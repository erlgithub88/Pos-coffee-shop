<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<title>Struk Order #{{ $order->id }}</title>
	<style>
		body {
			font-family: monospace;
			font-size: 11pt;
			width: 250px;
			margin: auto;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}

		.mt-2 {
			margin-top: 8px;
		}

		.mb-1 {
			margin-bottom: 4px;
		}

		.mb-2 {
			margin-bottom: 8px;
		}

		.mb-3 {
			margin-bottom: 12px;
		}

		hr {
			border: none;
			border-top: 1px dashed #000;
			margin: 6px 0;
		}

		.logo {
			display: block;
			margin: 0 auto 2px;
			width: 100px;
			height: auto;
		}

		table {
			width: 100%;
		}

		td {
			vertical-align: top;
		}
	</style>
</head>

<body onload="printMultiple()">

	@php $copies = 2; @endphp

	@for ($i = 1; $i <= $copies; $i++)
		<div class="struk-copy">
		<div class="text-center mb-2">
			<img src="{{ asset('/storage/images/logo-coffee1.png') }}" alt="Logo" class="logo">
			<strong>COFFEE SHOP ERLANGGA</strong><br>
			Jl. Ngopi No. 47<br>
			0899-XXXX-XXX
		</div>

		<hr>

		<div class="mb-1">
			Order ID : #{{ $order->id }}<br>
			Tanggal : {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i') }}<br>
			Kasir : {{ $order->cashier->name }}
		</div>

		<hr>

		<table>
			@foreach ($order->orderDetails as $detail)
			<tr>
				<td colspan="2">{{ $detail->item->name }}</td>
			</tr>
			<tr>
				<td>{{ $detail->qty }} x Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
				<td class="text-right">Rp {{ number_format($detail->selling_price * $detail->qty, 0, ',', '.') }}</td>
			</tr>
			@endforeach
		</table>

		<hr>

		@php
		$subtotal = $order->orderDetails->sum(fn($d) => $d->selling_price * $d->qty);
		$total = $order->discount_type === 'percentage'
		? $subtotal - ($subtotal * $order->discount / 100)
		: $subtotal - $order->discount;
		@endphp

		<table>
			<tr>
				<td><strong>Subtotal</strong></td>
				<td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
			</tr>
			@if ($order->discount > 0)
			<tr>
				<td>Diskon</td>
				<td class="text-right">
					@if ($order->discount_type === 'percentage')
					-{{ $order->discount }}%
					@else
					-Rp {{ number_format($order->discount, 0, ',', '.') }}
					@endif
				</td>
			</tr>
			@endif
			<tr>
				<td><strong>Total</strong></td>
				<td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
			</tr>
			<tr>
				<td>Bayar via</td>
				<td class="text-right">{{ $order->paymentMethod->method }}</td>
			</tr>
		</table>

		<hr>

		@php
		$ngrokBaseUrl = env('NGROK_URL','https://ce57-202-46-68-201.ngrok-free.app');
		$publicUrl = $ngrokBaseUrl . '/order/' . $order->id . '/detail';
		$paymentMethod = strtolower($order->paymentMethod->method);
		$qrLink = $paymentMethod === 'dana'
		? env('QRIS_DANA_URL', 'https://link.dana.id/minta?full_url=https://qr.dana.id/v1/281012012023042236909826')
		: $publicUrl;
		@endphp

		@if ($paymentMethod === 'dana')
		<div class="text-center">
			<img style="display: block; margin: 0 auto;"
				src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($qrLink) }}"
				alt="QR Code">
			<em>Scan QR untuk bayar via DANA</em><br>
			*** Terima Kasih ***
		</div>
		@else
		<div class="text-center">
			*** Terima Kasih ***
		</div>
		@endif


		@if ($i < $copies)
			<div style="page-break-after: always;">
			</div>
			@endif
			</div>
			@endfor

			<script>
				function printMultiple() {
					window.print();
				}
			</script>

</body>

</html>