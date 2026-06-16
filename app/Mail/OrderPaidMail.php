<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {
        $this->order->load([
            'orderItems.item',
            'payment',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pembelian ' . $this->order->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.paid',
            with: [
                'order' => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('frontend.orders.invoice-pdf', [
            'order' => $this->order,
        ])->setPaper('a4');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'invoice-' . $this->order->invoice_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}