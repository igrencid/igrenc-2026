<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->payment?->status === 'paid') {
            return redirect()
                ->route('orders.show', $order->invoice_number)
                ->with('success', 'Pembayaran sudah berhasil.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->invoice_number,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_whatsapp,
            ],
        ];

        $snapUrl = Snap::createTransaction($params)->redirect_url;

        return redirect()->away($snapUrl);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        $signature = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signature !== $request->signature_key) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 403);
        }

        $order = Order::where('invoice_number', $request->order_id)->first();

        if (! $order || ! $order->payment) {
            return response()->json([
                'message' => 'Order or payment not found',
            ], 404);
        }

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            $this->markAsPaid($order, $request);
        }

        if ($transactionStatus === 'settlement') {
            $this->markAsPaid($order, $request);
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->payment->update([
                'status' => 'rejected',
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_type,
            ]);

            $order->update([
                'status' => 'cancelled',
            ]);
        }

        if ($transactionStatus === 'pending') {
            $order->payment->update([
                'status' => 'pending',
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_type,
            ]);

            $order->update([
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Callback processed',
        ]);
    }

    private function markAsPaid(Order $order, Request $request): void
    {
        $order->payment->update([
            'status' => 'paid',
            'transaction_id' => $request->transaction_id,
            'payment_method' => $request->payment_type,
            'verified_at' => now(),
        ]);

        $order->update([
            'status' => 'completed',
        ]);
    }
}