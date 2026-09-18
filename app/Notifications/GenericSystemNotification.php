<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GenericSystemNotification extends Notification
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Data untuk database notification
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message'  => $this->data['message'] ?? 'Pemberitahuan Sistem',
            'url'      => $this->data['url'] ?? route('notifikasi'),
            'order_no' => $this->data['order_no'] ?? null,
            'category' => $this->data['category'] ?? null,
        ];
    }
}
