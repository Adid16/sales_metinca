<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'delivery_request',
        'quotation_id',
        'attachment',
        'po_no',
        'status',
        'status_order'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function internals()
    {
        return $this->hasMany(\App\Models\PurchaseOrderInternal::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public static function newAmandement()
    {
        $year = now()->year;

        $last = self::whereYear('created_at', $year)
            ->where('po_no', 'like', "PO-$year-%")
            ->orderByDesc('id')
            ->first();

        $next = $last
            ? ((int) substr($last->po_no, -3)) + 1
            : 1;

        return sprintf('PO-%d-%03d', $year, $next);
    }

    public function amendments()
    {
        return $this->hasMany(PoAmendment::class, 'purchase_order_id');
    }

    public function contracts()
    {
        // Relasi jamak (hasMany)
        return $this->hasMany(Contract::class, 'order_no', 'po_no')->orderBy('amandement_no', 'asc');
    }

    // =========================================================================
    // TAMBAHKAN RELASI SINGULAR INI AGAR EAGER LOADING 'contract' TIDAK ERROR
    // =========================================================================
    public function contract()
    {
        return $this->hasOne(Contract::class, 'order_no', 'po_no');
    }

    public function internalContracts()
    {
        return $this->hasManyThrough(Contract::class, PurchaseOrderInternal::class, 'purchase_order_id', 'purchase_order_internal_id');
    }

    public function getSalesPicAttribute()
    {
        if ($this->quotation && $this->quotation->request && $this->quotation->request->assignment && $this->quotation->request->assignment->sales) {
            return $this->quotation->request->assignment->sales;
        }
        return null;
    }

    /**
     * Hitung status efektif / agregat Master PO berdasarkan seluruh item / sub-PO internalnya.
     * Mengambil tingkatan status yang paling awal (paling jadul / minimum progress),
     * dan HANYA menjadi 'production' jika SEMUA sub-item sudah masuk ke tahap production/done.
     */
    public function getEffectiveStatusAttribute(): string
    {
        $internals = $this->internals;
        $totalInternals = $internals ? $internals->count() : 0;
        $totalQuotation = ($this->quotation && $this->quotation->items) ? $this->quotation->items->count() : 0;
        $totalExpected = max($totalInternals, $totalQuotation, 1);

        if ($totalInternals === 0) {
            return strtolower($this->attributes['status'] ?? 'sent');
        }

        $itemStatuses = [];
        foreach ($internals as $internal) {
            $contract = $internal->contract ?? ($internal->contracts ? $internal->contracts->sortByDesc('amandement_no')->first() : null);
            if ($contract) {
                $itemStatuses[] = strtolower($contract->status ?? '');
            } else {
                $itemStatuses[] = strtolower($internal->status ?? 'created');
            }
        }

        // Jika ada item quotation yang belum sempat dibuatkan PO Internalnya
        if ($totalInternals < $totalExpected) {
            $itemStatuses[] = 'sent';
        }

        // 1. Urgensi tertinggi: Ada yang sedang mengajukan amandemen
        if (in_array('amandement_pending', $itemStatuses)) {
            return 'amandement_pending';
        }

        // 2. Amandemen disetujui, perlu diproses ulang ke internal
        if (in_array('amandement', $itemStatuses)) {
            return 'amandement';
        }

        // 3. Status paling awal / belum diproses
        if (in_array('sent', $itemStatuses) || in_array('open', $itemStatuses) || in_array('draft', $itemStatuses) || in_array('belum_diproses', $itemStatuses)) {
            return 'sent';
        }

        // 4. Ada item yang masih dalam tahap review / revisi / created
        if (in_array('review', $itemStatuses) || in_array('revision', $itemStatuses) || in_array('created', $itemStatuses) || in_array('waiting_approval', $itemStatuses)) {
            return 'review';
        }

        // 5. Jika seluruh item 100% sudah selesai (done)
        $allDone = count($itemStatuses) >= $totalExpected && collect($itemStatuses)->every(fn($s) => in_array($s, ['done', 'finished', 'completed']));
        if ($allDone) {
            return 'done';
        }

        // 6. Jika SELURUH item sudah 'production' atau 'done'
        $allProd = count($itemStatuses) >= $totalExpected && collect($itemStatuses)->every(fn($s) => in_array($s, ['production', 'done', 'finished', 'completed']));
        if ($allProd) {
            return 'production';
        }

        // 7. Jika semua item sudah diapprove 4 divisi (siap difinalisasi)
        $allApproved = collect($itemStatuses)->every(fn($s) => in_array($s, ['approved', 'contract', 'production', 'done']));
        if ($allApproved) {
            return 'contract';
        }

        return 'review';
    }
}