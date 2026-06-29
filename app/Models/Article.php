<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Daftarkan semua kolom database di sini agar bisa disimpan lewat Controller
    protected $fillable = [
        'internal_part_no', // Pastikan sesuai dengan nama kolom migration asli Anda
        'article_no',
        'part_name',
        'index_no',
        'berat',
        'die_no',
        'material',
        'drawing_no',
        'drawing_rev',
        'effective_date',
        'customer_id',
        'lokasi_pengerjaan',
        'remark',
        
        // KOLOM HARGA BARU (Wajib ditambahkan di sini)
        'casting_price',
        'machining_price',
        'price', 
        'pdf_attachment',
    ];

    /**
     * Relasi ke model User/Customer (jika diperlukan)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}