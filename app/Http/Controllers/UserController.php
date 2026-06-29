<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\RequestProject;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            //code...
            $query = User::query();
            if ($request->filled('role')) {
                $query->where('role', '=', $request->role);
            }
            $users = $query->where('role', '!=', 'customer')->get();

            // Keep current filters for view buttons (export)
            $filters = $request->only(['role', 'name', 'email']);

            return view('users.index', compact('users', 'filters'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function index_customer(Request $request)
    {
        $query = User::where('role', '=', 'customer');

        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        $users = $query->get();

        $filters = $request->only(['name', 'email']);

        return view('users.index_customer', compact('users', 'filters'));
    }

    /**
     * Export non-customer users (admin/managers/staff)
     */
    public function export(Request $request)
    {
        $filters = $request->only(['role', 'name', 'email']);
        $filters['include_customers'] = false;
        $filename = 'users-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new UsersExport($filters), $filename);
    }

    /**
     * Export customers
     */
    public function exportCustomers(Request $request)
    {
        $filters = $request->only(['name', 'email']);
        $filters['role'] = 'customer';
        $filename = 'customers-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new UsersExport($filters), $filename);
    }

    public function storeCustomer(Request $request, $requestProject)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'nullable',
            'company' => 'nullable',
            'divisi' => 'nullable',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        RequestProject::where('email', '=', $validated['email'])->update([
            'customer_id' => $user->id
        ]);
        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Membuat user',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return back()->with('success', 'Berhasil membuat akun customer');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'nullable',
            'company' => 'nullable',
            'divisi' => 'nullable'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Membuat user',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role'     => 'nullable',
            'company' => 'nullable',
            'divisi' => 'nullable'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);

        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Mengupdate user',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return back()->with('success', 'User Updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Menghapus quotation',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'berhasil hapus user');
    }

    public function edit(User $user)
    {
        if ($user->role == 'customer') {
            return view('users.customer-edit-partial', compact('user'));
        } else {
            return view('users.edit',compact('user'));
        }
    }
}
