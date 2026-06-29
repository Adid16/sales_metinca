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
        return [
            'contract_id'   => $this->contract->id,
            'order_no'      => $this->contract->order_no,
            'customer_id'   => $this->contract->customer_id,
            'message'       => 'Contract membutuhkan approval manager.',
            'url'           => route('contracts.show', $this->contract->id),
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
