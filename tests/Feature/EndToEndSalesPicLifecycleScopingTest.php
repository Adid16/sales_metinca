<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\RequestProject;
use App\Models\RequestProjectAssignment;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Negotiate;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;
use App\Models\ContractRequirement;
use App\Services\SystemSettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EndToEndSalesPicLifecycleScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_end_to_end_sales_pic_scoping_from_request_to_contract()
    {
        echo "\n=== STARTING END-TO-END SALES PIC SCOPING TEST ===\n";

        // 1. SETUP USERS
        $admin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $mgrSales = User::create([
            'name'     => 'Pak Bambang Mgr Sales',
            'email'    => 'mgr_sales@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'sales',
        ]);

        $mgrDe = User::create([
            'name'     => 'Pak Eko Mgr Engineering',
            'email'    => 'mgr_de@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'design engineering',
        ]);

        $mgrPpc = User::create([
            'name'     => 'Pak Joko Mgr PPC',
            'email'    => 'mgr_ppc@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'ppc',
        ]);

        $mgrQc = User::create([
            'name'     => 'Bu Siti Mgr QC',
            'email'    => 'mgr_qc@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'quality',
        ]);

        $salesPic1 = User::create([
            'name'     => 'Andi Sales PIC',
            'email'    => 'andi@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        $salesPic2 = User::create([
            'name'     => 'Budi Sales Non PIC',
            'email'    => 'budi@metinca.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        $customer = User::create([
            'name'     => 'PT Global Mitra',
            'email'    => 'customer@global.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        // 2. SETUP ARTICLE MASTER
        $article = Article::create([
            'customer_id'       => $customer->id,
            'article_no'        => 'ART-E2E-001',
            'part_name'         => 'Impeller Casting Hub',
            'internal_part_no'  => 'INT-ICH-001',
            'price'             => 250000,
            'price_list'        => 250000,
            'floor_price'       => 200000,
            'effective_date'    => now()->format('Y-m-d'),
            'lokasi_pengerjaan' => 'Foundry & Machining',
        ]);

        // 3. STAGE 1: REQUEST PROJECT & ASSIGNMENT
        $requestProject = RequestProject::create([
            'customer_id' => $customer->id,
            'name'        => $customer->name,
            'email'       => $customer->email,
            'subject'     => 'Request Penawaran Impeller Casting Hub',
            'message'     => 'Mohon penawaran untuk 50 unit',
        ]);

        // Assign to Sales PIC 1
        RequestProjectAssignment::create([
            'request_project_id' => $requestProject->id,
            'sales_id'           => $salesPic1->id,
        ]);

        echo "[STAGE 1 - REQUEST ASSIGNMENT] Request assigned to: {$salesPic1->name} (ID: {$salesPic1->id})\n";

        // Sales PIC 2 tries to access create Quotation for this request -> MUST BE BLOCKED
        $responsePic2CreateQuotation = $this->actingAs($salesPic2)->get(route('quotations.create', ['request_id' => $requestProject->id]));
        $responsePic2CreateQuotation->assertRedirect(route('requests-project.index'));
        $responsePic2CreateQuotation->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 (Non-PIC) blocked from creating Quotation for Request assigned to PIC 1.\n";

        // Sales PIC 1 accesses create Quotation -> SUCCESS
        $responsePic1CreateQuotation = $this->actingAs($salesPic1)->get(route('quotations.create', ['request_id' => $requestProject->id]));
        $responsePic1CreateQuotation->assertStatus(200);
        echo "  [PASS] Sales PIC 1 allowed to create Quotation.\n";

        // 4. STAGE 2: QUOTATION & NEGOTIATION
        $quotation = Quotation::create([
            'customer_id'             => $customer->id,
            'request_id'              => $requestProject->id,
            'quotation_no'            => 'QT-E2E-001',
            'date_expired'            => now()->addDays(30),
            'status'                  => 'created',
            'is_below_floor_price'    => false,
            'manager_approval_status' => 'none',
        ]);

        $qItem = $quotation->items()->create([
            'article_id'           => $article->id,
            'item'                 => $article->part_name,
            'qty'                  => 50,
            'original_price'       => 250000,
            'price'                => 250000,
            'floor_price'          => 200000,
            'is_below_floor_price' => false,
        ]);

        // Index Scoping: Sales PIC 2 should not see PIC 1's quotation in index
        $responsePic2Index = $this->actingAs($salesPic2)->get(route('quotations.index'));
        $responsePic2Index->assertDontSee($quotation->quotation_no);
        echo "  [PASS] Sales PIC 2 does not see PIC 1's Quotation in index.\n";

        $responsePic1Index = $this->actingAs($salesPic1)->get(route('quotations.index'));
        $responsePic1Index->assertSee($quotation->quotation_no);
        echo "  [PASS] Sales PIC 1 sees own Quotation in index.\n";

        // Sales PIC 2 tries to edit, update, send, or delete Quotation -> ALL BLOCKED
        $this->actingAs($salesPic2)->get(route('quotations.edit', $quotation->id))->assertRedirect(route('quotations.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->put(route('quotations.update', $quotation->id), ['notes' => 'Hacked by Budi'])->assertRedirect(route('quotations.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->patch(route('quotations.send', $quotation->id))->assertRedirect(route('quotations.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->delete(route('quotations.destroy', $quotation->id))->assertRedirect(route('quotations.index'))->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 blocked from editing/updating/sending/deleting PIC 1's Quotation.\n";

        // Sales PIC 1 sends Quotation -> SUCCESS
        $responseSend = $this->actingAs($salesPic1)->patch(route('quotations.send', $quotation->id));
        $responseSend->assertRedirect(route('quotations.index'));
        $quotation->refresh();
        $this->assertEquals('sent', $quotation->status);
        echo "  [PASS] Sales PIC 1 successfully sent Quotation to Customer.\n";

        // Customer negotiates
        $this->actingAs($customer)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'negotiate',
            'negotiation_message' => 'Nego harga Rp 220.000 per unit ya pak',
            'items'               => [
                [
                    'id'               => $qItem->id,
                    'negotiated_price' => 220000,
                ]
            ]
        ]);
        $quotation->refresh();
        $this->assertEquals('negotiating', $quotation->status);

        // Sales PIC 2 tries to reply to negotiation -> BLOCKED
        $responsePic2Nego = $this->actingAs($salesPic2)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'negotiate',
            'negotiation_message' => 'Balasan dari Budi non-PIC',
            'items'               => [
                [
                    'id'               => $qItem->id,
                    'negotiated_price' => 230000,
                ]
            ]
        ]);
        $responsePic2Nego->assertRedirect(route('quotations.index'));
        $responsePic2Nego->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 blocked from replying to Negotiation.\n";

        // Sales PIC 1 replies to negotiation -> SUCCESS
        $responsePic1Nego = $this->actingAs($salesPic1)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'negotiate',
            'negotiation_message' => 'Kami tawarkan Rp 230.000 pak',
            'items'               => [
                [
                    'id'               => $qItem->id,
                    'negotiated_price' => 230000,
                ]
            ]
        ]);
        $responsePic1Nego->assertRedirect(route('negotiate.show-nego', $quotation->id));
        echo "  [PASS] Sales PIC 1 successfully sent counter offer.\n";

        // Customer accepts
        $this->actingAs($customer)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'accept',
            'negotiation_message' => 'Deal Rp 230.000',
            'items'               => [
                [
                    'id'               => $qItem->id,
                    'negotiated_price' => 230000,
                ]
            ]
        ]);
        $quotation->refresh();
        $this->assertEquals('accepted', $quotation->status);

        // 5. STAGE 3: PURCHASE ORDER SCOPING
        $po = PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-E2E-001',
            'delivery_request' => now()->addDays(20),
            'status'           => 'open',
        ]);

        // Accessor check
        $this->assertNotNull($po->sales_pic);
        $this->assertEquals($salesPic1->id, $po->sales_pic->id);
        echo "[STAGE 3 - PURCHASE ORDER] PO Sales PIC correctly resolved: {$po->sales_pic->name}\n";

        // PO Index scoping for Staff Sales
        $responsePoIndexPic2 = $this->actingAs($salesPic2)->get(route('purchase-orders.index'));
        $responsePoIndexPic2->assertDontSee($po->po_no);
        echo "  [PASS] Sales PIC 2 does not see PIC 1's Purchase Order in index.\n";

        $responsePoIndexPic1 = $this->actingAs($salesPic1)->get(route('purchase-orders.index'));
        $responsePoIndexPic1->assertSee($po->po_no);
        echo "  [PASS] Sales PIC 1 sees own Purchase Order in index.\n";

        // Sales PIC 2 tries to edit PO or create contract from PO -> BLOCKED
        $this->actingAs($salesPic2)->get(route('purchase-orders.edit', $po->id))->assertRedirect(route('purchase-orders.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->put(route('purchase-orders.update', $po->id), ['status' => 'production'])->assertRedirect(route('purchase-orders.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->get(route('purchase-orders.create-contract', $po->id))->assertRedirect(route('purchase-orders.index'))->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 blocked from editing PO or creating Contract from PO.\n";

        // Sales PIC 1 can access create contract from PO -> SUCCESS
        $responsePic1CreateContract = $this->actingAs($salesPic1)->get(route('purchase-orders.create-contract', $po->id));
        $responsePic1CreateContract->assertStatus(200);
        echo "  [PASS] Sales PIC 1 allowed to create Contract from PO.\n";

        // 6. STAGE 4: PURCHASE ORDER INTERNAL SCOPING
        // Sales PIC 2 tries to store PO Internal for this PO -> BLOCKED
        $responsePic2StorePoi = $this->actingAs($salesPic2)->post(route('purchase-orders-internal.store', $po->id), [
            'item'          => ['Impeller Casting Hub'],
            'qty'           => [50],
            'unit_price'    => [230000],
            'po_no'         => ['PO-E2E-001-1'],
        ]);
        $responsePic2StorePoi->assertRedirect(route('purchase-orders.index'));
        $responsePic2StorePoi->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 blocked from creating PO Internal for PIC 1's PO.\n";

        // Sales PIC 1 creates PO Internal -> SUCCESS
        $responsePic1StorePoi = $this->actingAs($salesPic1)->post(route('purchase-orders-internal.store', $po->id), [
            'item'          => ['Impeller Casting Hub'],
            'qty'           => [50],
            'unit_price'    => [230000],
            'po_no'         => ['PO-E2E-001-1'],
        ]);
        $responsePic1StorePoi->assertRedirect(route('purchase-orders-internal.show', $po->id));
        echo "  [PASS] Sales PIC 1 successfully created PO Internal.\n";

        $poi = PurchaseOrderInternal::where('purchase_order_id', $po->id)->first();
        $this->assertNotNull($poi);
        $this->assertEquals($salesPic1->id, $poi->sales_pic->id);

        // Sales PIC 2 tries to edit, update, or delete PO Internal item -> BLOCKED
        $this->actingAs($salesPic2)->get(route('purchase-orders-internal.edit', $po->id))->assertRedirect(route('purchase-orders.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->put(route('purchase-orders-internal.update', $po->id), [
            'item'          => ['Impeller Casting Hub'],
            'qty'           => [99],
            'unit_price'    => [230000],
        ])->assertRedirect(route('purchase-orders.index'))->assertSessionHas('error');
        $this->actingAs($salesPic2)->delete(route('purchase-orders-internal.destroy-item', $poi->id))->assertStatus(403);
        echo "  [PASS] Sales PIC 2 blocked from editing/updating/deleting PO Internal item.\n";

        // 7. STAGE 5: CONTRACT REVIEW SHEET SCOPING & FINALIZATION
        // Sales PIC 2 tries to store Contract -> BLOCKED
        $responsePic2StoreContract = $this->actingAs($salesPic2)->post(route('contracts.store'), [
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $poi->id,
            'order_no'                   => $po->po_no,
            'contract_no'                => 'CTR-E2E-001',
            'part_no'                    => $article->article_no,
            'part_name'                  => $article->part_name,
            'drawing_no'                 => 'DWG-001',
            'material'                   => 'FC250',
            'weight'                     => 15.5,
            'status'                     => 'review',
            'requirements'               => [
                'sales' => [
                    [
                        'requirement_from'  => 'sales',
                        'requirement'       => 'price',
                        'requirement_value' => 'Rp 230.000',
                    ]
                ]
            ]
        ]);
        $responsePic2StoreContract->assertRedirect(route('contracts.index'));
        $responsePic2StoreContract->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 blocked from creating Contract Review Sheet.\n";

        // Sales PIC 1 creates Contract -> SUCCESS
        $responsePic1StoreContract = $this->actingAs($salesPic1)->post(route('contracts.store'), [
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $poi->id,
            'order_no'                   => $po->po_no,
            'contract_no'                => 'CTR-E2E-001',
            'part_no'                    => $article->article_no,
            'part_name'                  => $article->part_name,
            'drawing_no'                 => 'DWG-001',
            'material'                   => 'FC250',
            'weight'                     => 15.5,
            'status'                     => 'review',
            'requirements'               => [
                'sales' => [
                    [
                        'requirement_from'  => 'sales',
                        'requirement'       => 'price',
                        'requirement_value' => 'Rp 230.000',
                    ]
                ]
            ]
        ]);
        $responsePic1StoreContract->assertRedirect(route('contracts.index'));
        echo "  [PASS] Sales PIC 1 successfully created Contract Review Sheet.\n";

        $contract = Contract::where('order_no', $po->po_no)->first();
        $this->assertNotNull($contract);
        $this->assertEquals($salesPic1->id, $contract->sales_pic->id);

        // 4 Divisions approve the contract
        $this->actingAs($mgrSales)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_sales'])->assertSessionHas('success');
        $this->actingAs($mgrDe)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_de'])->assertSessionHas('success');
        $this->actingAs($mgrPpc)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_ppc'])->assertSessionHas('success');
        $this->actingAs($mgrQc)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_qc'])->assertSessionHas('success');

        $contract->refresh();
        $this->assertEquals('approved', $contract->status);
        echo "[STAGE 5 - CONTRACT] Contract fully approved by 4 divisions. Current status: {$contract->status}\n";

        // Sales PIC 2 tries to finalize Contract -> BLOCKED
        $responsePic2Finalize = $this->actingAs($salesPic2)->post(route('contracts.finalize', $contract->id));
        $responsePic2Finalize->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 blocked from finalizing Contract to Production.\n";

        // Sales PIC 1 finalizes Contract -> SUCCESS (status becomes production)
        $responsePic1Finalize = $this->actingAs($salesPic1)->post(route('contracts.finalize', $contract->id));
        $responsePic1Finalize->assertSessionHas('success');

        $contract->refresh();
        $this->assertEquals('production', $contract->status);
        echo "  [PASS] Sales PIC 1 successfully finalized Contract to 'production'.\n";

        // 8. STAGE 6: AMANDEMEN PO APPROVAL & REJECTION SCOPING
        echo "[STAGE 6 - AMANDEMEN PO SCOPING] Setting up PO Amandemen for Sales PIC 1...\n";
        $po2 = PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-E2E-AMEND-002',
            'delivery_request' => now()->addDays(14),
            'status'           => 'contract',
        ]);

        $poi2 = PurchaseOrderInternal::create([
            'purchase_order_id' => $po2->id,
            'po_no'             => 'PO-E2E-AMEND-002-1',
            'item'              => 'Impeller Casting Hub Amandemen',
            'qty'               => 40,
            'unit_price'        => 230000,
            'status'            => 'contract',
        ]);

        $contract2 = Contract::create([
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $poi2->id,
            'order_no'                   => $po2->po_no,
            'contract_no'                => 'CTR-E2E-AMEND-002',
            'status'                     => 'amandement_pending',
            'alasan_amandemen'           => 'Permintaan perubahan kuantitas item',
            'amandement_no'              => 1,
        ]);

        // Sales PIC 2 checks /approval-amandement -> MUST NOT SEE PIC 1's pending amandemen
        $responsePic2AmandIndex = $this->actingAs($salesPic2)->get(route('purchase-orders.approval-amandement'));
        $responsePic2AmandIndex->assertDontSee('PO-E2E-AMEND-002');
        echo "  [PASS] Sales PIC 2 does not see PIC 1's pending amandemen in approval index.\n";

        // Sales PIC 1 checks /approval-amandement -> SEES own pending amandemen
        $responsePic1AmandIndex = $this->actingAs($salesPic1)->get(route('purchase-orders.approval-amandement'));
        $responsePic1AmandIndex->assertSee('PO-E2E-AMEND-002');
        echo "  [PASS] Sales PIC 1 sees own pending amandemen in approval index.\n";

        // Sales PIC 2 attempts to approve PIC 1's amandemen -> BLOCKED
        $responsePic2Approve = $this->actingAs($salesPic2)->post(route('purchase-orders.approve-amandement', $po2->id), [
            'contract_id'                => $contract2->id,
            'purchase_order_internal_id' => $poi2->id,
            'catatan'                    => 'Approve ilegal dari PIC 2',
        ]);
        $responsePic2Approve->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 BLOCKED from approving PIC 1's amandemen.\n";

        // Sales PIC 2 attempts to reject PIC 1's amandemen -> BLOCKED
        $responsePic2Reject = $this->actingAs($salesPic2)->post(route('purchase-orders.reject-amandement', $po2->id), [
            'contract_id'                => $contract2->id,
            'purchase_order_internal_id' => $poi2->id,
            'alasan_penolakan'           => 'Reject ilegal dari PIC 2',
        ]);
        $responsePic2Reject->assertSessionHas('error');
        echo "  [PASS] Sales PIC 2 BLOCKED from rejecting PIC 1's amandemen.\n";

        // Sales PIC 1 successfully approves own amandemen -> SUCCESS
        $responsePic1Approve = $this->actingAs($salesPic1)->post(route('purchase-orders.approve-amandement', $po2->id), [
            'contract_id'                => $contract2->id,
            'purchase_order_internal_id' => $poi2->id,
            'catatan'                    => 'Amandemen disetujui oleh Sales PIC 1 resmi.',
        ]);
        $responsePic1Approve->assertSessionHas('success');
        $contract2->refresh();
        $this->assertEquals('amandement', $contract2->status);
        echo "  [PASS] Assigned Sales PIC 1 successfully approved own amandemen.\n";

        // Manager Sales & Admin Superpower: Can view & perform actions
        $responseAdminShow = $this->actingAs($admin)->get(route('contracts.show', $contract->id));
        $responseAdminShow->assertStatus(200);
        $responseMgrShow = $this->actingAs($mgrSales)->get(route('contracts.show', $contract->id));
        $responseMgrShow->assertStatus(200);
        echo "  [PASS] Admin and Manager Sales can access and oversee the entire lifecycle.\n";

        echo "\n=== ALL END-TO-END SALES PIC SCOPING TESTS PASSED PERFECTLY ===\n\n";
    }
}
