<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    // Tampilkan detail account
    public function show()
    {
        $account = Account::where('user_id', Auth::id())->first();
        return view('account.show', compact('account'));
    }

    // Form isi account pertama kali
    public function create()
    {
        // Kalau sudah punya account, redirect ke show
        $existing = Account::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('account.show');
        }
        return view('account.create');
    }

    // Simpan account baru
    public function store(Request $request)
    {
        $request->validate([
            'phone'    => 'nullable|string|max:20',
            'company'  => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'zip'      => 'nullable|string|max:10',
            'fax'      => 'nullable|string|max:20',
        ]);

        Account::create([
            'user_id'  => Auth::id(),
            'phone'    => $request->phone,
            'company'  => $request->company,
            'position' => $request->position,
            'address'  => $request->address,
            'city'     => $request->city,
            'zip'      => $request->zip,
            'fax'      => $request->fax,
        ]);

        return redirect()->route('account.show')
            ->with('success', 'Account berhasil disimpan.');
    }

    // Form edit account
    public function edit()
    {
         $user    = Auth::user();
        $account = Account::firstOrCreate(['user_id' => Auth::id()]);
        return view('account.edit', compact('user','account'));
    }

    // Update account
    public function update(Request $request)
    {
        $request->validate([
            'phone'    => 'nullable|string|max:20',
            'company'  => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'zip'      => 'nullable|string|max:10',
            'fax'      => 'nullable|string|max:20',
        ]);

        Account::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['phone', 'company', 'position', 'address', 'city', 'zip', 'fax'])
        );

        return redirect()->route('account.show')
            ->with('success', 'Account berhasil diupdate.');
    }
}