<?php

namespace App\Notifications;

use App\Models\RequestProject;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRequestProjectNotification extends Notification
{
    use Queueable;

    protected RequestProject $project;

    public function __construct(RequestProject $project)
    {
        $this->project = $project;
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
        $client = $this->project->company ?? ($this->project->name ?? 'Customer');
        $subject = $this->project->subject ?? 'Permintaan Proyek Baru';

        return [
            'request_id'    => $this->project->id,
            'order_no'      => 'REQ-#' . str_pad($this->project->id, 4, '0', STR_PAD_LEFT),
            'customer_id'   => $this->project->customer_id,
            'customer_name' => $client,
            'subject'       => $subject,
            'message'       => 'Request Project baru #' . $this->project->id . ' (' . $subject . ') dari ' . $client,
            'url'           => route('requests-project.show', $this->project->id),
        ];
    }
}
