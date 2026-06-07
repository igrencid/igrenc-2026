<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
            'refundRequests' => fn ($query) => $query->latest(),
        ]);

        return view('frontend.orders.show', compact('order'));
    }

    public function uploadProof(Request $request, Order $order)
    {
        if ($order->payment?->status === 'paid') {
            return back()->with('error', 'Pembayaran sudah diverifikasi.');
        }

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method' => $order->payment?->payment_method ?? 'manual',
                'amount' => $order->total_price,
                'payment_proof' => $path,
                'status' => 'waiting_verification',
            ]
        );

        $order->update([
            'status' => 'waiting_verification',
        ]);

        return back()->with('success', 'Bukti transfer berhasil diupload. Status pembayaran menunggu verifikasi admin.');
    }
}