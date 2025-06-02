<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cashflow;

class DashboardController extends Controller
{
	public function __invoke()
	{
		$user = Auth::user();

		if (!$user) {
			return redirect()->to('login');
		}

		switch ($user->role) {
			case 'owner':
				$data = $this->getOwnerDashboardData();
				break;

			case 'manager':
				$data = $this->getManagerDashboardData();
				break;

			case 'cashier':
				$data = $this->getCashierDashboardData();
				break;

			default:
				$data = [];
				break;
		}

		return view('dashboard.' . $user->role, $data);
	}

	private function getOwnerDashboardData()
	{
		$now = Carbon::now();
		$month = $now->month;
		$year = $now->year;

		$totalOrdersThisMonth = Order::whereMonth('date', $month)->whereYear('date', $year)->count();

		$totalItemsSoldThisMonth = OrderDetail::whereHas('order', function ($query) use ($month, $year) {
			$query->whereMonth('date', $month)->whereYear('date', $year);
		})->sum('qty');

		$totalRevenueThisMonth = OrderDetail::whereHas('order', function ($query) use ($month, $year) {
			$query->whereMonth('date', $month)->whereYear('date', $year);
		})->sum(DB::raw('qty * selling_price'));

		$totalExpensesThisMonth = OrderDetail::whereHas('order', function ($query) use ($month, $year) {
			$query->whereMonth('date', $month)->whereYear('date', $year);
		})->sum(DB::raw('qty * capital_price'));

		$cashflowRevenueThisMonth = Cashflow::where('type', 'income')
			->whereMonth('date', $month)
			->whereYear('date', $year)
			->sum('nominal');

		$cashflowExpensesThisMonth = Cashflow::where('type', 'expense')
			->whereMonth('date', $month)
			->whereYear('date', $year)
			->sum('nominal');

		// Penjualan Mingguan (7 hari terakhir)
		$weekly = collect();
		for ($i = 6; $i >= 0; $i--) {
			$day = Carbon::now()->subDays($i)->format('Y-m-d');
			$label = Carbon::now()->subDays($i)->isoFormat('ddd'); // e.g. "Sen", "Sel", "Rab"

			$value = Order::whereDate('date', $day)->count();

			$weekly->push(['label' => $label, 'value' => $value]);
		}

		// Penjualan Bulanan (12 bulan terakhir)
		$monthly = collect();
		for ($i = 11; $i >= 0; $i--) {
			$date = Carbon::now()->subMonths($i);
			$label = $date->isoFormat('MMM'); // e.g. "Jan", "Feb"
			$value = Order::whereMonth('date', $date->month)->whereYear('date', $date->year)->count();

			$monthly->push(['label' => $label, 'value' => $value]);
		}

		return [
			'totalOrdersThisMonth' => $totalOrdersThisMonth,
			'totalItemsSoldThisMonth' => $totalItemsSoldThisMonth,
			'totalRevenueThisMonth' => $totalRevenueThisMonth,
			'totalExpensesThisMonth' => $totalExpensesThisMonth,
			'cashflowRevenueThisMonth' => $cashflowRevenueThisMonth,
			'cashflowExpensesThisMonth' => $cashflowExpensesThisMonth,
			'weeklySales' => [
				'days' => $weekly->pluck('label'),
				'values' => $weekly->pluck('value'),
			],
			'monthlySales' => [
				'months' => $monthly->pluck('label'),
				'values' => $monthly->pluck('value'),
			],
		];
	}

	private function getManagerDashboardData()
	{
		$month = Carbon::now()->month;
		$year = Carbon::now()->year;

		$totalOrdersThisMonth = Order::whereMonth('date', $month)
			->whereYear('date', $year)
			->count();

		$totalRevenueThisMonth = OrderDetail::whereHas('order', function ($query) use ($month, $year) {
			$query->whereMonth('date', $month)
				->whereYear('date', $year);
		})->sum(DB::raw('qty * selling_price'));

		$cashflowRevenueThisMonth = Cashflow::where('type', 'income')
			->whereMonth('date', $month)
			->whereYear('date', $year)
			->sum('nominal');

		$cashflowExpensesThisMonth = Cashflow::where('type', 'expense')
			->whereMonth('date', $month)
			->whereYear('date', $year)
			->sum('nominal');

		return [
			'totalOrdersThisMonth' => $totalOrdersThisMonth,
			'totalRevenueThisMonth' => $totalRevenueThisMonth,
			'cashflowRevenueThisMonth' => $cashflowRevenueThisMonth,
			'cashflowExpensesThisMonth' => $cashflowExpensesThisMonth,
		];
	}

	private function getCashierDashboardData()
	{
		$totalOrdersToday = Order::whereDate('date', Carbon::today())->count();

		$totalRevenueToday = OrderDetail::whereHas('order', function ($query) {
			$query->whereDate('date', Carbon::today());
		})->sum(DB::raw('qty * selling_price'));

		return [
			'totalOrdersToday' => $totalOrdersToday,
			'totalRevenueToday' => $totalRevenueToday,
		];
	}
}
