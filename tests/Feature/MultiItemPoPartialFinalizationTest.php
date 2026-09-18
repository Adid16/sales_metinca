<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\RequestProject;
use App\Models\RequestProjectAssignment;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultiItemPoPartialFinalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_finalizing_single_sub_po_does_not_force_all_items_to_production()
    {
        echo "\n=== STARTING MULTI-ITEM SUB-PO PARTIAL FINALIZATION TEST ===\n";

        // 1. Setup Users
        $admin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $salesPic = User::create([
            'name'     => 'Sales PIC',
            'email'    => 'sales@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        $mgrSales = User::create(['name' => 'Mgr Sales', 'email' => 'mgr_sales@metinca.com', 'password' => bcrypt('password'), 'role' => 'manager', 'divisi' => 'sales']);
        $mgrQc    = User::create(['name' => 'Mgr QC', 'email' => 'mgr_qc@metinca.com', 'password' => bcrypt('password'), 'role' => 'manager', 'divisi' => 'quality']);
        $mgrPpc   = User::create(['name' => 'Mgr PPC', 'email' => 'mgr_ppc@metinca.com', 'password' => bcrypt('password'), 'role' => 'manager', 'divisi' => 'ppc']);
        $mgrDe    = User::create(['name' => 'Mgr DE', 'email' => 'mgr_de@metinca.com', 'password' => bcrypt('password'), 'role' => 'manager', 'divisi' => 'design engineering']);

        $customer = User::create([
            'name'     => 'PT Multi Mitra',
            'email'    => 'customer@multimitra.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        // 2. Setup Request, Quotation with 2 Items
        $requestProject = RequestProject::create([
            'customer_id' => $customer->id,
            'name'        => $customer->name,
            'email'       => $customer->email,
            'subject'     => 'Request Multi Item Order',
            'message'     => 'Order 2 part berbeda',
        ]);

        RequestProjectAssignment::create([
            'request_project_id' => $requestProject->id,
            'sales_id'           => $salesPic->id,
        ]);

        $quotation = Quotation::create([
            'customer_id'  => $customer->id,
            'request_id'   => $requestProject->id,
            'quotation_no' => 'QT-MULTI-001',
            'date_expired' => now()->addDays(30),
            'status'       => 'accepted',
        ]);

        $qItem1 = $quotation->items()->create([
            'item'                 => 'Flange Adapter 4 Inch',
            'qty'                  => 20,
            'original_price'       => 150000,
            'price'                => 150000,
            'floor_price'          => 120000,
            'is_below_floor_price' => false,
        ]);

        $qItem2 = $quotation->items()->create([
            'item'                 => 'Bracket Mounting Hub',
            'qty'                  => 10,
            'original_price'       => 250000,
            'price'                => 250000,
            'floor_price'          => 200000,
            'is_below_floor_price' => false,
        ]);

        // 3. Setup PO Master
        $po = PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-MULTI-2026-001',
            'delivery_request' => now()->addDays(25),
            'status'           => 'sent',
        ]);

        // 4. Setup 2 Sub-PO Internals
        $poi1 = PurchaseOrderInternal::create([
            'purchase_order_id' => $po->id,
            'po_no'             => 'PO-MULTI-2026-001-1',
            'item'              => 'Flange Adapter 4 Inch',
            'qty'               => 20,
            'unit_price'        => 150000,
            'subtotal'          => 3000000,
            'status'            => 'review',
        ]);

        $poi2 = PurchaseOrderInternal::create([
            'purchase_order_id' => $po->id,
            'po_no'             => 'PO-MULTI-2026-001-2',
            'item'              => 'Bracket Mounting Hub',
            'qty'               => 10,
            'unit_price'        => 250000,
            'subtotal'          => 2500000,
            'status'            => 'review',
        ]);

        // 5. Setup Contracts for both items
        $contract1 = Contract::create([
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $poi1->id,
            'order_no'                   => $po->po_no,
            'contract_no'                => 'CTR-MULTI-001-1',
            'part_name'                  => 'Flange Adapter 4 Inch',
            'status'                     => 'review',
        ]);

        $contract2 = Contract::create([
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $poi2->id,
            'order_no'                   => $po->po_no,
            'contract_no'                => 'CTR-MULTI-001-2',
            'part_name'                  => 'Bracket Mounting Hub',
            'status'                     => 'review',
        ]);

        // 6. Approve ONLY Contract 1 (4 divisions)
        $this->actingAs($mgrSales)->patch(route('contracts.approve-manager', $contract1->id), ['signature' => 'sig_sales'])->assertSessionHas('success');
        $this->actingAs($mgrQc)->patch(route('contracts.approve-manager', $contract1->id), ['signature' => 'sig_qc'])->assertSessionHas('success');
        $this->actingAs($mgrPpc)->patch(route('contracts.approve-manager', $contract1->id), ['signature' => 'sig_ppc'])->assertSessionHas('success');
        $this->actingAs($mgrDe)->patch(route('contracts.approve-manager', $contract1->id), ['signature' => 'sig_de'])->assertSessionHas('success');

        $contract1->refresh();
        $this->assertEquals('approved', $contract1->status);

        // 7. Sales PIC finalizes ONLY Contract 1
        $responseFinalize1 = $this->actingAs($salesPic)->post(route('contracts.finalize', $contract1->id));
        $responseFinalize1->assertSessionHas('success');

        $contract1->refresh();
        $poi1->refresh();
        $contract2->refresh();
        $poi2->refresh();
        $po->refresh();

        // ASSERTIONS:
        // Contract 1 and PO Internal 1 MUST be in production
        $this->assertEquals('production', $contract1->status);
        $this->assertEquals('production', $poi1->status);
        echo "  [PASS] Sub-PO 1 (Contract & Internal) successfully updated to 'production'.\n";

        // Contract 2 and PO Internal 2 MUST NOT be in production (remains review)
        $this->assertEquals('review', $contract2->status);
        $this->assertEquals('review', $poi2->status);
        echo "  [PASS] Sub-PO 2 is NOT forced to production (remains 'review').\n";

        // Master PO MUST NOT be in production because Sub-PO 2 is still in review
        $this->assertNotEquals('production', $po->status);
        echo "  [PASS] Master PO status is NOT prematurely updated to 'production'.\n";

        // Customer can still access createAmandement for Sub-PO 2
        $responseCustomerAmend2 = $this->actingAs($customer)->get(route('purchase-orders.create-amandement', [
            'id'          => $po->id,
            'internal_id' => $poi2->id
        ]));
        $responseCustomerAmend2->assertStatus(200);
        echo "  [PASS] Customer can still apply for amendment on Sub-PO 2 without being blocked by production lock.\n";

        // Customer IS BLOCKED from applying amendment on Sub-PO 1 (which is already in production)
        $responseCustomerAmend1 = $this->actingAs($customer)->get(route('purchase-orders.create-amandement', [
            'id'          => $po->id,
            'internal_id' => $poi1->id
        ]));
        $responseCustomerAmend1->assertRedirect(route('purchase-orders.index'));
        $responseCustomerAmend1->assertSessionHas('error');
        echo "  [PASS] Customer is correctly blocked from amending Sub-PO 1 (already in production).\n";

        // 8. Now approve and finalize Sub-PO 2
        $this->actingAs($mgrSales)->patch(route('contracts.approve-manager', $contract2->id), ['signature' => 'sig_sales'])->assertSessionHas('success');
        $this->actingAs($mgrQc)->patch(route('contracts.approve-manager', $contract2->id), ['signature' => 'sig_qc'])->assertSessionHas('success');
        $this->actingAs($mgrPpc)->patch(route('contracts.approve-manager', $contract2->id), ['signature' => 'sig_ppc'])->assertSessionHas('success');
        $this->actingAs($mgrDe)->patch(route('contracts.approve-manager', $contract2->id), ['signature' => 'sig_de'])->assertSessionHas('success');

        $this->actingAs($salesPic)->post(route('contracts.finalize', $contract2->id))->assertSessionHas('success');

        $contract2->refresh();
        $poi2->refresh();
        $po->refresh();

        // Now both items are in production, Master PO MUST BE in production
        $this->assertEquals('production', $contract2->status);
        $this->assertEquals('production', $poi2->status);
        $this->assertEquals('production', $po->status);
        echo "  [PASS] After all Sub-POs are finalized, Master PO is now 'production'.\n";

        echo "=== ALL MULTI-ITEM SUB-PO PARTIAL FINALIZATION TESTS PASSED! ===\n\n";
    }
}
