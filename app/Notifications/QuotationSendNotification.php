<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class QuotationSendNotification extends Notification
{
    // use Queueable;

    protected Quotation $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Channel notifikasi yang digunakan
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Notifikasi Email
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Quotation Telah Dikirim')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Quotation dengan nomor ' . $this->quotation->quotation_no . ' telah dikirim oleh tim kami.')
            ->line('Silakan meninjau detail quotation melalui sistem.')
            ->action(
                'Lihat Quotation',
                route('quotations.show', $this->quotation->id)
            )
            ->line('Terima kasih atas kepercayaan Anda.');
    }

    /**
     * Notifikasi Database
     */
    public function toDatabase($notifiable): array
    {
        return [
            'quotation_id' => $this->quotation->id,
            'quotation_no' => $this->quotation->quotation_no,
            'order_no'     => $this->quotation->quotation_no,
            'status'       => $this->quotation->status,
            'message'      => 'Quotation #' . $this->quotation->quotation_no . ' telah dikirim oleh tim Sales. Silakan ditinjau.',
            'url'          => route('quotations.show', $this->quotation->id),
        ];
    }
}
