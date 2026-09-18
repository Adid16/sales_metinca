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
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

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
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

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
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

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
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

        $filters = $request->only(['name', 'email']);
        $filters['role'] = 'customer';
        $filename = 'customers-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new UsersExport($filters), $filename);
    }

    public function storeCustomer(Request $request, $requestProject)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff() && !Auth::user()->isManager()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'nullable',
            'company'  => 'nullable',
            'divisi'   => 'nullable',
            'plant'    => 'nullable',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        RequestProject::where('email', '=', $validated['email'])->update([
            'customer_id' => $user->id
        ]);
        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Membuat user customer ' . $user->name,
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return back()->with('success', 'Berhasil membuat akun customer');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'nullable',
            'company'  => 'nullable',
            'divisi'   => 'nullable',
            'plant'    => 'nullable',
        ]);

        // Business rules: Admin has no department, Staff is always Sales
        if (($validated['role'] ?? '') === 'admin') {
            $validated['divisi'] = null;
        } elseif (($validated['role'] ?? '') === 'staff') {
            $validated['divisi'] = 'sales';
        }

        $validated['password'] = Hash::make($validated['password']);
        $createdUser = User::create($validated);

        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Membuat user ' . $createdUser->name . ' (Role: ' . ($createdUser->role ?? '-') . ')',
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        if (($createdUser->role ?? '') === 'customer') {
            return redirect()->route('users.customer')->with('success', 'Data Customer berhasil ditambahkan');
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role'     => 'nullable',
            'company'  => 'nullable',
            'divisi'   => 'nullable',
            'plant'    => 'nullable',
        ]);

        // Business rules: Admin has no department, Staff is always Sales
        if (($validated['role'] ?? '') === 'admin') {
            $validated['divisi'] = null;
        } elseif (($validated['role'] ?? '') === 'staff') {
            $validated['divisi'] = 'sales';
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);

        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Mengupdate data user ' . $user->name,
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);

        return back()->with('success', 'User Updated');
    }

    public function destroy(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

        $userName = $user->name;
        $user->delete();
        \App\Models\HistoryActivity::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Menghapus user ' . $userName,
            'activity_time' => now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'berhasil hapus user');
    }

    public function edit(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak. User Management hanya dapat diakses oleh Administrator.');
        }

        if ($user->role == 'customer') {
            return view('users.customer-edit-partial', compact('user'));
        } else {
            return view('users.edit',compact('user'));
        }
    }
}
