<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KasbonNotification extends Notification
{
    use Queueable;

    public $kasbon;

    public function __construct($kasbon)
    {
        $this->kasbon = $kasbon;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_id'  => $this->kasbon->user_id,
            'message'  => auth()->user()->name . ' mengajukan kasbon sebesar Rp ' . number_format($this->kasbon->nominal, 0, ',', '.'),
            'action'   => '/kasbon',
        ];
    }
}
