<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('frontend.cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Item $item)
    {
        if (! $item->is_active || $item->stock < 1) {
            return back()->with('error', 'Item tidak tersedia.');
        }

        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:' . $item->stock],
        ]);

        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        $currentQuantity = $cart[$item->id]['quantity'] ?? 0;
        $newQuantity = min($currentQuantity + $quantity, $item->stock);

        $cart[$item->id] = [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'image' => $item->image,
            'game' => $item->game?->name,
            'category' => $item->category?->name,
            'price' => (float) $item->price,
            'stock' => (int) $item->stock,
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Item berhasil masuk keranjang.');
    }

    public function update(Request $request, Item $item)
    {
        if (! $item->is_active || $item->stock < 1) {
            return back()->with('error', 'Item tidak tersedia.');
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $item->stock],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] = min((int) $request->quantity, $item->stock);
            $cart[$item->id]['stock'] = (int) $item->stock;

            session()->put('cart', $cart);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Item $item)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$item->id])) {
            unset($cart[$item->id]);

            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}