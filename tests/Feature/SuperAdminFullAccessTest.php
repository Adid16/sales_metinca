<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Contract;
use App\Models\Negotiate;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\RequestProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuperAdminFullAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_has_complete_unrestricted_access_across_all_modules()
    {
        Storage::fake('public');

        echo "\n=== STARTING SUPER-ADMIN COMPLETE ACCESS TEST ===\n";

        // 1. SETUP USERS
        $admin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'admin@metinca.com',
            'role' => 'admin',
            'divisi' => null,
        ]);

        $salesPic = User::factory()->create([
            'name' => 'Staff Sales Budi',
            'email' => 'budi@metinca.com',
            'role' => 'staff',
            'divisi' => 'sales',
        ]);

        $customer = User::factory()->create([
            'name' => 'PT Astra Honda Motor',
            'email' => 'purchasing@astra.com',
            'role' => 'customer',
            'company' => 'PT Astra Honda Motor',
        ]);

        $managerSales = User::factory()->create([
            'name' => 'Manager Sales Anton',
            'email' => 'anton@metinca.com',
            'role' => 'manager',
            'divisi' => 'sales',
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($admin->hasPermission('any_custom_permission'));

        // Non-admin cannot access user management
        $this->actingAs($managerSales)->get(route('users.index'))->assertStatus(403);
        $this->actingAs($managerSales)->get(route('users.customer'))->assertStatus(403);
        $this->actingAs($salesPic)->get(route('users.index'))->assertStatus(403);
        $this->actingAs($customer)->get(route('users.index'))->assertStatus(403);

        $response = $this->actingAs($admin)->get(route('users.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('users.customer'));
        $response->assertStatus(200);

        $createResp = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Staff Bekasi Baru',
            'email' => 'bekasi.staff@metinca.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'staff',
            'divisi' => 'ppc',
            'plant' => 'Bekasi',
        ]);
        $createResp->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'bekasi.staff@metinca.com',
            'plant' => 'Bekasi',
        ]);

        $newUser = User::where('email', 'bekasi.staff@metinca.com')->first();
        $updateResp = $this->actingAs($admin)->put(route('users.update', $newUser->id), [
            'name' => 'Staff Salatiga Updated',
            'email' => 'salatiga.staff@metinca.com',
            'role' => 'manager',
            'divisi' => 'quality',
            'plant' => 'Salatiga',
        ]);
        $updateResp->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $newUser->id,
            'email' => 'salatiga.staff@metinca.com',
            'plant' => 'Salatiga',
            'role' => 'manager',
        ]);

        $deleteUserResponse = $this->actingAs($admin)->delete(route('users.destroy', $newUser->id));
        $deleteUserResponse->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
        echo "[PASS] Super-Admin full access to User Management (Employee & Customer with Branch / Plant).\n";

        // ====================================================================
        // B. ARTICLE / PRICELIST MANAGEMENT ACCESS
        // ====================================================================
        $article = Article::create([
            'customer_id' => $customer->id,
            'article_no' => 'ART-ADMIN-001',
            'internal_part_no' => 'PRT-ADM-001',
            'part_name' => 'Bracket Support Admin',
            'casting_price' => 50000,
            'machining_price' => 35000,
            'price' => 85000,
            'effective_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($admin)->get(route('articles.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->put(route('articles.update', $article->id), [
            'customer_id' => $customer->id,
            'part_number' => 'PRT-ADM-001',
            'article_no' => 'ART-ADMIN-001',
            'part_name' => 'Bracket Support Admin Updated',
            'casting_price' => 60000,
            'machining_price' => 40000,
            'effective_date' => now()->format('Y-m-d'),
        ]);
        $response->assertRedirect(route('articles.index'));
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'price' => 100000]);
        echo "[PASS] Super-Admin full access to Pricelist & Floor Price Management.\n";

        // ====================================================================
        // C. REQUEST PROJECT: ASSIGN & REASSIGN ACCESS
        // ====================================================================
        $requestProj = RequestProject::create([
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'subject' => 'Permintaan Fabrikasi Braket Kopling',
            'message' => 'Tolong segera dibuatkan penawaran harga.',
        ]);

        // Admin assigns to salesPic
        $response = $this->actingAs($admin)->post(route('requests-project.assign', $requestProj->id), [
            'sales_id' => $salesPic->id,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('request_project_assignments', [
            'request_project_id' => $requestProj->id,
            'sales_id' => $salesPic->id,
        ]);

        // Admin can reassign to another user or self
        $response = $this->actingAs($admin)->post(route('requests-project.assign', $requestProj->id), [
            'sales_id' => $admin->id,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('request_project_assignments', [
            'request_project_id' => $requestProj->id,
            'sales_id' => $admin->id,
        ]);
        echo "[PASS] Super-Admin full access to Assign and Re-assign Request Projects.\n";

        // ====================================================================
        // D. QUOTATION & OVERRIDE NEGO LIMIT ACCESS
        // ====================================================================
        $quotation = Quotation::create([
            'quotation_no' => 'QT-2026-09-0001',
            'customer_id' => $customer->id,
            'request_id' => $requestProj->id,
            'status' => 'sent',
            'date_expired' => now()->addDays(30),
            'total_price' => 100000,
        ]);

        $qItem = QuotationItem::create([
            'quotation_id' => $quotation->id,
            'item' => 'Bracket Support Admin Updated',
            'qty' => 100,
            'price' => 100000,
            'article_id' => $article->id,
        ]);

        // Super-Admin adds negotiation override quota (+3 rounds)
        $overrideResp = $this->actingAs($admin)->post(route('quotations.override-nego-limit', $quotation->id), [
            'additional_quota' => 3,
        ]);
        $overrideResp->assertRedirect();
        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'negotiation_override_quota' => 3,
        ]);
        echo "[PASS] Super-Admin successfully overridden negotiation quota (+3 rounds).\n";

        // ====================================================================
        // E. PURCHASE ORDER & CONTRACT REVIEW ACCESS
        // ====================================================================
        $po = PurchaseOrder::create([
            'po_no' => 'PO-ADM-2026-001',
            'customer_id' => $customer->id,
            'quotation_id' => $quotation->id,
            'status' => 'sent',
            'delivery_request' => now()->addDays(20),
        ]);

        $poInternal = PurchaseOrderInternal::create([
            'purchase_order_id' => $po->id,
            'po_no' => 'PO-ADM-2026-001-1',
            'item' => 'Bracket Support Admin Updated',
            'qty' => 100,
            'unit_price' => 100000,
            'subtotal' => 10000000,
            'status' => 'created',
        ]);

        $contract = Contract::create([
            'contract_no' => 'CTR-ADM-2026-001-1',
            'order_no' => 'PO-ADM-2026-001-1',
            'customer_id' => $customer->id,
            'quotation_id' => $quotation->id,
            'purchase_order_internal_id' => $poInternal->id,
            'part_name' => 'Bracket Support Admin Updated',
            'status' => 'review',
        ]);

        // Super-Admin: Instant Approve All 4 Divisions
        $approveAllResp = $this->actingAs($admin)->patch(route('contracts.approve-manager', $contract->id), [
            'target_divisi' => 'all',
        ]);
        $approveAllResp->assertRedirect();

        $contract->refresh();
        $this->assertNotNull($contract->sales_approver);
        $this->assertNotNull($contract->quality_approver);
        $this->assertNotNull($contract->ppc_approver);
        $this->assertNotNull($contract->dev_engineering_approver);
        $this->assertEquals('approved', $contract->status);
        echo "[PASS] Super-Admin Instant Approve All 4 Divisions succeeded.\n";

        // Super-Admin: Finalize to In Production
        $finalizeResp = $this->actingAs($admin)->post(route('contracts.finalize', $contract->id));
        $finalizeResp->assertRedirect();

        $contract->refresh();
        $poInternal->refresh();
        $po->refresh();
        $this->assertEquals('production', $contract->status);
        $this->assertEquals('production', $poInternal->status);
        $this->assertEquals('production', $po->status);
        echo "[PASS] Super-Admin successfully finalized contract & PO to 'In Production'.\n";

        // ====================================================================
        // F. PO AMANDEMEN APPROVAL ACCESS
        // ====================================================================
        // Reset to allow amendment test
        $contract->update(['status' => 'amandement_pending', 'amandement_no' => 1, 'alasan_amandemen' => 'Perubahan kuantitas']);
        $po->update(['status' => 'amandement_pending']);

        // Admin approves amendment
        $approveAmendResp = $this->actingAs($admin)->post(route('purchase-orders.approve-amandement', $po->id), [
            'contract_id' => $contract->id,
            'new_qty' => 150,
            'new_unit_price' => 100000,
        ]);
        $approveAmendResp->assertRedirect();

        $contract->refresh();
        $poInternal->refresh();
        $this->assertEquals('amandement', $contract->status);
        $this->assertEquals(150, $poInternal->qty);
        $this->assertEquals(15000000, $poInternal->subtotal);
        echo "[PASS] Super-Admin successfully approved PO Amandemen with sync to PO Internal.\n";

        echo "=== ALL SUPER-ADMIN FULL ACCESS TESTS PASSED 100%! ===\n\n";
    }
}
