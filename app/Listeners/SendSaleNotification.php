<?php
namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Mail\SaleReceiptMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSaleNotification implements ShouldQueue
{
    public function handle(SaleCompleted $event): void
    {
        $sale = $event->sale;

        // Send receipt to customer if email exists
        if ($sale->customer && $sale->customer->email) {
            Mail::to($sale->customer->email)
                ->send(new SaleReceiptMail($sale));
        }

        // Send notification to admin/manager
        $adminEmail = config('mail.admin_email', 'admin@example.com');
        Mail::to($adminEmail)
            ->send(new SaleReceiptMail($sale, true));
    }
}