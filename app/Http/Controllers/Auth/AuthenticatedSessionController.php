<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle login request (AJAX)
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $redirectUrl = match($user->role) {
        'admin' => route('dashboard'),
        'manager' => route('dashboard'),
        'staff' => route('dashboard'),
        'customer' => route('customer_home.main'), // sesuaikan route customer
        default => route('dashboard'),
        };
        // Karena login pakai AJAX
        return response()->json([
            'message' => 'Login Success'
        ]);
    }

    /**
     * Handle logout (FORM SUBMIT OR GET)
     */
    public function destroy(Request $request)
    {
        $userRole = Auth::user()?->role;
        $redirectRoute = match($userRole){
            'admin' => 'login',
            'manager' => 'login',
            'staff' => 'login',
            'customer' => 'customer_home.login',
            default => 'login',
        };

        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // INI KUNCI → redirect ke login
        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Logout berhasil');
    }
}