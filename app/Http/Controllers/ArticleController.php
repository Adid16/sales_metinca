<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\DetailArticle;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Get article for requirements contract simulation
     */
    public function requirements($articleNo)
{
    // Mencari data berdasarkan article_no atau internal_part_no
    $article = Article::where('article_no', $articleNo)
        ->orWhere('internal_part_no', $articleNo)
        ->first();

    if (!$article) {
        return response()->json([
            'success' => false,
            'message' => 'Data Article tidak ditemukan'
        ], 404);
    }

    // Mengembalikan semua data article termasuk kolom baru (berat, index, prices)
    return response()->json([
        'success' => true,
        'data'    => $article,
    ], 200);
}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Article::query();

    // PERBAIKAN: Mengubah 'part_number' menjadi 'internal_part_no' sesuai database
    $query->when($request->part_number, fn($q, $v) => $q->where('internal_part_no', 'like', "%{$v}%"));
    $query->when($request->article_no, fn($q, $v) => $q->where('article_no', 'like', "%{$v}%"));
    $query->when($request->part_name, fn($q, $v) => $q->where('part_name', 'like', "%{$v}%"));
    $query->when($request->customer_id, fn($q, $v) => $q->where('customer_id', $v));
    $query->when($request->created_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v));
    $query->when($request->created_to, fn($q, $v) => $q->whereDate('created_at', '<=', $v));

    // PERBAIKAN: Urutkan berdasarkan updated_at agar yang baru di-save harganya langsung muncul di paling atas
    $articles = $query->orderBy('updated_at', 'desc')
        ->paginate(10)
        ->appends($request->except('page'));

    return view('articles.index', compact('articles'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = User::select('id', 'name')->get();

        return view('articles.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // HILANGKAN ATURAN UNIQUE AGAR TIDAK BENTROK DENGAN DATA DIVISI LAIN
    $validated = $request->validate([
        'part_number'       => 'required|string|max:100',
        'article_no'        => 'required|string|max:100',
        'part_name'         => 'nullable|string|max:255',
        'index_no'          => 'nullable|string|max:100',
        'berat'             => 'nullable|numeric',
        'die_no'            => 'nullable|string|max:100',
        'material'          => 'nullable|string|max:100',
        'drawing_no'        => 'nullable|string|max:100',
        'drawing_rev'       => 'nullable|string|max:50',
        'effective_date'    => 'nullable|date',
        'lokasi_pengerjaan' => 'nullable|integer',
        'customer_id'       => 'nullable|exists:users,id',
        'remark'            => 'nullable|string',
        'casting_price'     => 'required|numeric',
        'machining_price'   => 'required|numeric',
        'price'             => 'required|numeric',
    ]);

    try {
        // Hitung ulang total price cadangan di backend
        $totalPrice = ($request->casting_price ?? 0) + ($request->machining_price ?? 0);

        // MENGGUNAKAN UPDATE OR CREATE: Jika nomor artikel sudah diinput divisi lain, kita perbarui harganya
        Article::updateOrCreate(
            ['article_no' => $request->article_no], // Kunci pencarian data lama
            [
                'internal_part_no'  => $request->part_number,
                'part_name'         => $request->part_name,
                'index_no'          => $request->index_no,
                'berat'             => $request->berat,
                'die_no'            => $request->die_no,
                'material'          => $request->material,
                'drawing_no'        => $request->drawing_no,
                'drawing_rev'       => $request->drawing_rev,
                'effective_date'    => $request->effective_date,
                'lokasi_pengerjaan' => $request->lokasi_pengerjaan,
                'customer_id'       => $request->customer_id,
                'remark'            => $request->remark,
                'casting_price'     => $request->casting_price,
                'machining_price'   => $request->machining_price,
                'price'             => $totalPrice, // Total price masuk ke kolom utama
            ]
        );

        return redirect()
            ->route('articles.index')
            ->with('success', 'Harga Pricelist Product berhasil disimpan ke sistem!');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Gagal menyimpan harga: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load('customer');

        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $customers = User::select('id', 'name')->get();

        return view('articles.edit', compact('article', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
{
    // 1. Validasi data (Aturan unik dilonggarkan agar sama seperti proses Create/Save)
    $validated = $request->validate([
        'part_number'       => 'required|string|max:100',
        'article_no'        => 'required|string|max:100',
        'part_name'         => 'nullable|string|max:255',
        'index_no'          => 'nullable|string|max:100',
        'berat'             => 'nullable|numeric',
        'die_no'            => 'nullable|string|max:100',
        'material'          => 'nullable|string|max:100',
        'drawing_no'        => 'nullable|string|max:100',
        'drawing_rev'       => 'nullable|string|max:50',
        'effective_date'    => 'nullable|date',
        'lokasi_pengerjaan' => 'nullable|integer',
        'customer_id'       => 'nullable|exists:users,id',
        'remark'            => 'nullable|string',
        'casting_price'     => 'required|numeric',
        'machining_price'   => 'required|numeric',
        'pdf_attachment'    => 'nullable|mimes:pdf|max:10240', // Maksimal 10MB
    ]);

    try {
        // 2. Proses manajemen file PDF lama dan baru
        $pdfPath = $article->pdf_attachment; // Gunakan file lama sebagai default
        
        if ($request->hasFile('pdf_attachment')) {
            // Hapus file fisik PDF lama dari storage jika sebelumnya sudah ada berkasnya
            if ($article->pdf_attachment && \Illuminate\Support\Facades\Storage::disk('public')->exists('uploads/pricelist/' . $article->pdf_attachment)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('uploads/pricelist/' . $article->pdf_attachment);
            }

            // Simpan file PDF baru
            $file = $request->file('pdf_attachment');
            $filename = time() . '_drawing_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('uploads/pricelist', $filename, 'public');
            $pdfPath = $filename;
        }

        // 3. Hitung ulang total price cadangan di backend
        $totalPrice = ($request->casting_price ?? 0) + ($request->machining_price ?? 0);

        // 4. Eksekusi update data ke model article tujuan
        $article->update([
            'internal_part_no'  => $request->part_number,
            'article_no'        => $request->article_no,
            'part_name'         => $request->part_name,
            'index_no'          => $request->index_no,
            'berat'             => $request->berat,
            'die_no'            => $request->die_no,
            'material'          => $request->material,
            'drawing_no'        => $request->drawing_no,
            'drawing_rev'       => $request->drawing_rev,
            'effective_date'    => $request->effective_date,
            'lokasi_pengerjaan' => $request->lokasi_pengerjaan,
            'customer_id'       => $request->customer_id,
            'remark'            => $request->remark,
            'casting_price'     => $request->casting_price,
            'machining_price'   => $request->machining_price,
            'price'             => $totalPrice, // Akumulasi total masuk ke kolom price utama
            'pdf_attachment'    => $pdfPath,
        ]);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Pricelist Product berhasil diperbarui!');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            // Ikut hapus berkas file PDF fisik dari storage saat data dihapus dari DB
            if ($article->pdf_attachment) {
                Storage::disk('public')->delete('uploads/pricelist/' . $article->pdf_attachment);
            }

            $article->delete();

            return redirect()
                ->route('articles.index')
                ->with('success', 'Article deleted successfully!');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete article: ' . $e->getMessage());
        }
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $articles = Article::when($search, function ($query) use ($search) {
            $query->where('part_number', 'like', "%{$search}%")
                ->orWhere('article_no', 'like', "%{$search}%")
                ->orWhere('part_name', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('articles.index', compact('articles'));
    }
}