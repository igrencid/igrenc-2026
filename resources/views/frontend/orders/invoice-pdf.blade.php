<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_number }}</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            color:#111827;
            font-size:12px;
        }

        .header{
            border-bottom:3px solid #7c3aed;
            padding-bottom:15px;
            margin-bottom:25px;
        }

        .brand{
            font-size:28px;
            font-weight:bold;
            color:#7c3aed;
        }

        .title{
            font-size:18px;
            font-weight:bold;
            margin-top:5px;
        }

        .box{
            border:1px solid #ddd;
            padding:15px;
            margin-bottom:15px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        th{
            background:#7c3aed;
            color:white;
            padding:10px;
            text-align:left;
        }

        td{
            border-bottom:1px solid #ddd;
            padding:10px;
        }

        .right{
            text-align:right;
        }

        .total{
            font-size:18px;
            font-weight:bold;
            color:#0891b2;
        }

        .status{
            font-weight:bold;
            text-transform:uppercase;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="brand">IgrencGame</div>

        <div class="title">
            Invoice Pembelian
        </div>

        <div>
            Invoice : {{ $order->invoice_number }}
        </div>

        <div>
            Tanggal : {{ $order->created_at->format('d M Y H:i') }}
        </div>
    </div>

    <div class="box">
        <strong>Data Customer</strong>

        <br><br>

        Nama :
        {{ $order->customer_name }}

        <br>

        Email :
        {{ $order->customer_email }}

        <br>

        Whatsapp :
        {{ $order->customer_whatsapp ?? '-' }}
    </div>

    <div class="box">
        <strong>Status Pesanan</strong>

        <br><br>

        Order :
        <span class="status">
            {{ strtoupper($order->status) }}
        </span>

        <br>

        Payment :
        <span class="status">
            {{ strtoupper($order->payment?->status ?? 'pending') }}
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Harga</th>
                <th class="right">Qty</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        {{ $item->item_name }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->price,0,',','.') }}
                    </td>

                    <td class="right">
                        {{ $item->quantity }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->subtotal,0,',','.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="right total">
        Total :
        Rp {{ number_format($order->total_price,0,',','.') }}
    </p>

</body>
</html>