<?php
namespace App\Listeners;

use App\Events\LowStockAlert;
use App\Mail\LowStockAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLowStockNotification implements ShouldQueue
{
    public function handle(LowStockAlert $event): void
    {
        $product = $event->product;

        $adminEmail = config('mail.admin_email', 'admin@example.com');
        Mail::to($adminEmail)
            ->send(new LowStockAlertMail($product));
    }
}