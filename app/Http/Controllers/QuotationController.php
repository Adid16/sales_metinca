<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Quotation;
use App\Models\RequestProject;
use App\Models\User;
use App\Notifications\QuotationSendNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    //
    public function index(\Illuminate\Http\Request $request)
    {
        $filters = $request->only(['start_date','end_date','status']);

        $query = Quotation::with(['customer', 'request.assignment.sales']);

        // Filter untuk customer
        if (Auth::user()->isCustomer()) {
            $query->where('customer_id', '=', Auth::id())
                ->where('date_expired', '>=', now())
                ->whereDoesntHave('purchaseOrder')
                ->whereIn('status', ['sent', 'accepted', 'negotiating']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $quotations = $query->latest()->get();

        return view('quotations.index', compact('quotations', 'filters'));
    }
    
    public function create(\Illuminate\Http\Request $request)
    {
        $quotationNo = $this->generateQuotationNo();
        
        $requestProject = null;
        $customerAccount = null;
        if($request->has('request_id')){
            $requestProject = \App\Models\RequestProject::with(['attachments', 'customer', 'assignment.sales'])
                ->find($request->request_id);

            //tarik data account customer dari account_table
            if($requestProject && $requestProject->customer_id){
                $customerAccount = \App\Models\Account::where('user_id', $requestProject->customer_id)->first();
            }
        }

        $customers = \App\Models\User::with(['requestProjects.attachments'])
            ->whereHas('requestProjects')
            ->where('role','=','customer')
            ->get();
        return view('quotations.create',compact('customers', 'requestProject', 'quotationNo', 'customerAccount'));

        
    }

    public function store(Request $request)
    {
        
        //1. validasi input
        try {
           
            //2. Proses upload file
            if ($request->hasFile('attachments')){
                $file = $request->file('attachments');

                //membuat nama file unik
                $filename = time() . '_' . $file->getClientOriginalName();

                //simpan file ke folder:storage/app/public/uploads
                $path=$file->storeAs('uploads', $filename, 'public');
            } else {
                $filename = null;
            }

            $requestProject = \App\Models\RequestProject::find($request->request_id);
            if ($requestProject && !$requestProject->customer_id) {
                $customer = \App\Models\User::where('email', $requestProject->email)->first();
                $customerId = $customer?->id;
            } else {
                $customerId = $requestProject?->customer_id;
            }

            $requestProject = \App\Models\RequestProject::find($request->request_id);

            // Coba semua cara untuk dapat customer_id
            $customerId = $requestProject?->customer_id;

            if (!$customerId && $requestProject?->email) {
                $customer = \App\Models\User::where('email', $requestProject->email)->first();
                $customerId = $customer?->id;
            }

            // Tambahkan dd untuk debug
            // dd([
            //     'request_id'     => $request->request_id,
            //     'requestProject' => $requestProject,
            //     'customer_id'    => $customerId,
            // ]);

            //3. simpan ke database
            $quotation = Quotation::create([
                'customer_id'               => $request->customer_id,
                'request_id'                => $request->request_id,
                'quotation_no'              => $request->quotation_no,
                'date_expired'              => $request->date_expired,
                'notes'                     => $request->notes,
                'attachments'               => json_encode($filename),
            ]);

            //simpan items quotation
            if($request->has('item') && is_array($request->item))
                {
                    foreach($request->item as $index => $itemName)
                        {
                            if(!empty($itemName))
                                {
                                    $quotation->items()->create([
                                        'item'  => $itemName,
                                        'qty'   => $request->qty[$index] ?? 0,
                                        'price' => $request->price[$index] ?? 0,
                                    ]);
                                }
                        }
                }

            \App\Models\HistoryActivity::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Membuat quotation',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);

            //5. kirim pesan notifikasi
            return redirect()->route('quotations.index')->with('success', 'data berhasil disimpan');
        } catch (Exception $e) {
            // dd($e->getMessage());
            Log::error('error : ' . $e->getMessage());
            return redirect()->back()->with('error','eror: ' . $e->getMessage());
            //throw $th;
        }
    }

    public function send(Request $request, $id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            //code...
            $quotation->update([
                'status' => 'sent',
                'sent_date' => now()
            ]);

            $quotation->customer->notify(new QuotationSendNotification($quotation));
            \App\Models\HistoryActivity::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Mengirim quotation',
                'activity_time' => now()->format('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('success','Quotation sent');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error',$th);
        }

    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'items', 'request.attachments', 'negotiates' => function($q){
            $q->with('user')->orderBy('created_at', 'desc');
        }]);
        $customerAccount = \App\Models\Account::where('user_id', $quotation->customer_id)->first();
        $negotiations = $quotation->negotiates;
        return view('quotations.show', compact('quotation', 'customerAccount', 'negotiations'));
    }

    /**
     * Export quotations to Excel
     */
    public function export(\Illuminate\Http\Request $request)
    {
        $filters = $request->only(['start_date','end_date','status']);
        $filename = 'quotations-' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\QuotationExport($filters), $filename);
    }

    public function edit(Quotation $quotation)
    {
        // $quotation = Quotation::FindOrFail($id);
        // $isReadOnly = false;
        return view('quotations.edit', compact('quotation'));
    }

   public function update(Request $request, Quotation $quotation)
{
    // 1. Ambil semua input KECUALI token, method, attachment, dan data array item
    $data = $request->except(['_token', '_method', 'attachments', 'item_ids', 'item', 'qty', 'price']);
    
    // Proses upload berkas lampiran bawaan lo
    if ($request->hasFile('attachments')) {
        $file = $request->file('attachments');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/quotation'), $filename);
        $data['attachments'] = $filename;
    }

    // 2. PROSES UTAMA: UPDATE / INSERT / DELETE BARIS ITEM BARANG & HARGA
    if ($request->has('item') && is_array($request->item)) {
        // Ambil semua ID item lama yang ada di database saat ini
        $existingItemIds = $quotation->items->pluck('id')->toArray();
        $keptItemIds = [];

        foreach ($request->item as $index => $itemName) {
            if (!empty($itemName)) {
                $itemId = $request->item_ids[$index] ?? null;
                
                // Struktur data disamakan dengan fungsi store lo ('item', 'qty', 'price')
                $itemData = [
                    'item'  => $itemName,
                    'qty'   => $request->qty[$index] ?? 0,
                    'price' => $request->price[$index] ?? 0, 
                ];

                if (!empty($itemId)) {
                    // Jika ID item sudah ada, update baris data item tersebut
                    $quotation->items()->where('id', $itemId)->update($itemData);
                    $keptItemIds[] = $itemId;
                } else {
                    // Jika ID kosong (item baru hasil klik tombol +), buat baru
                    $newItem = $quotation->items()->create($itemData);
                    $keptItemIds[] = $newItem->id;
                }
            }
        }

        // Hapus item dari database jika di form edit diklik tombol trash (hapus baris)
        $itemsToDelete = array_diff($existingItemIds, $keptItemIds);
        if (!empty($itemsToDelete)) {
            $quotation->items()->whereIn('id', $itemsToDelete)->delete();
        }
    }

    // 3. Catat Riwayat Aktivitas bawaan lo
    \App\Models\HistoryActivity::create([
        'user_id' => Auth::user()->id,
        'activity' => 'Mengupdate quotation',
        'activity_time' => now()->format('Y-m-d H:i:s')
    ]);

    // 4. Update data header dokumen (date_expired & notes)
    $quotation->update($data);

    return redirect()
        ->route('quotations.index')
        ->with('success', 'Quotation updated successfully');
}

    public function exportPdf(Quotation $quotation)
    {
        $quotation->load(['customer', 'items', 'request.attachments']);
        $customerAccount = \App\Models\Account::where('user_id', $quotation->customer_id)->first();
        $pdf = Pdf::loadView('quotations.quotation-pdf', compact('quotation', 'customerAccount'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('quotation-' . $quotation->quotation_no . '.pdf');
    }

    private function generateQuotationNo(): string
    {
        $prefix = 'QT';
        $year = now()->format('Y');
        $month = now()->format('m');

        //ambil quotation terakhir bulan ini 
        $last = \App\Models\Quotation::where('quotation_no', 'like', "{$prefix}-{$year}-{$month}-%")
            ->orderBy('quotation_no', 'desc')
            ->first();

        if ($last){
            //ambil nomor urut terakhir lalu +1
            $lastNo =   (int) substr($last->quotation_no, -4);
            $newNo  =   str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNo = '0001';
        }

        return "{$prefix}-{$year}-{$month}-{$newNo}";
    }
}

        // $request->validate([
        //     'quotation_no'              => 'required|string',
        //     'date'                      => 'required|date',
        //     'company_name'              => 'required|string',
        //     'description'               => 'required|string',
        //     'material'                  => 'required|string',
        //     'quantity_required_pcs'     => 'required|numeric',
        //     'die_cavities'              => 'required|string',
        //     'grade_type'                => 'required|string',
        //     'form_of_supply'            => 'required|string',
        //     'qty_per_mould_pcs'         => 'required|numeric',
        //     'pattern_wax'               => 'required|string',
        //     'metal'                     => 'required|string',
        //     'soluble_wax'               => 'required|string',
        //     'ceramic'                   => 'required|string',
        //     'runner_wax'                => 'required|string',
        //     'total_raw_material_cost'   => 'required|numeric',
        //     'injection'                 => 'required|string',
        //     'cut_off'                   => 'required|string',
        //     'cleaning'                  => 'required|string',
        //     'scut_off'                  => 'required|string',
        //     'assembly'                  => 'required|string',
        //     'finishing'                 => 'required|string',
        //     'dipping'                   => 'required|string',
        //     'heat_treatment'            => 'required|string',
        //     'dewaxing'                  => 'required|string',
        //     'straight'                  => 'required|string',
        //     'burnout'                   => 'required|string',
        //     'repair'                    => 'required|string',
        //     'melting'                   => 'required|string',
        //     'blasting'                  => 'required|string',
        //     'knockout'                  => 'required|string',
        //     'inspect'                   => 'required|string',
        //     'w_blast'                   => 'required|string',
        //     'casting_weight'            => 'required|numeric',
        //     'total_minutes_per_mould'   => 'required|numeric',
        //     'total_minutes_mould'       => 'required|numeric',
        //     'machining_add'             => 'required|string',
        //     'x_ray'                     => 'required|string',
        //     'crack_det'                 => 'required|string',
        //     'polish'                    => 'required|string',
        //     'total_minutes_mould_add'   => 'required|string',
        //     'total_add'                 => 'required|string',
        //     'wax'                       => 'required|string',
        //     'fixed_overheads_usd'       => 'required|string',
        //     'straightening'             => 'required|string',
        //     'scrap_usd'                 => 'required|string',
        //     'machining_fix'             => 'required|string',
        //     'total_mould_cost'          => 'required|string',
        //     'piece_price_usd'           => 'required|string',
        //     'sub_contracting'           => 'required|string',
        //     'director_comment_approval' => 'required|string',
        //     'attachments'               => 'required|file|max:10240',
        // ]);

//         $quotation = Quotation::findOrFail($id);
//         $quotation->update($request->all());

//         if($request->hasFile('attachments')){
//             $file = $request->file('attachments');
//             $filename = time() . '_' . $file->getClientOriginalName();

//             // 1. Hapus file lama jika ada
//             if ($quotation->attachments && Storage::disk('public')->exists('uploads/' . $quotation->attachments)) {
//                 Storage::disk('public')->delete('uploads/' . $quotation->attachments);
//             }

//             // 2. Simpan file baru
//             $file->storeAs('uploads', $filename, 'public');

//             // 3. Masukkan nama file baru ke array data update
//             $data['attachments'] = $filename;
//         }
//         // Jika tidak ada file baru, $data['attachments'] tidak di-set, jadi database tetap pakai file lama

//         // Update database
//         $quotation->update($data);

//         return redirect()->route('quotation.index')->with('success', 'data berhasil disimpan!');
//         }


// }
