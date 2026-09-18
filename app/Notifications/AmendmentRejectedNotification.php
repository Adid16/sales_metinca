<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AmendmentRejectedNotification extends Notification
{
    use Queueable;

    protected PurchaseOrder $po;
    protected string $reason;

    public function __construct(PurchaseOrder $po, string $reason)
    {
        $this->po = $po;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'po_id'    => $this->po->id,
            'order_no' => $this->po->po_no,
            'message'  => 'Pengajuan Amandemen PO #' . $this->po->po_no . ' ditolak. Alasan: ' . $this->reason,
            'url'      => route('purchase-orders.index'),
            'category' => 'rejected',
        ];
    }
}
