<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'delivery_request',
        'quotation_id',
        'attachment',
        'po_no',
        'status',
        'status_order'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class,'quotation_id');
    }

    public function internals()
    {
        return $this->hasMany(\App\Models\PurchaseOrderInternal::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
    //  protected static function booted()
    // {
    //     static::creating(function ($po) {

    //         $year = now()->year;

    //         $last = self::whereYear('created_at', $year)
    //             ->where('po_no', 'like', "PO-$year-%")
    //             ->orderByDesc('id')
    //             ->first();

    //         $next = $last
    //             ? ((int) substr($last->po_no, -3)) + 1
    //             : 1;

    //         $po->po_no = sprintf('PO-%d-%03d', $year, $next);
    //     });
    // }

    public static function newAmandement()
{
    $year = now()->year;

    // Cari PO terakhir yang nomornya berawalan PO- di tahun ini
    $last = self::whereYear('created_at', $year)
        ->where('po_no', 'like', "PO-$year-%")
        ->orderByDesc('id')
        ->first();

    // Jika ada PO sebelumnya, ambil 3 digit terakhir dan tambah 1. Jika belum ada, mulai dari 1.
    $next = $last
        ? ((int) substr($last->po_no, -3)) + 1
        : 1;

    // Menghasilkan nomor PO baru berurutan (Contoh: PO-2026-002)
    return sprintf('PO-%d-%03d', $year, $next);
}


public function amendments()
{
    return $this->hasMany(PoAmendment::class, 'purchase_order_id');
}
public function contracts()
{
    // Menghubungkan nomor PO dengan order_no di tabel contracts
    return $this->hasMany(Contract::class, 'order_no', 'po_no')->orderBy('amandement_no', 'asc');
}
}
