<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->payment?->status === 'paid') {
            return redirect()
                ->route('orders.show', $order->invoice_number)
                ->with('success', 'Pembayaran sudah berhasil.');
        }

        $payment = $order->payment;

        if (! $payment) {
            return redirect()
                ->route('orders.show', $order->invoice_number)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($payment->snap_url) {
            return redirect()->away($payment->snap_url);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $midtransOrderId = $order->invoice_number . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_whatsapp,
            ],
            'callbacks' => [
                'finish' => route('orders.midtrans.finish', $order->invoice_number),
            ],
        ];

        try {
            $transaction = Snap::createTransaction($params);

            $payment->update([
                'payment_method' => 'midtrans',
                'midtrans_order_id' => $midtransOrderId,
                'snap_url' => $transaction->redirect_url,
                'status' => 'pending',
            ]);

            return redirect()->away($transaction->redirect_url);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('orders.show', $order->invoice_number)
                ->with('error', 'Sistem pembayaran sedang mengalami gangguan. Silakan coba beberapa saat lagi.');
        }
    }

    public function finish(Order $order)
    {
        $order->load('payment');

        $payment = $order->payment;

        if (! $payment || ! $payment->midtrans_order_id) {
            return redirect()
                ->route('orders.show', $order->invoice_number)
                ->with('warning', 'Data pembayaran belum tersedia.');
        }

        if ($payment->status === 'paid') {
            $order->update(['status' => 'completed']);

            return redirect()
                ->route('orders.show', $order->invoice_number)
                ->with('success', 'Pembayaran berhasil. Invoice sudah dikirim ke email kamu.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');

        try {
            $status = Transaction::status($payment->midtrans_order_id);

            $this->updatePaymentStatus($order, $payment, $status->transaction_status ?? 'pending', [
                'transaction_id' => $status->transaction_id ?? null,
                'payment_type' => $status->payment_type ?? 'midtrans',
                'fraud_status' => $status->fraud_status ?? null,
            ]);

            $payment->refresh();
            $order->refresh();

            if ($payment->status === 'paid') {
                return redirect()
                    ->route('orders.show', $order->invoice_number)
                    ->with('success', 'Pembayaran berhasil. Invoice sudah dikirim ke email kamu.');
            }

            if ($payment->status === 'failed') {
                return redirect()
                    ->route('orders.show', $order->invoice_number)
                    ->with('error', 'Pembayaran gagal atau dibatalkan.');
            }

            if ($payment->status === 'expired') {
                return redirect()
                    ->route('orders.show', $order->invoice_number)
                    ->with('warning', 'Pembayaran sudah kedaluwarsa.');
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('orders.show', $order->invoice_number)
            ->with('warning', 'Pembayaran sedang diproses. Silakan refresh beberapa saat lagi.');
    }

    public function callback(Request $request)
    {
        return $this->handleMidtransNotification($request);
    }

    public function notification(Request $request)
    {
        return $this->handleMidtransNotification($request);
    }

    private function handleMidtransNotification(Request $request)
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
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $this->updatePaymentStatus($payment->order, $payment, $request->transaction_status ?? 'pending', [
            'transaction_id' => $request->transaction_id,
            'payment_type' => $request->payment_type,
            'fraud_status' => $request->fraud_status,
        ]);

        return response()->json(['message' => 'Midtrans notification processed']);
    }

    private function updatePaymentStatus(Order $order, Payment $payment, string $transactionStatus, array $data = []): void
    {
        $paymentStatus = match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => ($data['fraud_status'] ?? null) === 'accept' ? 'paid' : 'pending',
            'deny', 'cancel', 'failure' => 'failed',
            'expire' => 'expired',
            default => 'pending',
        };

        $orderStatus = match ($paymentStatus) {
            'paid' => 'completed',
            'failed', 'expired' => 'cancelled',
            default => 'pending',
        };

        $wasNotPaid = $payment->status !== 'paid';

        $payment->update([
            'status' => $paymentStatus,
            'transaction_id' => $data['transaction_id'] ?? $payment->transaction_id,
            'payment_method' => $data['payment_type'] ?? $payment->payment_method ?? 'midtrans',
            'verified_at' => $paymentStatus === 'paid'
                ? ($payment->verified_at ?? now())
                : $payment->verified_at,
        ]);

        $order->update([
            'status' => $orderStatus,
        ]);

        if ($paymentStatus === 'paid' && $wasNotPaid) {
            $order->load(['orderItems.item', 'payment']);

            Mail::to($order->customer_email)->send(
                new OrderPaidMail($order)
            );
        }
    }
}