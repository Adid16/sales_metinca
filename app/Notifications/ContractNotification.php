<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContractNotification extends Notification
{
    use Queueable;

    protected Contract $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Channel notifikasi
     */
    public function via($notifiable)
    {
        return ['database']; 
        // Tambahkan 'mail' jika ingin email
    }

    /**
     * Data untuk database notification
     */
    public function toDatabase($notifiable)
    {
        $contractNo = $this->contract->contract_no ?? ('CTR-' . $this->contract->order_no);
        return [
            'contract_id'   => $this->contract->id,
            'order_no'      => $this->contract->order_no,
            'customer_id'   => $this->contract->customer_id,
            'message'       => 'Draft Contract Review Sheet #' . $contractNo . ' (Order No: ' . $this->contract->order_no . ') membutuhkan persetujuan divisi Anda.',
            'url'           => route('contracts.show', $this->contract->id),
            'category'      => 'contract',
        ];
    }

    /**
     * (Opsional) Email notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Contract Approval Required')
            ->line('Sebuah contract membutuhkan approval Anda.')
            ->line('Order No: ' . $this->contract->order_no)
            ->action('Review Contract', route('contracts.show', $this->contract->id))
            ->line('Silakan lakukan approval melalui sistem.');
    }
}
