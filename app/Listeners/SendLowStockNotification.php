<?php

namespace App\Listeners;

use App\Events\LowStockAlert;
use App\Mail\LowStockAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLowStockNotification
{

    public function handle(LowStockAlert $event): void
    {
       
        $adminEmail = config('setting.admin_email', 'muhmdhamed757@gmail.com');
        Mail::to($adminEmail)
            ->send(new LowStockAlertMail($event->product));
    }
    
}