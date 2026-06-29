<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Contract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractApprovedNotification extends Notification
{
    use Queueable;

    protected Contract $contract;

    protected $approver;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract, $approver)
    {
        //
        $this->contract = $contract;
        $this->approver = $approver;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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
            'message'       => 'Contract di approve oleh manager '. $this->approver,
            'url'           => route('contracts.show', $this->contract->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }
}
