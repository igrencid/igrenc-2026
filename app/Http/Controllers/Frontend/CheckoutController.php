<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('items.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('frontend.checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_whatsapp' => ['nullable', 'string', 'max:30'],
            'payment_method' => ['required', 'in:bank_transfer,qris,ewallet'],
            'notes' => ['nullable', 'string'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('items.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $items = Item::whereIn('id', collect($cart)->pluck('id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $total = 0;

                foreach ($cart as $cartItem) {
                    $item = $items->get($cartItem['id']);
                    $quantity = (int) $cartItem['quantity'];

                    if (! $item || ! $item->is_active || $item->stock < $quantity) {
                        throw new \Exception('Item tidak tersedia atau stok tidak cukup.');
                    }

                    $total += $item->price * $quantity;
                }

                $order = Order::create([
                    'invoice_number' => 'GV-' . now()->format('YmdHis') . rand(100, 999),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_whatsapp' => $request->customer_whatsapp,
                    'total_price' => $total,
                    'status' => 'pending',
                    'notes' => $request->notes,
                ]);

                foreach ($cart as $cartItem) {
                    $item = $items->get($cartItem['id']);
                    $quantity = (int) $cartItem['quantity'];

                    $order->orderItems()->create([
                        'item_id' => $item->id,
                        'item_name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $quantity,
                        'subtotal' => $item->price * $quantity,
                    ]);

                    $item->decrement('stock', $quantity);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'amount' => $total,
                    'status' => 'pending',
                ]);

                return $order;
            });
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()
            ->route('orders.show', $order->invoice_number)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran dan upload bukti transfer.');
    }

    public function success(Order $order)
    {
        return redirect()->route('orders.show', $order->invoice_number);
    }
}