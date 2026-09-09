<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contract;
use App\Models\ContractRequirement;
use App\Models\Quotation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractRejectionAndRevisionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_rejection_and_targeted_revision_workflow()
    {
        echo "\n=== STARTING CONTRACT REJECTION & TARGETED REVISION TEST ===\n";

        // 1. Buat User: Manager Sales, Manager Quality, Staff Sales, Customer
        $mgrSales = User::create([
            'name'     => 'Pak Budi Mgr Sales',
            'email'    => 'mgr_sales@test.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'sales',
        ]);

        $mgrQuality = User::create([
            'name'     => 'Bu Siti Mgr Quality',
            'email'    => 'mgr_qc@test.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'quality',
        ]);

        $staffSales = User::create([
            'name'     => 'Mas Anton Sales',
            'email'    => 'staff_sales@test.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        $customer = User::create([
            'name'     => 'PT Pelanggan Sejati',
            'email'    => 'customer@test.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $quotation = Quotation::create([
            'customer_id'  => $customer->id,
            'quotation_no' => 'QT-TEST-REJECT-01',
            'date_expired' => now()->addDays(30),
            'status'       => 'accepted',
        ]);

        // 2. Buat Kontrak
        $contract = Contract::create([
            'customer_id'  => $customer->id,
            'quotation_id' => $quotation->id,
            'order_no'     => 'PO-TEST-REJECT-001',
            'contract_no'  => 'CT-2026-TEST-01',
            'part_no'      => 'PART-TEST-001',
            'part_name'    => 'Flange Bracket Test',
            'status'       => 'review',
        ]);

        // Requirements
        $reqSales = ContractRequirement::create([
            'contract_id'       => $contract->id,
            'requirement_from'  => 'sales',
            'requirement'       => 'price',
            'requirement_value' => 'Rp 50.000',
        ]);

        $reqQuality = ContractRequirement::create([
            'contract_id'       => $contract->id,
            'requirement_from'  => 'quality',
            'requirement'       => 'inspection',
            'requirement_value' => 'Visual check 100%',
        ]);

        // 3. Manager Quality Approve duluan
        $response = $this->actingAs($mgrQuality)->patch(route('contracts.approve-manager', $contract->id), [
            'signature' => 'data:image/png;base64,sampleQualitySignature',
        ]);
        $response->assertSessionHas('success');

        $contract->refresh();
        $this->assertEquals($mgrQuality->id, $contract->quality_approver);
        echo "[PASS] Manager Quality approved first. Quality Approver ID: {$contract->quality_approver}\n";

        // 4. Manager Sales Menolak / Reject dengan alasan wajib
        $rejectReason = "Harga khusus belum disesuaikan dengan volume order minimal 100 pcs.";
        $response = $this->actingAs($mgrSales)->post(route('contracts.reject-manager', $contract->id), [
            'comment' => $rejectReason
        ]);
        $response->assertSessionHas('success');

        $contract->refresh();
        $this->assertEquals('revision', $contract->status);
        $this->assertEquals($rejectReason, $contract->sales_reject_reason);
        $this->assertNotNull($contract->sales_rejected_at);
        $this->assertEquals('sales', $contract->rejected_by_dept);
        $this->assertNull($contract->sales_approver);
        // Quality approval harus tetap terjaga!
        $this->assertEquals($mgrQuality->id, $contract->quality_approver);

        echo "[PASS] Manager Sales successfully rejected with reason: '{$contract->sales_reject_reason}'\n";
        echo "[PASS] Quality approval remained intact: {$contract->quality_approver}\n";

        // 4.5. Verifikasi Manager Sales diblokir dari Approve langsung sebelum direvisi
        $blockedApprove = $this->actingAs($mgrSales)->patch(route('contracts.approve-manager', $contract->id), [
            'signature' => 'data:image/png;base64,sampleSalesSignature',
        ]);
        $blockedApprove->assertSessionHas('error');
        echo "[PASS] Manager Sales correctly blocked from approving while in rejected state\n";

        // 5. Staff Sales melakukan revisi pada form edit
        $response = $this->actingAs($staffSales)->put(route('contracts.update', $contract->id), [
            'customer_id'  => $customer->id,
            'quotation_id' => $quotation->id,
            'order_no'     => $contract->order_no,
            'part_no'      => $contract->part_no,
            'part_name'    => $contract->part_name,
            'requirements' => [
                $reqSales->id => [
                    'requirement' => 'price',
                    'value'       => 'Rp 50.000 (Min order 100 pcs)',
                ],
                $reqQuality->id => [
                    'requirement' => 'inspection',
                    'value'       => 'MODIFIED_ATTEMPT', // Seharusnya tidak berubah karena Quality sudah approve
                ]
            ]
        ]);
        $response->assertSessionHas('success');

        $contract->refresh();
        $reqSales->refresh();
        $reqQuality->refresh();

        // Verifikasi requirement sales terupdate, sedangkan quality tetap terkunci
        $this->assertEquals('Rp 50.000 (Min order 100 pcs)', $reqSales->requirement_value);
        $this->assertEquals('Visual check 100%', $reqQuality->requirement_value);
        $this->assertEquals('review', $contract->status);
        $this->assertNull($contract->sales_reject_reason); // Reset antrean
        $this->assertNull($contract->sales_rejected_at);
        $this->assertEquals($mgrQuality->id, $contract->quality_approver); // Quality tetap approve!

        echo "[PASS] Sales revision updated successfully: '{$reqSales->requirement_value}'\n";
        echo "[PASS] Locked Quality requirement safely preserved: '{$reqQuality->requirement_value}'\n";
        echo "[PASS] Rejection state reset to review for Manager Sales\n";

        // 6. Manager Sales sekarang Approve kontrak yang telah direvisi
        $response = $this->actingAs($mgrSales)->patch(route('contracts.approve-manager', $contract->id), [
            'signature' => 'data:image/png;base64,sampleSalesSignature',
        ]);
        $response->assertSessionHas('success');

        $contract->refresh();
        $this->assertEquals($mgrSales->id, $contract->sales_approver);
        echo "[PASS] Manager Sales approved the revised contract! Both Quality and Sales approved.\n";
        echo "=== REJECTION & TARGETED REVISION WORKFLOW FULLY VERIFIED! ===\n";
    }

    public function test_all_4_divisions_approve_requires_assigned_sales_pic_finalization_to_production()
    {
        echo "\n=== STARTING 4-DIVISION APPROVAL & SALES PIC FINALIZATION TEST ===\n";

        // 1. Users setup
        $customer = User::create([
            'name'     => 'PT Auto Mitra',
            'email'    => 'automitra@customer.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        $assignedSales = User::create([
            'name'     => 'Sales PIC Dimas',
            'email'    => 'dimas_sales@test.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        $otherSales = User::create([
            'name'     => 'Sales Lain Joko',
            'email'    => 'joko_sales@test.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
            'divisi'   => 'sales',
        ]);

        $mgrSales = User::create([
            'name'     => 'Manager Sales Budi',
            'email'    => 'budi_sales_mgr@test.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'sales',
        ]);

        $mgrQuality = User::create([
            'name'     => 'Manager Quality Siti',
            'email'    => 'siti_qc_mgr@test.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'quality',
        ]);

        $mgrPpc = User::create([
            'name'     => 'Manager PPC Rian',
            'email'    => 'rian_ppc_mgr@test.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'ppc',
        ]);

        $mgrDe = User::create([
            'name'     => 'Manager DE Hendra',
            'email'    => 'hendra_de_mgr@test.com',
            'password' => bcrypt('password'),
            'role'     => 'manager',
            'divisi'   => 'design engineering',
        ]);

        // 2. Request Project with Assignment to assignedSales (PIC)
        $reqProject = \App\Models\RequestProject::create([
            'customer_id' => $customer->id,
            'name'        => 'Proyek Flange Precision',
            'email'       => 'automitra@customer.com',
            'company'     => 'PT Auto Mitra',
            'subject'     => 'Permintaan Flange Precision',
            'message'     => 'Kebutuhan Flange 500 pcs',
        ]);

        \App\Models\RequestProjectAssignment::create([
            'request_project_id' => $reqProject->id,
            'sales_id'           => $assignedSales->id,
        ]);

        // 3. Quotation, Purchase Order, and Internal Item
        $quotation = Quotation::create([
            'customer_id'  => $customer->id,
            'request_id'   => $reqProject->id,
            'quotation_no' => 'QT-2026-PIC-001',
            'date_expired' => now()->addDays(30),
            'status'       => 'accepted',
        ]);

        $po = \App\Models\PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-2026-PIC-001',
            'delivery_request' => now()->addDays(14),
            'status'           => 'process',
        ]);

        $poInternal = \App\Models\PurchaseOrderInternal::create([
            'purchase_order_id' => $po->id,
            'item'              => 'Flange Precision X',
            'qty'               => 500,
            'status'            => 'process',
        ]);

        // 4. Contract Creation
        $contract = Contract::create([
            'customer_id'                => $customer->id,
            'quotation_id'               => $quotation->id,
            'purchase_order_internal_id' => $poInternal->id,
            'order_no'                   => $po->po_no,
            'contract_no'                => 'CT-2026-PIC-001',
            'part_name'                  => 'Flange Precision X',
            'status'                     => 'review',
        ]);

        // Verify sales_pic accessor
        $this->assertNotNull($contract->sales_pic);
        $this->assertEquals($assignedSales->id, $contract->sales_pic->id);
        echo "[PASS] Contract sales_pic successfully resolved to: {$contract->sales_pic->name} (ID: {$contract->sales_pic->id})\n";

        // 5. 4 Managers Approve one by one
        // Manager Sales
        $this->actingAs($mgrSales)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_sales'])->assertSessionHas('success');
        // Manager Quality
        $this->actingAs($mgrQuality)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_quality'])->assertSessionHas('success');
        // Manager PPC
        $this->actingAs($mgrPpc)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_ppc'])->assertSessionHas('success');
        // Manager DE
        $this->actingAs($mgrDe)->patch(route('contracts.approve-manager', $contract->id), ['signature' => 'sig_de'])->assertSessionHas('success');

        $contract->refresh();
        // Assert that all 4 are approved AND status is 'approved' (awaiting Sales PIC finalization, not auto 'production')
        $this->assertNotNull($contract->sales_approver);
        $this->assertNotNull($contract->quality_approver);
        $this->assertNotNull($contract->ppc_approver);
        $this->assertNotNull($contract->dev_engineering_approver);
        $this->assertEquals('approved', $contract->status);
        echo "[PASS] All 4 divisions approved. Contract status is 'approved' (awaiting Sales PIC handover).\n";

        // 6. Non-PIC Sales Staff ($otherSales) attempts to finalize -> Should be BLOCKED
        $blockedResponse = $this->actingAs($otherSales)->post(route('contracts.finalize', $contract->id));
        $blockedResponse->assertSessionHas('error');
        $contract->refresh();
        $this->assertEquals('approved', $contract->status);
        echo "[PASS] Non-PIC Sales Staff was correctly blocked from finalizing another PIC's contract.\n";

        // 7. Assigned Sales PIC ($assignedSales) finalizes -> Should SUCCEED
        $successResponse = $this->actingAs($assignedSales)->post(route('contracts.finalize', $contract->id));
        $successResponse->assertSessionHas('success');

        $contract->refresh();
        $poInternal->refresh();
        $po->refresh();

        $this->assertEquals('production', $contract->status);
        $this->assertEquals('production', $poInternal->status);
        $this->assertEquals('production', $po->status);
        echo "[PASS] Assigned Sales PIC ({$assignedSales->name}) successfully finalized contract to 'production'.\n";
        echo "[PASS] Contract, PO Internal, and Customer PO status all updated to 'production'.\n";
        echo "=== 4-DIVISION APPROVAL & SALES PIC FINALIZATION FULLY VERIFIED! ===\n";
    }
}

