<?php

namespace App\Notifications;

use App\Models\RequestProject;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestProjectAssignedNotification extends Notification
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
        $subject = $this->project->subject ?? 'Permintaan Proyek';

        return [
            'request_id'    => $this->project->id,
            'order_no'      => 'REQ-#' . str_pad($this->project->id, 4, '0', STR_PAD_LEFT),
            'customer_id'   => $this->project->customer_id,
            'subject'       => $subject,
            'message'       => 'Anda telah ditugaskan untuk Request Project #' . $this->project->id . ' (' . $subject . ')',
            'url'           => route('requests-project.show', $this->project->id),
        ];
    }
}
