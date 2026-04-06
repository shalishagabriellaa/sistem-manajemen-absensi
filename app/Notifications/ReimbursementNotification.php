<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReimbursementNotification extends Notification
{
    use Queueable;

    public $reimbursement;

    public function __construct($reimbursement)
    {
        $this->reimbursement = $reimbursement;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->reimbursement->user_id,
            'message' => auth()->user()->name . ' mengajukan reimbursement sebesar Rp ' . number_format($this->reimbursement->total, 0, ',', '.'),
            'action'  => '/reimbursement',
        ];
    }
}