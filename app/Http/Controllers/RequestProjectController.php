<?php

namespace App\Http\Controllers;

use App\Models\RequestProject;
use App\Models\HistoryActivity;
use App\Models\User;
use App\Notifications\NewRequestProjectNotification;
use App\Notifications\RequestProjectAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $filters = $request->only(['start_date','end_date', 'sales_id']);

    // 1. Tambahkan langsung di sini agar berlaku global (Sales & Customer tidak akan melihat data yang sudah ada quotation)
    $query = RequestProject::with(['assignment.sales'])->whereDoesntHave('quotation');

    // 2. Blok ini sekarang murni untuk mengunci ID customer saja
    if (Auth::user()->isCustomer()) {
        $query->where('customer_id', '=', Auth::id());
    }

        // apply filters
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['sales_id'])) {
            $query->whereHas('assignment', function ($q) use ($filters) {
                $q->where('sales_id', $filters['sales_id']);
            });
        }

        $projects = $query->orderByRaw("
            CASE 
                WHEN NOT EXISTS (SELECT 1 FROM request_project_assignments WHERE request_project_assignments.request_project_id = request_projects.id) THEN 1
                ELSE 2
            END ASC, created_at DESC
        ")->get();

        $sales = \App\Models\User::where('role','staff')->where('divisi','sales')->get();

        return view('requests-project.index', compact('projects','filters','sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak perlu kirim list sales ke view
        return view('requests-project.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'  => 'nullable',
            'name'         => 'nullable',
            'email'        => 'required|email',
            'subject'      => 'required',
            'message'      => 'nullable',
            'phone'        => 'nullable',
            'company'      => 'nullable',
            'attachment'   => 'required|array|min:1',
            'attachment.*' => 'file|mimes:pdf|max:5048',
        ]);

        // ====================================================================
        // MODUL 2: Auto-fill profil customer dari data user yang login / terdaftar
        // ====================================================================
        if (Auth::check()) {
            $user = Auth::user();
            $account = $user->account;
            $company = !empty($user->company) ? $user->company : ($account->company ?? null);
            $phone = !empty($user->phone) ? $user->phone : ($account->phone ?? null);

            $validated['customer_id'] = $user->id;
            $validated['name']    = $user->name;
            $validated['email']   = $user->email;
            $validated['company'] = $company ?: ($request->input('company') ?: null);
            $validated['phone']   = $phone ?: ($request->input('phone') ?: null);
        } else {
            $existingUser = User::where('email', $validated['email'])->first();
            if ($existingUser) {
                $validated['customer_id'] = $existingUser->id;
                if (empty($validated['company'])) {
                    $validated['company'] = $existingUser->company ?: ($existingUser->account->company ?? null);
                }
                if (empty($validated['phone'])) {
                    $validated['phone'] = $existingUser->phone ?: ($existingUser->account->phone ?? null);
                }
            }
        }

        unset($validated['attachment']);
        $project = RequestProject::create($validated);

        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                $path = $file->storeAs('project-requests/attachment', $fileName, 'public');
                $project->attachments()->create([
                    'document_name' => $originalName,
                    'file_path'     => $path,
                ]);
            }
        }

        HistoryActivity::create([
            'user_id'       => Auth::id() ?? ($project->customer_id ?? null),
            'activity'      => 'Membuat Request Project #' . $project->id . ' (' . ($project->subject ?? 'Permintaan Baru') . ')',
            'activity_time' => now()->format('Y-m-d H:i:s'),
        ]);

        // Kirim Notifikasi Request Baru ke Admin dan Tim Sales (Staff Sales & Manager Sales)
        $notifiableUsers = User::where('role', 'admin')
            ->orWhere(function ($q) {
                $q->whereIn('role', ['staff', 'manager'])->where('divisi', 'sales');
            })
            ->get();

        foreach ($notifiableUsers as $recipient) {
            if (Auth::check() && Auth::id() === $recipient->id) {
                continue;
            }
            $recipient->notify(new NewRequestProjectNotification($project));
        }

        return redirect()->back()->with('success', 'Project request created successfully.');
    }
    public function getByCustomer($id)
    {
        $request = RequestProject::with(['attachments','customer'])->where('customer_id','=',$id)->get();
        return response()->json([
            'success' => true,
            'data' => $request
        ],200);
    }

    /**
     * Export request projects to Excel
     */
    public function export(\Illuminate\Http\Request $request)
    {
        $filters = $request->only(['start_date','end_date','sales_id']);
        $filename = 'request-projects-' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RequestProjectExport($filters), $filename);
    }

    public function detail($id)
    {
        $request = RequestProject::with(['attachments','customer'])->find($id);
        return response()->json([
            'success' => true,
            'data' => $request
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestProject $requests_project)
    {
        $requestProject = $requests_project->load(['attachments', 'customer', 'assignment.sales']);
        $sales = User::where('role', 'staff')->where('divisi', 'sales')->get();
        return view('requests-project.show', compact('requestProject', 'sales'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $project = RequestProject::findOrFail($id);
        return view('requests-project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestProject $requests_project)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'nullable',
            'phone' => 'nullable',
            'company' => 'nullable',
            'attachment'   => 'required|array|min:1', // Harus berupa array & minimal 1 file
            'attachment.*' => 'file|mimes:pdf|max:2048', // Tiap file harus PDF & max 2MB
        ]);
        $requests_project->update($validated);

        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                // Ambil nama asli file: "laporan-keuangan.pdf"
                $originalName = $file->getClientOriginalName();

                // Tambahkan timestamp di depan nama file untuk mencegah duplikasi jika ada nama yang sama
                $fileName = time() . '_' . $originalName;

                // Simpan dengan nama asli ke folder tujuan
                $path = $file->storeAs('project-requests/attachment', $fileName, 'public');

                // Simpan $path ke database
                $requests_project->attachments()->update(['document_name'=> $originalName,'file_path' => $path]);
            }
        }

        return redirect()->route('requests-project.index')->with('success', 'Project request updated successfully.');
    }

    public function assign(Request $request, $id)
    {
        $project = RequestProject::findOrFail($id);
        $user = Auth::user();

        // Admin can re-assign anytime, Staff can only assign if unassigned
        if ($project->assignment && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Project sudah diambil sales lain.');
        }

        $salesId = $request->input('sales_id', $user->id);

        if ($project->assignment) {
            $project->assignment->update([
                'sales_id' => $salesId,
            ]);
        } else {
            $project->assignment()->create([
                'sales_id' => $salesId,
            ]);
        }

        $assignedUser = User::find($salesId);
        $assignedName = $assignedUser ? $assignedUser->name : $user->name;

        HistoryActivity::create([
            'user_id'       => $user->id,
            'activity'      => ($user->isAdmin() ? 'Super-Admin menugaskan ' : 'Sales PIC (') . $assignedName . ') tiket Request Project #' . $project->id . ' (' . ($project->subject ?? '-') . ')',
            'activity_time' => now()->format('Y-m-d H:i:s'),
        ]);

        // Kirim notifikasi ke Sales PIC yang ditugaskan jika bukan dirinya sendiri yang menugaskan
        if ($assignedUser && (!Auth::check() || Auth::id() !== $assignedUser->id)) {
            $assignedUser->notify(new RequestProjectAssignedNotification($project));
        }

        return redirect()->back()->with('success', 'Project berhasil ditugaskan / diterima.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     * MODUL 2: Customer tidak boleh menghapus request yang sudah disubmit.
     */
    public function destroy(RequestProject $requests_project)
    {
        // Block akses delete untuk Customer
        if (Auth::user()->isCustomer()) {
            abort(403, 'Customer tidak diperkenankan menghapus tiket request yang sudah disubmit.');
        }

        foreach ($requests_project->attachments as $attachment) {
            if(Storage::disk('public')->exists($attachment->file_path))
            {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        $requests_project->delete();
        return redirect()->route('requests-project.index')->with('success', 'Project request deleted successfully.');
    }
}
