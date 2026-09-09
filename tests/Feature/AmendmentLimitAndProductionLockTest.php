<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contract;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Services\SystemSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AmendmentLimitAndProductionLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_amendment_limit_and_production_lock_rules()
    {
        Storage::fake('public');
        echo "\n=== STARTING AMENDMENT LIMIT & PRODUCTION LOCK TEST ===\n";

        // 1. Setup User
        $customer = User::create([
            'name'     => 'PT Maju Terus',
            'email'    => 'customer@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $quotation = Quotation::create([
            'customer_id'  => $customer->id,
            'quotation_no' => 'QT-AMEND-001',
            'date_expired' => now()->addDays(30),
            'status'       => 'po',
        ]);

        $po = PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-TEST-001',
            'delivery_request' => now()->addDays(14),
            'status'           => 'contract',
        ]);

        $internal = PurchaseOrderInternal::create([
            'purchase_order_id' => $po->id,
            'po_no'             => 'PO-TEST-001-1',
            'item'              => 'Impeller Casting FC250',
            'qty'               => 100,
            'unit_price'        => 50000,
            'subtotal'          => 5000000,
            'status'            => 'contract',
        ]);

        $contract = Contract::create([
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $internal->id,
            'order_no'                   => 'PO-TEST-001',
            'contract_no'                => 'CTR-TEST-001',
            'status'                     => 'review',
            'amandement_no'              => 0,
        ]);

        $this->actingAs($customer);

        // Test 1: First amendment (#1) is allowed
        $response = $this->get(route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $internal->id]));
        $response->assertStatus(200);
        $response->assertViewHas('nextAmendmentNo', 1);

        $storeResponse = $this->post(route('purchase-orders.store-amandement', $po->id), [
            'purchase_order_internal_id' => $internal->id,
            'alasan_amandemen'           => 'Perubahan spec teknis #1',
            'attachments'                => UploadedFile::fake()->create('revisi_po_1.pdf', 200, 'application/pdf'),
        ]);
        $storeResponse->assertRedirect(route('purchase-orders.index'));

        $contract->refresh();
        $this->assertEquals(1, $contract->amandement_no);
        $this->assertEquals('amandement_pending', $contract->status);
        echo "[PASS] Amendment #1 submitted successfully.\n";

        // Simulate sales approval of Amendment #1
        $contract->update(['status' => 'review']);

        // Test 2: Second amendment (#2) is allowed
        $response = $this->get(route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $internal->id]));
        $response->assertStatus(200);
        $response->assertViewHas('nextAmendmentNo', 2);

        $storeResponse = $this->post(route('purchase-orders.store-amandement', $po->id), [
            'purchase_order_internal_id' => $internal->id,
            'alasan_amandemen'           => 'Perubahan spec teknis #2',
            'attachments'                => UploadedFile::fake()->create('revisi_po_2.pdf', 200, 'application/pdf'),
        ]);
        $storeResponse->assertRedirect(route('purchase-orders.index'));

        $contract->refresh();
        $this->assertEquals(2, $contract->amandement_no);
        echo "[PASS] Amendment #2 submitted successfully (Max 2 reached).\n";

        // Simulate sales review
        $contract->update(['status' => 'review']);

        // Test 3: Third amendment (#3) MUST BE BLOCKED (Quota Exceeded: 2/2)
        $response = $this->get(route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $internal->id]));
        $response->assertRedirect(route('purchase-orders.index'));
        $response->assertSessionHas('error');

        $storeResponse = $this->post(route('purchase-orders.store-amandement', $po->id), [
            'purchase_order_internal_id' => $internal->id,
            'alasan_amandemen'           => 'Percobaan amandemen ke-3',
            'attachments'                => UploadedFile::fake()->create('revisi_po_3.pdf', 200, 'application/pdf'),
        ]);
        $storeResponse->assertRedirect(route('purchase-orders.index'));
        $storeResponse->assertSessionHas('error');

        $contract->refresh();
        $this->assertEquals(2, $contract->amandement_no, 'Amandement_no must remain 2 and not increment to 3');
        echo "[PASS] Amendment #3 correctly BLOCKED by quota limit (2/2).\n";

        // Test 4: Production status lock test
        // Reset amandement_no to 0 but set status to production
        $contract->update(['amandement_no' => 0, 'status' => 'production']);
        $po->update(['status' => 'production']);
        $internal->update(['status' => 'production']);

        $response = $this->get(route('purchase-orders.create-amandement', ['id' => $po->id, 'internal_id' => $internal->id]));
        $response->assertRedirect(route('purchase-orders.index'));
        $response->assertSessionHas('error');

        $storeResponse = $this->post(route('purchase-orders.store-amandement', $po->id), [
            'purchase_order_internal_id' => $internal->id,
            'alasan_amandemen'           => 'Coba amandemen saat sedang produksi',
            'attachments'                => UploadedFile::fake()->create('revisi_po_prod.pdf', 200, 'application/pdf'),
        ]);
        $storeResponse->assertRedirect(route('purchase-orders.index'));
        $storeResponse->assertSessionHas('error');
        echo "[PASS] Amendment correctly BLOCKED when status is in production.\n";

        echo "=== ALL AMENDMENT LIMIT & PRODUCTION LOCK TESTS PASSED! ===\n";
    }
}
