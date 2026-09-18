<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_update_profile_and_change_own_password()
    {
        echo "\n=== STARTING CUSTOMER PASSWORD CHANGE TEST ===\n";

        // 1. Setup Customer
        $customer = User::factory()->create([
            'name' => 'PT Astra Honda Motor (PIC Sabila)',
            'email' => 'purchasing@astrahonda.com',
            'password' => Hash::make('oldpassword123'),
            'role' => 'customer',
            'company' => 'PT Astra Honda Motor',
        ]);

        Account::create([
            'user_id' => $customer->id,
            'company' => 'PT Astra Honda Motor',
            'phone' => '08123456789',
        ]);

        // 2. View Account Show & Edit
        $showResp = $this->actingAs($customer)->get(route('account.show'));
        $showResp->assertStatus(200);
        $showResp->assertSee('My Account & Profil Pengguna', false);
        $showResp->assertSee('Ubah Password');

        $editResp = $this->actingAs($customer)->get(route('account.edit'));
        $editResp->assertStatus(200);
        $editResp->assertSee('Keamanan & Ubah Password', false);

        // 3. Update profile only (without changing password)
        $updateProfileResp = $this->actingAs($customer)->put(route('account.update'), [
            'position' => 'Senior Purchasing Officer',
            'city' => 'Jakarta Utara',
            'phone' => '081999888777',
        ]);
        $updateProfileResp->assertRedirect(route('account.show'));
        $this->assertDatabaseHas('account_table', [
            'user_id' => $customer->id,
            'position' => 'Senior Purchasing Officer',
            'city' => 'Jakarta Utara',
        ]);
        // Password remains unchanged
        $this->assertTrue(Hash::check('oldpassword123', $customer->fresh()->password));
        echo "[PASS] Customer successfully updated profile without changing password.\n";

        // 4. Attempt to change password with WRONG current password
        $wrongPassResp = $this->actingAs($customer)->put(route('account.update'), [
            'current_password' => 'wrongcurrentpassword',
            'new_password' => 'newsecret2026',
            'new_password_confirmation' => 'newsecret2026',
        ]);
        $wrongPassResp->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('oldpassword123', $customer->fresh()->password));
        echo "[PASS] System correctly BLOCKED password change with incorrect current password.\n";

        // 5. Attempt to change password with mismatch confirmation
        $mismatchResp = $this->actingAs($customer)->put(route('account.update'), [
            'current_password' => 'oldpassword123',
            'new_password' => 'newsecret2026',
            'new_password_confirmation' => 'differentsecret2026',
        ]);
        $mismatchResp->assertSessionHasErrors('new_password');
        $this->assertTrue(Hash::check('oldpassword123', $customer->fresh()->password));
        echo "[PASS] System correctly BLOCKED password change with mismatched confirmation.\n";

        // 6. Successfully change password with valid credentials
        $successResp = $this->actingAs($customer)->put(route('account.update'), [
            'current_password' => 'oldpassword123',
            'new_password' => 'newsecret2026',
            'new_password_confirmation' => 'newsecret2026',
        ]);
        $successResp->assertRedirect(route('account.show'));
        $successResp->assertSessionHas('success');

        // Verify password in database is updated
        $this->assertTrue(Hash::check('newsecret2026', $customer->fresh()->password));
        $this->assertFalse(Hash::check('oldpassword123', $customer->fresh()->password));
        echo "[PASS] Customer password successfully changed and hashed with Bcrypt.\n";

        // 7. Verify new password can be used for login
        Auth::logout();
        $this->assertGuest();

        $loginResp = $this->post(route('login.store'), [
            'email' => 'purchasing@astrahonda.com',
            'password' => 'newsecret2026',
        ]);
        $loginResp->assertStatus(200);
        $loginResp->assertJson(['message' => 'Login Success']);
        $this->assertAuthenticatedAs($customer);
        echo "[PASS] Customer successfully authenticated with new password!\n";

        echo "=== ALL CUSTOMER PASSWORD CHANGE TESTS PASSED 100%! ===\n";
    }
}
