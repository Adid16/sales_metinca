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

        if ($request->filled('company')) {
            Auth::user()->update(['company' => $request->company]);
        }

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

    // Update account & password
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone'                     => 'nullable|string|max:20',
            'company'                   => 'nullable|string|max:255',
            'position'                  => 'nullable|string|max:255',
            'address'                   => 'nullable|string',
            'city'                      => 'nullable|string|max:100',
            'zip'                       => 'nullable|string|max:10',
            'fax'                       => 'nullable|string|max:20',
            'current_password'          => 'nullable|string',
            'new_password'              => 'nullable|string|min:6|confirmed',
        ]);

        // Jika user mengisi password baru, validasi password saat ini
        if ($request->filled('new_password')) {
            if (empty($request->current_password)) {
                return back()->withErrors(['current_password' => 'Password saat ini wajib diisi jika ingin mengubah password.'])->withInput();
            }

            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }

            $user->update([
                'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
            ]);

            \App\Models\HistoryActivity::create([
                'user_id' => $user->id,
                'activity' => 'Mengubah password akun pribadi',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);
        }

        Account::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['phone', 'company', 'position', 'address', 'city', 'zip', 'fax'])
        );

        if ($request->filled('company')) {
            $user->update(['company' => $request->company]);
        }

        return redirect()->route('account.show')
            ->with('success', 'Data akun dan password berhasil diperbarui.');
    }
}