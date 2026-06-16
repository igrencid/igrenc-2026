<x-mail::message>
# Pembayaran Berhasil

Halo **{{ $order->customer_name }}**,

Pembayaran untuk pesanan **{{ $order->invoice_number }}** sudah berhasil.

**Total Pembayaran:** Rp {{ number_format($order->total_price, 0, ',', '.') }}

<x-mail::panel>
Invoice PDF sudah kami lampirkan di email ini.
</x-mail::panel>

## Detail Item

@foreach($order->orderItems as $orderItem)
**{{ $orderItem->item_name }}**  
Qty: {{ $orderItem->quantity }}  
Subtotal: Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}

@if($orderItem->item?->requires_access_link)
<x-mail::panel>
**Link Akses Khusus**

@if($orderItem->item->access_instruction)
Instruksi: {{ $orderItem->item->access_instruction }}
@endif

@if($orderItem->item->access_link)
Link: {{ $orderItem->item->access_link }}
@endif
</x-mail::panel>
@else
Delivery item akan diproses manual oleh admin jika diperlukan.
@endif

---
@endforeach

<x-mail::button :url="route('orders.show', $order->invoice_number)">
Lihat Detail Pesanan
</x-mail::button>

Terima kasih,  
**IgrencGame**
</x-mail::message>