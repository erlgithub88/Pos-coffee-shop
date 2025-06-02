@extends('layouts.admin-layout')

@section('title', 'Order Detail')

@section('content')
<div class="card shadow-sm rounded">
	<div class="card-header bg-primary text-white d-flex align-items-center">
		<i class="fas fa-receipt fa-lg mr-3"></i>
		<h3 class="card-title mb-0">Detail Order #{{ $order->id }}</h3>
		<div class="ml-auto">
			<a href="{{ route('order.index') }}" class="btn btn-light btn-sm">
				<i class="fas fa-arrow-left mr-1"></i> Kembali
			</a>
		</div>
	</div>

	<div class="card-body">
		<h4 class="mb-4 text-primary"><i class="fas fa-info-circle mr-2"></i>Informasi Order</h4>
		<table class="table table-borderless table-striped mb-4">
			<tbody>
				<tr>
					<th style="width: 150px;"><i class="fas fa-user-cashier mr-2"></i> Kasir</th>
					<td>{{ $order->cashier->name }}</td>
				</tr>
				<tr>
					<th><i class="fas fa-tag mr-2"></i> Diskon</th>
					<td>
						@if ($order->discount_type === 'percentage')
							{{ $order->discount }}%
						@else
							<span class="text-success font-weight-bold">Rp {{ number_format($order->discount, 2, ',', '.') }}</span>
						@endif
					</td>
				</tr>
				<tr>
					<th><i class="fas fa-calendar-alt mr-2"></i> Tanggal</th>
					<td>{{ \Carbon\Carbon::parse($order->date)->format('d M Y, H:i') }}</td>
				</tr>
				<tr>
					<th><i class="fas fa-credit-card mr-2"></i> Metode Bayar</th>
					<td>{{ $order->paymentMethod->method }}</td>
				</tr>
			</tbody>
		</table>

		<h4 class="mb-3 text-primary"><i class="fas fa-box-open mr-2"></i>Detail Item Order</h4>
		<div class="table-responsive">
			<table class="table table-bordered table-hover shadow-sm">
				<thead class="thead-light">
					<tr>
						<th style="width: 50px;">#</th>
						<th>Nama Item</th>
						<th style="width: 130px;">Harga</th>
						<th style="width: 100px;">Jumlah</th>
						<th style="width: 150px;">Subtotal</th>
					</tr>
				</thead>
				<tbody>
					@foreach($order->orderDetails as $detail)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $detail->item->name }}</td>
						<td>Rp {{ number_format($detail->selling_price, 2, ',', '.') }}</td>
						<td>{{ $detail->qty }}</td>
						<td class="font-weight-bold text-success">Rp {{ number_format($detail->selling_price * $detail->qty, 2, ',', '.') }}</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="text-right font-weight-bold" style="font-size: 1.1rem;">Total Bayar:</td>
						<td class="font-weight-bold text-primary" style="font-size: 1.1rem;">
							Rp {{ number_format($order->orderDetails->sum(fn($d) => $d->selling_price * $d->qty), 2, ',', '.') }}
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
@endsection
