<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryActivity extends Model
{
    //
    protected $fillable = ['user_id', 'activity', 'activity_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kategori modul aktivitas
     */
    public function getModuleInfoAttribute(): array
    {
        $text = strtolower($this->activity ?? '');

        if (str_contains($text, 'request project') || str_contains($text, 'tiket request')) {
            return [
                'name'  => 'Request Project',
                'class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                'icon'  => 'bi-inbox-fill',
            ];
        }

        if (str_contains($text, 'negosiasi')) {
            return [
                'name'  => 'Negosiasi',
                'class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'icon'  => 'bi-chat-left-dots-fill',
            ];
        }

        if (str_contains($text, 'quotation')) {
            return [
                'name'  => 'Quotation',
                'class' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                'icon'  => 'bi-file-earmark-text-fill',
            ];
        }

        if (str_contains($text, 'po internal')) {
            return [
                'name'  => 'PO Internal',
                'class' => 'bg-purple-subtle text-purple border border-purple-subtle',
                'icon'  => 'bi-boxes',
            ];
        }

        if (str_contains($text, 'amandemen')) {
            return [
                'name'  => 'Amandemen',
                'class' => 'bg-warning-subtle text-danger border border-warning-subtle',
                'icon'  => 'bi-arrow-repeat',
            ];
        }

        if (str_contains($text, 'kontrak') || str_contains($text, 'contract review') || str_contains($text, 'production')) {
            return [
                'name'  => 'Review Kontrak',
                'class' => 'bg-teal-subtle text-teal border border-teal-subtle',
                'icon'  => 'bi-shield-check',
            ];
        }

        if (str_contains($text, 'po') || str_contains($text, 'purchase order')) {
            return [
                'name'  => 'Purchase Order',
                'class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                'icon'  => 'bi-file-earmark-ruled-fill',
            ];
        }

        if (str_contains($text, 'user') || str_contains($text, 'akun')) {
            return [
                'name'  => 'Pengguna',
                'class' => 'bg-secondary-subtle text-dark border border-secondary-subtle',
                'icon'  => 'bi-person-badge',
            ];
        }

        return [
            'name'  => 'Sistem',
            'class' => 'bg-light text-secondary border',
            'icon'  => 'bi-activity',
        ];
    }

    /**
     * Tipe aksi, status, dan badge warna
     */
    public function getActionInfoAttribute(): array
    {
        $text = strtolower($this->activity ?? '');

        if (str_contains($text, 'menolak') || str_contains($text, 'reject') || str_contains($text, 'ditolak')) {
            return [
                'label'       => 'Ditolak / Revisi',
                'color'       => 'danger',
                'icon'        => 'bi-x-circle-fill',
                'badge_class' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'border'      => 'border-start border-4 border-danger',
            ];
        }

        if (str_contains($text, 'production') || str_contains($text, 'finalisasi')) {
            return [
                'label'       => 'In Production',
                'color'       => 'success',
                'icon'        => 'bi-gear-wide-connected',
                'badge_class' => 'bg-success text-white',
                'border'      => 'border-start border-4 border-success',
            ];
        }

        if (str_contains($text, 'approve') || str_contains($text, 'menyetujui') || str_contains($text, 'disetujui')) {
            return [
                'label'       => 'Disetujui',
                'color'       => 'success',
                'icon'        => 'bi-check-circle-fill',
                'badge_class' => 'bg-success-subtle text-success border border-success-subtle',
                'border'      => 'border-start border-4 border-success',
            ];
        }

        if (str_contains($text, 'menutup & menyepakati') || str_contains($text, 'sepakat')) {
            return [
                'label'       => 'Disepakati',
                'color'       => 'success',
                'icon'        => 'bi-hand-thumbs-up-fill',
                'badge_class' => 'bg-success-subtle text-success border border-success-subtle',
                'border'      => 'border-start border-4 border-success',
            ];
        }

        if (str_contains($text, 'negosiasi')) {
            return [
                'label'       => 'Negosiasi',
                'color'       => 'warning',
                'icon'        => 'bi-chat-dots-fill',
                'badge_class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'border'      => 'border-start border-4 border-warning',
            ];
        }

        if (str_contains($text, 'mengirim')) {
            return [
                'label'       => 'Terkirim',
                'color'       => 'info',
                'icon'        => 'bi-send-fill',
                'badge_class' => 'bg-info-subtle text-info border border-info-subtle',
                'border'      => 'border-start border-4 border-info',
            ];
        }

        if (str_contains($text, 'menghapus')) {
            return [
                'label'       => 'Dihapus',
                'color'       => 'danger',
                'icon'        => 'bi-trash-fill',
                'badge_class' => 'bg-danger text-white',
                'border'      => 'border-start border-4 border-danger',
            ];
        }

        if (str_contains($text, 'membuat') || str_contains($text, 'input') || str_contains($text, 'menerima')) {
            return [
                'label'       => 'Dibuat / Input',
                'color'       => 'primary',
                'icon'        => 'bi-plus-circle-fill',
                'badge_class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                'border'      => 'border-start border-4 border-primary',
            ];
        }

        if (str_contains($text, 'memperbarui') || str_contains($text, 'update') || str_contains($text, 'mengupdate') || str_contains($text, 'revisi')) {
            return [
                'label'       => 'Diperbarui',
                'color'       => 'primary',
                'icon'        => 'bi-pencil-square',
                'badge_class' => 'bg-primary-subtle text-primary border border-primary-subtle',
                'border'      => 'border-start border-4 border-primary',
            ];
        }

        return [
            'label'       => 'Aktivitas',
            'color'       => 'secondary',
            'icon'        => 'bi-info-circle-fill',
            'badge_class' => 'bg-light text-secondary border',
            'border'      => '',
        ];
    }

    /**
     * Format deskripsi aktivitas dalam HTML yang rapi
     */
    public function getFormattedHtmlAttribute(): string
    {
        $raw = e($this->activity ?? '-');

        // Highlight Quotation, Contract, PO codes
        $formatted = preg_replace(
            '/\b(QT-\d{4}-\d{2}-\d{4}|CTR-[A-Za-z0-9\-_]+|CT-[A-Za-z0-9\-_]+|PO[A-Za-z0-9\-_]+)\b/',
            '<span class="badge bg-light text-primary border font-monospace px-1 py-0.5">$1</span>',
            $raw
        );

        // Highlight "Item: <part name>"
        $formatted = preg_replace(
            '/Item:\s*([^,\(\n]+)/i',
            'Item: <strong class="text-dark">$1</strong>',
            $formatted
        );

        // Highlight "(Alasan: ...)" or "(Divisi ...): ..."
        $formatted = preg_replace(
            '/\(Alasan:\s*([^\)]+)\)/i',
            '<div class="mt-1 text-danger small bg-danger-subtle p-2 rounded border border-danger-subtle"><i class="bi bi-exclamation-triangle-fill me-1"></i><strong>Alasan:</strong> $1</div>',
            $formatted
        );

        // Highlight "(Tanggapan Customer: ...)"
        $formatted = preg_replace(
            '/\(Tanggapan Customer:\s*([^\)]+)\)/i',
            '<div class="mt-1 text-primary small bg-primary-subtle p-2 rounded border border-primary-subtle"><i class="bi bi-chat-quote-fill me-1"></i><strong>Tanggapan Customer:</strong> $1</div>',
            $formatted
        );

        return $formatted;
    }

    /**
     * Waktu relatif dan terformat
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->activity_time) {
            return '-';
        }
        return \Carbon\Carbon::parse($this->activity_time)->translatedFormat('d M Y, H:i') . ' WIB';
    }

    public function getTimeAgoAttribute(): string
    {
        if (!$this->activity_time) {
            return '';
        }
        return \Carbon\Carbon::parse($this->activity_time)->diffForHumans();
    }

    /**
     * Badge visual untuk user dan divisi
     */
    public function getUserBadgeHtmlAttribute(): string
    {
        if (!$this->user) {
            return '<span class="badge bg-light text-secondary border"><i class="bi bi-robot me-1"></i>Sistem</span>';
        }

        $user = $this->user;
        $role = strtolower($user->role ?? '');
        $divisi = strtoupper($user->divisi ?? '');

        if ($role === 'admin') {
            return '<span class="badge bg-dark text-white"><i class="bi bi-shield-shaded me-1"></i>Admin</span>';
        }

        if ($role === 'manager') {
            return '<span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle"><i class="bi bi-person-workspace me-1"></i>Manager ' . ($divisi ?: 'Manajerial') . '</span>';
        }

        if ($role === 'staff') {
            return '<span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-person-fill me-1"></i>Staff ' . ($divisi ?: 'Sales') . '</span>';
        }

        if ($role === 'customer') {
            return '<span class="badge bg-success-subtle text-success-emphasis border border-success-subtle"><i class="bi bi-building me-1"></i>Customer</span>';
        }

        return '<span class="badge bg-light text-dark border">' . e($user->name) . '</span>';
    }
}
