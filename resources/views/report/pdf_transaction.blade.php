<!DOCTYPE html>
<html>
<head>
    <title>Transaction Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Transaction Report</h2>
    <p>Periode: {{ $period }} - {{ $year }} {{ $month ?? '' }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Cashier</th>
                <th>Date</th>
                <th>Payment Method</th>
                <th class="text-right">Total Price (Rp)</th>
                <th class="text-right">Total Profit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
                @php
                    $totalPrice = $order->orderDetails->sum(fn($d) => $d->selling_price * $d->qty);
                    $totalProfit = $order->orderDetails->sum(fn($d) => ($d->selling_price - $d->capital_price) * $d->qty);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->cashier->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
                    <td>{{ $order->paymentMethod->method }}</td>
                    <td class="text-right">{{ number_format($totalPrice, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalProfit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total Profit: Rp {{ number_format($totalProfitSum, 0, ',', '.') }}</strong></p>
</body>
</html>
