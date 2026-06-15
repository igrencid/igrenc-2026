<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        $order->load([
            'orderItems.item',
            'payment',
        ]);

        $pdf = Pdf::loadView('frontend.orders.invoice-pdf', compact('order'))
            ->setPaper('a4');

        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }
}