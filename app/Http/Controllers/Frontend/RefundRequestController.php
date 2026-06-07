<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->payment?->status !== 'rejected') {
            return back()->with('error', 'Refund hanya bisa diajukan jika pembayaran ditolak.');
        }

        $request->validate([
            'refund_method' => ['required', 'in:bank,ewallet'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string'],
        ]);

        $order->refundRequests()->create([
            'payment_id' => $order->payment?->id,
            'refund_method' => $request->refund_method,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Data refund berhasil dikirim. Admin akan memproses pengembalian dana.');
    }
}