<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Transaction;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('frontend.orders.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'invoice_number' => ['required', 'string'],
            'customer_email' => ['required', 'email'],
        ]);

        $order = Order::query()
            ->where('invoice_number', $request->invoice_number)
            ->where('customer_email', $request->customer_email)
            ->first();

        if (! $order) {
            return back()->with('error', 'Pesanan tidak ditemukan. Pastikan invoice dan email benar.');
        }

        return redirect()->route('orders.show', $order->invoice_number);
    }

    public function show(Order $order)
    {
        $order->load([
            'orderItems.item.game',
            'payment',
        ]);

        $this->syncMidtransStatus($order);

        $order->refresh()->load([
            'orderItems.item.game',
            'payment',
        ]);

        return view('frontend.orders.show', compact('order'));
    }

    private function syncMidtransStatus(Order $order): void
    {
        $payment = $order->payment;

        if (! $payment || ! $payment->midtrans_order_id) {
            return;
        }

        if ($payment->status === 'paid') {
            if ($order->status !== 'completed') {
                $order->update(['status' => 'completed']);
            }

            return;
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');

        try {
            $status = Transaction::status($payment->midtrans_order_id);

            $transactionStatus = $status->transaction_status ?? 'pending';
            $fraudStatus = $status->fraud_status ?? null;

            if (
                $transactionStatus === 'settlement' ||
                ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            ) {
                $payment->update([
                    'status' => 'paid',
                    'transaction_id' => $status->transaction_id ?? $payment->transaction_id,
                    'payment_method' => $status->payment_type ?? $payment->payment_method ?? 'midtrans',
                    'verified_at' => now(),
                ]);

                $order->update([
                    'status' => 'completed',
                ]);

                return;
            }

            if (in_array($transactionStatus, ['cancel', 'deny', 'failure'])) {
                $payment->update([
                    'status' => 'failed',
                    'transaction_id' => $status->transaction_id ?? $payment->transaction_id,
                    'payment_method' => $status->payment_type ?? $payment->payment_method ?? 'midtrans',
                ]);

                $order->update([
                    'status' => 'cancelled',
                ]);

                return;
            }

            if ($transactionStatus === 'expire') {
                $payment->update([
                    'status' => 'expired',
                    'transaction_id' => $status->transaction_id ?? $payment->transaction_id,
                    'payment_method' => $status->payment_type ?? $payment->payment_method ?? 'midtrans',
                ]);

                $order->update([
                    'status' => 'cancelled',
                ]);

                return;
            }

            $payment->update([
                'status' => 'pending',
                'transaction_id' => $status->transaction_id ?? $payment->transaction_id,
                'payment_method' => $status->payment_type ?? $payment->payment_method ?? 'midtrans',
            ]);

            $order->update([
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}