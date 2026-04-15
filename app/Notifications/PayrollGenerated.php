<?php

namespace App\Notifications;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayrollGenerated extends Notification
{
    use Queueable;

    public function __construct(public Payroll $payroll) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
        {
            return [
                'user_id'    => $notifiable->id,        // ← tambah ini
                'action'     => '/payroll/' . $this->payroll->id . '/download',  // ← ganti 'url' jadi 'action'
                'title'      => 'Slip Gaji Tersedia',
                'message'    => 'Slip gaji Anda untuk periode ' . $this->payroll->tanggal_mulai . ' s/d ' . $this->payroll->tanggal_akhir . ' telah diterbitkan.',
                'no_gaji'    => $this->payroll->no_gaji,
                'payroll_id' => $this->payroll->id,
            ];
        }
}