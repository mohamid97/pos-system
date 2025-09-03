<?php
namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Sale $sale,
        public bool $isAdminNotification = false
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->isAdminNotification 
            ? "New Sale Notification - #{$this->sale->sale_number}"
            : "Your Purchase Receipt - #{$this->sale->sale_number}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->isAdminNotification 
                ? 'emails.sale-admin-notification'
                : 'emails.sale-receipt'
        );
    }
}