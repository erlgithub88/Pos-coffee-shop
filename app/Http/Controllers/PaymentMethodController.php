<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$paymentMethods = PaymentMethod::query();

			return DataTables::eloquent($paymentMethods)
				->addIndexColumn()
				->addColumn('action', function ($paymentMethod) {
					return '
						<a href="' . route('payment-method.edit', ['paymentMethod' => $paymentMethod->id]) . '" class="btn btn-primary btn-sm">Edit</a>
						<form id="delete-payment-method-' . $paymentMethod->id . '" action="' . route('payment-method.destroy', ['paymentMethod' => $paymentMethod->id]) . '" method="POST" style="display: inline-block;">
								' . csrf_field() . '
								' . method_field('DELETE') . '
								<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $paymentMethod->id . ')">Delete</button>
						</form>
					';
				})
				->rawColumns(['action'])
				->make(true);
		}

		return view('payment-method.index');
	}

	public function create()
	{
		return view('payment-method.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'method' => ['required', 'string', 'max:50'],
		]);

		PaymentMethod::create($validated);

		return redirect()->route('payment-method.index')->with('success', 'Payment method created.');
	}

	public function edit(PaymentMethod $paymentMethod)
	{
		return view('payment-method.edit', ['paymentMethod' => $paymentMethod]);
	}

	public function update(Request $request, PaymentMethod $paymentMethod)
	{
		$validated = $request->validate([
			'method' => ['required', 'string', 'max:50'],
		]);

		$paymentMethod->update($validated);

		return redirect()->route('payment-method.index')->with('success', 'Payment method updated.');
	}

	public function destroy(PaymentMethod $paymentMethod)
	{
		$paymentMethod->delete();

		return redirect()->route('payment-method.index')->with('success', 'Payment method deleted.');
	}
}