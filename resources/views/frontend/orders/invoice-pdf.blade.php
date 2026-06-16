<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_number }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .page {
            padding: 28px;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 18px;
            margin-bottom: 22px;
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
            color: #7c3aed;
        }

        .subtitle {
            color: #6b7280;
            margin-top: 4px;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            text-align: right;
            color: #111827;
        }

        .invoice-meta {
            text-align: right;
            color: #6b7280;
            margin-top: 6px;
        }

        .grid {
            width: 100%;
            margin-bottom: 16px;
        }

        .grid td {
            width: 50%;
            vertical-align: top;
            border: none;
            padding: 0;
        }

        .box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 14px;
        }

        .box-left {
            margin-right: 8px;
        }

        .box-right {
            margin-left: 8px;
        }

        .box-title {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 10px;
        }

        .row {
            margin-bottom: 6px;
        }

        .label {
            color: #6b7280;
            display: inline-block;
            min-width: 90px;
        }

        .value {
            font-weight: bold;
            color: #111827;
        }

        .success {
            border: 1px solid #16a34a;
            background: #f0fdf4;
            color: #166534;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-weight: bold;
        }

        .warning {
            border: 1px solid #f59e0b;
            background: #fffbeb;
            color: #92400e;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 18px;
        }

        table.items th {
            background: #7c3aed;
            color: #ffffff;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }

        table.items td {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .item-name {
            font-weight: bold;
            color: #111827;
        }

        .muted {
            color: #6b7280;
        }

        .access-box {
            border: 1px solid #06b6d4;
            background: #ecfeff;
            border-radius: 8px;
            padding: 10px;
            margin-top: 8px;
        }

        .access-title {
            font-weight: bold;
            color: #0e7490;
            margin-bottom: 5px;
        }

        .manual-box {
            border: 1px solid #f59e0b;
            background: #fffbeb;
            border-radius: 8px;
            padding: 10px;
            margin-top: 8px;
            color: #92400e;
        }

        a {
            color: #2563eb;
            text-decoration: none;
            word-break: break-all;
        }

        .summary {
            width: 45%;
            margin-left: auto;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px;
            margin-top: 10px;
        }

        .summary-row {
            width: 100%;
            margin-bottom: 8px;
        }

        .summary-label {
            color: #6b7280;
        }

        .summary-value {
            float: right;
            font-weight: bold;
        }

        .grand-total {
            border-top: 1px solid #e5e7eb;
            margin-top: 10px;
            padding-top: 12px;
            font-size: 18px;
            font-weight: bold;
            color: #0891b2;
        }

        .footer {
            margin-top: 26px;
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
            color: #6b7280;
            font-size: 11px;
        }

        .cs-box {
            border: 1px solid #7c3aed;
            background: #faf5ff;
            border-radius: 10px;
            padding: 14px;
            margin-top: 18px;
        }

        .cs-title {
            font-weight: bold;
            color: #6d28d9;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>
@php
    $paymentStatus = $order->payment?->status ?? 'pending';

    $isPaid = in_array($paymentStatus, ['paid', 'settlement', 'capture']) || $order->status === 'completed';

    $isFailed = in_array($paymentStatus, ['failed', 'deny', 'cancel', 'failure']);

    $statusClass = $isPaid ? 'status-paid' : ($isFailed ? 'status-failed' : 'status-pending');

    $statusLabel = $isPaid
        ? 'Pembayaran Berhasil'
        : ($isFailed ? 'Pembayaran Gagal' : 'Menunggu Pembayaran');

    $csWhatsapp = '6285813295317';
@endphp

<div class="page">
    <table class="header">
        <tr>
            <td>
                <div class="brand">IgrencGame</div>
                <div class="subtitle">Marketplace item game digital terpercaya</div>
            </td>

            <td>
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    {{ $order->invoice_number }}<br>
                    {{ $order->created_at->format('d M Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    @if($isPaid)
        <div class="success">
            Pembayaran berhasil. Invoice ini menjadi bukti pembelian resmi
        </div>
    @else
        <div class="warning">
            Invoice ini masih menunggu pembayaran. Selesaikan pembayaran agar item dapat diproses
        </div>
    @endif

    <table class="grid">
        <tr>
            <td>
                <div class="box box-left">
                    <div class="box-title">Data Customer</div>

                    <div class="row">
                        <span class="label">Nama</span>
                        <span class="value">{{ $order->customer_name }}</span>
                    </div>

                    <div class="row">
                        <span class="label">Email</span>
                        <span class="value">{{ $order->customer_email }}</span>
                    </div>

                    <div class="row">
                        <span class="label">WhatsApp</span>
                        <span class="value">{{ $order->customer_whatsapp ?? '-' }}</span>
                    </div>
                </div>
            </td>

            <td>
                <div class="box box-right">
                    <div class="box-title">Status Pesanan</div>

                    <div class="row">
                        <span class="label">Order</span>
                        <span class="value">{{ strtoupper($order->status) }}</span>
                    </div>

                    <div class="row">
                        <span class="label">Payment</span>
                        <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>

                    <div class="row">
                        <span class="label">Metode</span>
                        <span class="value">{{ strtoupper(str_replace('_', ' ', $order->payment?->payment_method ?? 'midtrans')) }}</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="box-title">Detail Item</div>

    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Harga</th>
                <th class="right">Qty</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->orderItems as $orderItem)
                <tr>
                    <td>
                        <div class="item-name">{{ $orderItem->item_name }}</div>

                        @if($isPaid && $orderItem->item?->requires_access_link)
                            <div class="access-box">
                                <div class="access-title">Link Akses Khusus</div>

                                @if($orderItem->item->access_instruction)
                                    <div>
                                        Instruksi: {{ $orderItem->item->access_instruction }}
                                    </div>
                                @endif

                                @if($orderItem->item->access_link)
                                    <div style="margin-top: 6px;">
                                        Link:
                                        <a href="{{ $orderItem->item->access_link }}">
                                            {{ $orderItem->item->access_link }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @elseif($isPaid)
                            <div class="manual-box">
                                Delivery manual oleh admin jika diperlukan
                            </div>
                        @else
                            <div class="muted" style="margin-top: 6px;">
                                Akses item tersedia setelah pembayaran berhasil
                            </div>
                        @endif
                    </td>

                    <td class="right">
                        Rp {{ number_format($orderItem->price, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        {{ $orderItem->quantity }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Subtotal</span>
            <span class="summary-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="summary-row">
            <span class="summary-label">Biaya Admin</span>
            <span class="summary-value">Rp 0</span>
        </div>

        <div class="grand-total">
            Total
            <span style="float: right;">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="cs-box">
        <div class="cs-title">Customer Service</div>

        Jika pembayaran belum berubah atau item belum diterima, hubungi CS IgrencGame melalui WhatsApp:
        <a href="https://wa.me/{{ $csWhatsapp }}">+{{ $csWhatsapp }}</a>
    </div>

    <div class="footer">
        Simpan invoice ini sebagai bukti pembelian. Jika item memiliki link akses khusus, gunakan link tersebut sesuai instruksi yang tersedia
        <br>
        © {{ date('Y') }} IgrencGame
    </div>
</div>
</body>
</html>