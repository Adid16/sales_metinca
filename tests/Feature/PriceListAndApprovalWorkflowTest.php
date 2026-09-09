<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Negotiate;
use App\Services\SystemSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PriceListAndApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_free_negotiation_and_sales_floor_price_enforcement_workflow()
    {
        echo "\n=== STARTING CUSTOMER FREE NEGO & SALES FLOOR ENFORCEMENT WORKFLOW TEST ===\n";

        // 1. Create Users
        $customer = User::create([
            'name'     => 'PT Customer Maju',
            'email'    => 'customer@example.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $staffSales = User::create([
            'name'     => 'Budi Staff Sales',
            'email'    => 'staff@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        // 2. Create Article with Price List and Floor Price (modal bahan + modal proses)
        $article = Article::create([
            'customer_id'       => $customer->id,
            'article_no'        => 'ART-TEST-001',
            'part_name'         => 'Flange Bracket Test',
            'internal_part_no'  => 'INT-FBT-001',
            'price'             => 100000,
            'price_list'        => 100000,
            'floor_price'       => 85000, // Harga dasar (modal bahan + proses)
            'bottom_price'      => 85000,
            'effective_date'    => now()->format('Y-m-d'),
            'lokasi_pengerjaan' => 'Machining',
        ]);

        $this->assertEquals(100000, $article->effective_price_list);
        $this->assertEquals(85000, $article->effective_floor_price);
        echo "[PASS] Master Article Price List: {$article->effective_price_list}, Floor Price: {$article->effective_floor_price}\n";

        // 3. Create Quotation with Item
        $quotation = Quotation::create([
            'customer_id'             => $customer->id,
            'quotation_no'            => 'QT-TEST-0001',
            'date_expired'            => now()->addDays(30),
            'status'                  => 'sent',
            'is_below_floor_price'    => false,
            'manager_approval_status' => 'none',
        ]);

        $item = $quotation->items()->create([
            'article_id'           => $article->id,
            'item'                 => $article->part_name,
            'qty'                  => 10,
            'original_price'       => $article->effective_price_list,
            'price'                => $article->effective_price_list,
            'floor_price'          => $article->effective_floor_price,
            'is_below_floor_price' => false,
        ]);

        $this->assertEquals(100000, $item->original_price);
        $this->assertEquals(85000, $item->floor_price);
        echo "[PASS] Quotation #{$quotation->quotation_no} created with initial price: {$item->price}\n";

        // 4. Sales CANNOT initiate negotiation first (must be initiated by Customer)
        $salesEarlyNego = $this->actingAs($staffSales)->post(route('negotiate.store', $quotation->id), [
            'negotiation_message'      => 'Penawaran awal diskon dari sales',
            'items'                    => [
                [
                    'id'               => $item->id,
                    'negotiated_price' => 95000,
                ]
            ],
            'action'                   => 'negotiate',
        ]);
        $salesEarlyNego->assertSessionHas('error');
        $this->assertEquals(0, $quotation->negotiates()->count());
        echo "[PASS] Sales correctly BLOCKED from initiating negotiation before Customer.\n";

        // 5. Customer is FREE to submit negotiation below floor price (e.g. 80.000 < 85.000) -> MUST SUCCEED
        $response = $this->actingAs($customer)->post(route('negotiate.store', $quotation->id), [
            'negotiation_message'      => 'Mohon diskon harga menjadi Rp 80.000 per pcs',
            'items'                    => [
                [
                    'id'               => $item->id,
                    'negotiated_price' => 80000,
                ]
            ],
            'action'                   => 'negotiate',
        ]);

        $response->assertSessionHas('success');
        $quotation->refresh();
        $item->refresh();

        $this->assertEquals('negotiating', $quotation->status);
        $this->assertEquals(1, $quotation->negotiates()->count());
        $this->assertTrue((bool) $quotation->is_below_floor_price);
        echo "[PASS] Customer successfully submitted negotiation with offered price 80.000 (< 85.000). Status: 'negotiating'.\n";

        // 5. Sales CANNOT accept/close customer's offer directly when it is below floor price
        $closeBlocked = $this->actingAs($staffSales)->post(route('negotiate.close', $quotation->id));
        $closeBlocked->assertSessionHas('error');
        $quotation->refresh();
        $this->assertEquals('negotiating', $quotation->status);
        echo "[PASS] Sales correctly BLOCKED from accepting customer offer below floor price.\n";

        // 6. Sales CANNOT counter-offer below floor price (e.g. 82.000 < 85.000)
        $salesUnderFloor = $this->actingAs($staffSales)->post(route('negotiate.store', $quotation->id), [
            'negotiation_message'      => 'Penawaran dari Sales Rp 82.000',
            'items'                    => [
                [
                    'id'               => $item->id,
                    'negotiated_price' => 82000,
                ]
            ],
            'action'                   => 'negotiate',
        ]);
        $salesUnderFloor->assertSessionHas('error');
        echo "[PASS] Sales correctly BLOCKED from counter-offering below floor price (82.000 < 85.000).\n";

        // 7. Sales counter-offers at or above floor price (e.g. 88.000 >= 85.000) -> SUCCEEDS
        $salesCounter = $this->actingAs($staffSales)->post(route('negotiate.store', $quotation->id), [
            'negotiation_message'      => 'Harga terbaik dari kami adalah Rp 88.000 per pcs',
            'items'                    => [
                [
                    'id'               => $item->id,
                    'negotiated_price' => 88000,
                ]
            ],
            'action'                   => 'negotiate',
        ]);
        $salesCounter->assertSessionHas('success');
        $quotation->refresh();
        $item->refresh();

        $this->assertEquals('negotiating', $quotation->status);
        $this->assertFalse((bool) $quotation->is_below_floor_price);
        echo "[PASS] Sales successfully counter-offered valid price (88.000 >= 85.000).\n";

        // 8. Customer accepts Sales counter-offer -> finalizes to 'accepted'
        $customerAccept = $this->actingAs($customer)->post(route('negotiate.close', $quotation->id));
        $customerAccept->assertSessionHas('success');
        $quotation->refresh();
        $item->refresh();

        $this->assertEquals('accepted', $quotation->status);
        $this->assertEquals(88000, $item->price);
        echo "[PASS] Customer accepted Sales counter-offer -> Final Agreed Price: {$item->price}, Status: 'accepted'.\n";

        echo "=== ALL CUSTOMER FREE NEGO & SALES FLOOR WORKFLOW TESTS PASSED! ===\n\n";
    }
}
