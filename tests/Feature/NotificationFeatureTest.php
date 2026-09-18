<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contract;
use App\Notifications\ContractApprovedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_notifications_and_mark_all_read(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'divisi' => 'sales',
        ]);

        $quotation = \App\Models\Quotation::create([
            'quotation_no' => 'QT-TEST-001',
            'customer_id' => $user->id,
            'date_expired' => now()->addDays(7),
            'status' => 'draft',
        ]);

        $contract = Contract::create([
            'order_no' => 'CRS-TEST-001',
            'customer_id' => $user->id,
            'quotation_id' => $quotation->id,
            'sales_pic' => $user->id,
            'status' => 'draft',
        ]);

        $user->notify(new ContractApprovedNotification($contract, 'Quality'));
        $user->notify(new ContractApprovedNotification($contract, 'Sales'));

        $this->assertEquals(2, $user->unreadNotifications()->count());

        // View notification page
        $response = $this->actingAs($user)->get(route('notifikasi'));
        $response->assertStatus(200);
        $response->assertSee('CRS-TEST-001');
        $response->assertSee('Quality');

        // Mark single as read
        $firstNotif = $user->unreadNotifications()->first();
        $readResponse = $this->actingAs($user)->get(route('notifikasi.read', $firstNotif->id));
        $readResponse->assertRedirect(route('contracts.show', $contract->id));

        $this->assertEquals(1, $user->fresh()->unreadNotifications()->count());

        // Mark all as read
        $markAllResponse = $this->actingAs($user)->post(route('notifikasi.markAllRead'));
        $markAllResponse->assertRedirect();

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_new_request_project_dispatches_notifications_to_admin_and_sales(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'divisi' => null,
        ]);

        $sales = User::factory()->create([
            'role' => 'staff',
            'divisi' => 'sales',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('spec.pdf', 100, 'application/pdf');

        $response = $this->actingAs($customer)->post(route('requests-project.store'), [
            'email' => $customer->email,
            'subject' => 'Permintaan Mold Komponen Baru',
            'message' => 'Tolong buatkan penawaran secepatnya',
            'attachment' => [$file],
        ]);

        $response->assertRedirect();

        // Admin and Sales should receive the new request notification
        $this->assertEquals(1, $admin->fresh()->unreadNotifications()->count());
        $this->assertEquals(1, $sales->fresh()->unreadNotifications()->count());

        $adminNotif = $admin->fresh()->unreadNotifications()->first();
        $this->assertStringContainsString('Permintaan Mold Komponen Baru', $adminNotif->data['message']);

        // Test Assign Notification to Sales PIC
        $project = \App\Models\RequestProject::first();
        $assignResponse = $this->actingAs($admin)->post(route('requests-project.assign', $project->id), [
            'sales_id' => $sales->id,
        ]);
        $assignResponse->assertRedirect();

        // Sales now has 2 notifications (1 new request, 1 assignment)
        $this->assertEquals(2, $sales->fresh()->unreadNotifications()->count());
        $salesMessages = $sales->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesMessages)->contains(fn($m) => str_contains($m, 'ditugaskan')));
        $this->assertTrue(collect($salesMessages)->contains(fn($m) => str_contains($m, 'Permintaan Mold Komponen Baru')));
    }

    public function test_quotation_and_negotiation_notification_lifecycle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $salesPic = User::factory()->create(['role' => 'staff', 'divisi' => 'sales']);
        $managerSales = User::factory()->create(['role' => 'manager', 'divisi' => 'sales']);
        $customer = User::factory()->create(['role' => 'customer']);

        $requestProject = \App\Models\RequestProject::create([
            'customer_id' => $customer->id,
            'email'       => $customer->email,
            'subject'     => 'Project Bracket CNC',
            'message'     => 'Detail requirement',
            'status'      => 'assigned',
        ]);

        \App\Models\RequestProjectAssignment::create([
            'request_project_id' => $requestProject->id,
            'sales_id'           => $salesPic->id,
            'assigned_by'        => $admin->id,
        ]);

        $quotation = \App\Models\Quotation::create([
            'customer_id'  => $customer->id,
            'request_id'   => $requestProject->id,
            'quotation_no' => 'QT-2026-09-0001',
            'date_expired' => now()->addDays(7),
            'status'       => 'created',
        ]);

        $item = $quotation->items()->create([
            'item'             => 'Bracket Flange CNC',
            'qty'              => 10,
            'price'            => 100000,
            'original_price'   => 100000,
            'floor_price'      => 80000,
        ]);

        // 1. Sales sends Quotation -> Customer receives notification
        $this->actingAs($salesPic)->patch(route('quotations.send', $quotation->id));
        $this->assertEquals(1, $customer->fresh()->unreadNotifications()->count());
        $this->assertStringContainsString('QT-2026-09-0001 telah dikirim oleh tim Sales', $customer->fresh()->unreadNotifications()->first()->data['message']);

        // 2. Customer submits negotiation -> Sales PIC & Manager Sales receive notification
        $this->actingAs($customer)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'negotiate',
            'negotiation_message' => 'Minta diskon Rp 90.000',
            'items'               => [
                ['id' => $item->id, 'negotiated_price' => 90000]
            ]
        ]);

        $salesNotifs = $salesPic->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesNotifs)->contains(fn($m) => str_contains($m, 'Customer mengajukan negosiasi')));

        $mgrNotifs = $managerSales->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($mgrNotifs)->contains(fn($m) => str_contains($m, 'Customer mengajukan negosiasi')));

        // 3. Sales PIC replies with counter-offer -> Customer receives notification
        $this->actingAs($salesPic)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'negotiate',
            'negotiation_message' => 'Penawaran terbaik kami Rp 95.000',
            'items'               => [
                ['id' => $item->id, 'negotiated_price' => 95000]
            ]
        ]);

        $customerNotifs = $customer->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($customerNotifs)->contains(fn($m) => str_contains($m, 'Sales PIC telah membalas negosiasi')));

        // 4. Customer accepts -> Sales PIC receives notification
        $this->actingAs($customer)->post(route('negotiate.store', $quotation->id), [
            'action'              => 'accept',
            'negotiation_message' => 'Sepakat Rp 95.000',
            'items'               => [
                ['id' => $item->id, 'negotiated_price' => 95000]
            ]
        ]);

        $salesNotifsAfterAccept = $salesPic->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesNotifsAfterAccept)->contains(fn($m) => str_contains($m, 'Customer telah menyetujui Quotation')));
    }

    public function test_purchase_order_and_amendment_notification_lifecycle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $salesPic = User::factory()->create(['role' => 'staff', 'divisi' => 'sales']);
        $managerSales = User::factory()->create(['role' => 'manager', 'divisi' => 'sales']);
        $customer = User::factory()->create(['role' => 'customer']);

        $requestProject = \App\Models\RequestProject::create([
            'customer_id' => $customer->id,
            'email'       => $customer->email,
            'subject'     => 'Project Shaft Motor',
            'message'     => 'Tolong diproses',
            'status'      => 'assigned',
        ]);

        \App\Models\RequestProjectAssignment::create([
            'request_project_id' => $requestProject->id,
            'sales_id'           => $salesPic->id,
            'assigned_by'        => $admin->id,
        ]);

        $quotation = \App\Models\Quotation::create([
            'customer_id'  => $customer->id,
            'request_id'   => $requestProject->id,
            'quotation_no' => 'QT-2026-09-0002',
            'date_expired' => now()->addDays(7),
            'status'       => 'accepted',
        ]);

        // 1. Customer creates PO -> Sales PIC & Admin receive notification
        $pdfFile = \Illuminate\Http\UploadedFile::fake()->create('po.pdf', 100, 'application/pdf');
        $this->actingAs($customer)->post(route('purchase-orders.store'), [
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-2026-0001',
            'attachments'      => $pdfFile,
            'delivery_request' => now()->addDays(14)->toDateString(),
        ]);

        $po = \App\Models\PurchaseOrder::where('po_no', 'PO-2026-0001')->first();

        $salesNotifs = $salesPic->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesNotifs)->contains(fn($m) => str_contains($m, 'menerbitkan PO #PO-2026-0001')));

        $adminNotifs = $admin->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($adminNotifs)->contains(fn($m) => str_contains($m, 'menerbitkan PO #PO-2026-0001')));

        // 2. Customer submits PO amendment -> Sales PIC, Manager Sales & Admin receive notification
        $contract = Contract::create([
            'customer_id'   => $customer->id,
            'quotation_id'  => $quotation->id,
            'order_no'      => $po->po_no,
            'contract_no'   => 'CTR-PO-2026-0001',
            'status'        => 'review',
            'amandement_no' => 0,
        ]);

        $amendmentFile = \Illuminate\Http\UploadedFile::fake()->create('amendment.pdf', 100, 'application/pdf');
        $responseAmandement = $this->actingAs($customer)->post(route('purchase-orders.store-amandement', $po->id), [
            'alasan_amandemen' => 'Perubahan kuantiti dari 10 menjadi 15 pcs',
            'attachments'      => $amendmentFile,
        ]);

        $salesNotifsAmandement = $salesPic->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesNotifsAmandement)->contains(fn($m) => str_contains($m, 'Customer mengajukan Amandemen ke-1')));

        $mgrNotifsAmandement = $managerSales->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($mgrNotifsAmandement)->contains(fn($m) => str_contains($m, 'Pengajuan amandemen PO #PO-2026-0001')));

        // 3. Sales PIC approves amendment -> Customer receives notification
        $this->actingAs($salesPic)->post(route('purchase-orders.approve-amandement', $po->id));
        $customerNotifs = $customer->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($customerNotifs)->contains(fn($m) => str_contains($m, 'Pengajuan Amandemen untuk PO #PO-2026-0001 telah disetujui')));

        // 4. Test rejection flow -> Customer receives rejection notification
        $contract->update(['status' => 'amandement_pending']);
        $this->actingAs($salesPic)->post(route('purchase-orders.reject-amandement', $po->id), [
            'alasan_penolakan' => 'Lead time bahan baku tidak mencukupi untuk jadwal baru'
        ]);
        $customerNotifsReject = $customer->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($customerNotifsReject)->contains(fn($m) => str_contains($m, 'ditolak. Alasan: Lead time')));
    }

    public function test_contract_approval_and_finalization_notification_lifecycle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $salesPic = User::factory()->create(['role' => 'staff', 'divisi' => 'sales']);
        $managerQuality = User::factory()->create(['role' => 'manager', 'divisi' => 'quality']);
        $managerPpc = User::factory()->create(['role' => 'manager', 'divisi' => 'ppc']);
        $managerDe = User::factory()->create(['role' => 'manager', 'divisi' => 'design engineering']);
        $managerSales = User::factory()->create(['role' => 'manager', 'divisi' => 'sales']);
        $customer = User::factory()->create(['role' => 'customer']);

        $requestProject = \App\Models\RequestProject::create([
            'customer_id' => $customer->id,
            'email'       => $customer->email,
            'subject'     => 'Project Die Casting',
            'message'     => 'Paling lambat 2 minggu',
            'status'      => 'assigned',
        ]);

        \App\Models\RequestProjectAssignment::create([
            'request_project_id' => $requestProject->id,
            'sales_id'           => $salesPic->id,
            'assigned_by'        => $admin->id,
        ]);

        $quotation = \App\Models\Quotation::create([
            'customer_id'  => $customer->id,
            'request_id'   => $requestProject->id,
            'quotation_no' => 'QT-2026-09-0003',
            'date_expired' => now()->addDays(7),
            'status'       => 'po',
        ]);

        $po = \App\Models\PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $quotation->id,
            'po_no'            => 'PO-2026-0003',
            'delivery_request' => now()->addDays(14)->toDateString(),
            'status'           => 'review',
        ]);

        $contract = Contract::create([
            'customer_id'   => $customer->id,
            'quotation_id'  => $quotation->id,
            'order_no'      => $po->po_no,
            'contract_no'   => 'CTR-PO-2026-0003',
            'status'        => 'review',
            'amandement_no' => 0,
        ]);

        // 1. Manager Quality rejects -> Sales PIC receives rejection notification with reason
        $this->actingAs($managerQuality)->post(route('contracts.reject-manager', $contract->id), [
            'comment' => 'Perlu sertifikasi material EN 10204 3.1'
        ]);

        $salesNotifs = $salesPic->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesNotifs)->contains(fn($m) => str_contains($m, 'Manager Quality menolak Contract')));

        // 2. Sales updates contract revision -> Managers receive notification
        $this->actingAs($salesPic)->put(route('contracts.update', $contract->id), [
            'customer_id'  => $customer->id,
            'quotation_id' => $quotation->id,
            'order_no'     => $po->po_no,
            'part_name'    => 'Die Casting Part Rev 1',
        ]);

        $qualityNotifs = $managerQuality->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($qualityNotifs)->contains(fn($m) => str_contains($m, 'telah diperbaiki oleh Sales PIC')));

        // 3. Super-Admin Approves all 4 divisions -> Sales PIC receives 4-division full approval notification
        $this->actingAs($admin)->post(route('contracts.approve-manager', $contract->id), [
            'approve_all' => '1',
            'target_divisi' => 'all',
        ]);

        $salesNotifsAfterAllApprove = $salesPic->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($salesNotifsAfterAllApprove)->contains(fn($m) => str_contains($m, 'disetujui penuh oleh 4 divisi')));

        // 4. Sales PIC Finalizes to Production -> Customer & Admin receive In Production notification
        $this->actingAs($salesPic)->post(route('contracts.finalize', $contract->id));

        $customerFinalNotifs = $customer->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($customerFinalNotifs)->contains(fn($m) => str_contains($m, 'resmi masuk proses produksi')));

        $adminFinalNotifs = $admin->fresh()->unreadNotifications->pluck('data.message')->toArray();
        $this->assertTrue(collect($adminFinalNotifs)->contains(fn($m) => str_contains($m, 'In Production')));
    }
}

