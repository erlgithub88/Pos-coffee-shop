<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\Cashier;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$orders = Order::with(['cashier', 'paymentMethod', 'orderDetails']);

			return DataTables::eloquent($orders)
				->addIndexColumn()
				->addColumn('cashier_name', function ($order) {
					return $order->cashier?->name ?? '-';
				})
				->addColumn('total_price', function ($order) {
					return $order->orderDetails->sum(function ($detail) {
						return $detail->selling_price * $detail->qty;
					});
				})
				->addColumn('total_items', function ($order) {
					return $order->orderDetails->sum('qty');
				})
				->addColumn('payment_method_name', function ($order) {
					return $order->paymentMethod?->method ?? '-';
				})
				->addColumn('action', function ($order) {
					if (Auth::user()->role != 'cashier') return '-';

					return '
                    <a href="' . route('order.show', $order->id) . '" class="btn btn-sm btn-info action-btn" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . route('order.print', $order->id) . '" target="_blank" class="btn btn-sm btn-secondary action-btn" title="Cetak Struk">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="confirmDelete(' . $order->id . ')" class="btn btn-sm btn-danger action-btn" title="Hapus">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <form id="delete-order-' . $order->id . '" action="' . route('order.destroy', $order->id) . '" method="POST" style="display:none;">
                        ' . csrf_field() . method_field('DELETE') . '
                    </form>
                ';
				})
				->rawColumns(['action'])
				->make(true);
		}

		return view('order.index');
	}

	public function create()
	{
		$paymentMethods = PaymentMethod::all();
		return view('order.create', ['paymentMethods' => $paymentMethods]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'item_id' => 'required|array',
			'item_id.*' => 'exists:items,id',
			'discount' => 'required|numeric|min:0',
			'discount_type' => 'required|in:percentage,amount',
			'payment_method_id' => 'required|exists:payment_methods,id',
		]);

		DB::beginTransaction();

		try {
			$order = Order::create([
				'cashier_id' => Auth::user()->cashier->id,
				'discount' => $validated['discount'],
				'discount_type' => $validated['discount_type'],
				'date' => now(),
				'payment_method_id' => $validated['payment_method_id'],
			]);

			foreach ($validated['item_id'] as $itemId) {
				$qty = 1;
				$item = Item::find($itemId);
				if ($item) {
					OrderDetail::create([
						'order_id' => $order->id,
						'item_id' => $itemId,
						'capital_price' => $item->capital_price,
						'selling_price' => $item->selling_price,
						'qty' => $qty,
					]);

					$item->update([
						'qty' => $item->qty - $qty,
					]);
				}
			}

			DB::commit();
			return redirect()->route('order.index')->with('success', 'Order created .');
		} catch (\Exception $e) {
			DB::rollback();
			return redirect()->back()->with('error', 'Failed to create order. ' . $e->getMessage());
		}
	}

	public function show(Order $order)
	{
		$order->load(['cashier', 'paymentMethod', 'orderDetails.item']); // Eager load relations
		return view('order.detail', ['order' => $order]);
	}

	public function edit(Order $order)
	{
		$cashiers = Cashier::all();
		$paymentMethods = PaymentMethod::all();
		return view('order.edit', ['order' => $order, 'cashiers' => $cashiers, 'paymentMethods' => $paymentMethods]);
	}

	public function update(Request $request, Order $order)
	{
		$validated = $request->validate([
			'cashier_id' => ['required', 'exists:cashiers,id'],
			'order_number' => ['required', 'unique:orders,order_number,' . $order->id],
			'discount' => ['nullable', 'numeric'],
			'discount_type' => ['nullable', 'in:flat,percentage'],
			'date' => ['required', 'date'],
			'status' => ['required', 'in:pending,preparing,completed,cancelled'],
			'payment_method_id' => ['required', 'exists:payment_methods,id'],
		]);

		$order->update($validated);

		return redirect()->route('order.index')->with('success', 'Order updated.');
	}

	public function destroy(Order $order)
	{
		$order->delete();

		return redirect()->route('order.index')->with('success', 'Order deleted.');
	}

	public function print($id)
	{
		$order = Order::with(['cashier', 'paymentMethod', 'orderDetails.item'])->findOrFail($id);
		return view('order.print', compact('order'));
	}


	
}
