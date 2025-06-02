<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cashflow;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class ReportController extends Controller
{
	public function transaction(Request $request)
	{
		$period = $request->period ?? 'monthly';
		$year = $request->year ?? now()->year;
		$month = $request->month ?? now()->month;

		$labels = [];
		$data = [];
		$ordersQuery = Order::with(['cashier', 'paymentMethod', 'orderDetails.item']);

		if ($period === 'monthly') {
			$startDate = Carbon::create($year, $month)->startOfMonth();
			$endDate = Carbon::create($year, $month)->endOfMonth();
			$ordersQuery->whereBetween('date', [$startDate, $endDate]);

			for ($i = 1; $i <= $startDate->daysInMonth; $i++) {
				$date = Carbon::create($year, $month, $i);
				$labels[] = $date->format('d');
				$count = Order::whereDate('date', $date)->count();
				$data[] = $count;
			}
		} elseif ($period === 'yearly') {
			$startDate = Carbon::create($year)->startOfYear();
			$endDate = Carbon::create($year)->endOfYear();
			$ordersQuery->whereBetween('date', [$startDate, $endDate]);

			for ($i = 1; $i <= 12; $i++) {
				$monthDate = Carbon::create($year, $i);
				$labels[] = $monthDate->format('M');
				$count = Order::whereYear('date', $year)->whereMonth('date', $i)->count();
				$data[] = $count;
			}
		}

		$orders = $ordersQuery->get();

		$totalProfit = $orders->sum(function ($order) {
			return $order->orderDetails->sum(function ($detail) {
				return ($detail->selling_price - $detail->capital_price) * $detail->qty;
			});
		});

		if ($request->ajax()) {
			$formattedOrders = $orders->map(function ($order) {
				$totalPrice = $order->orderDetails->sum(fn($d) => $d->selling_price * $d->qty);
				$totalProfit = $order->orderDetails->sum(fn($d) => ($d->selling_price - $d->capital_price) * $d->qty);

				return [
					'cashier' => $order->cashier,
					'date' => $order->date,
					'payment_method' => $order->paymentMethod,
					'total_price' => number_format($totalPrice, 2, ',', '.'),
					'total_profit' => number_format($totalProfit, 2, ',', '.'),
				];
			});

			return response()->json([
				'labels' => $labels,
				'data' => $data,
				'orders' => $formattedOrders,
				'total_profit' => number_format($totalProfit, 2, ',', '.'),
			]);
		}

		return view('report.transaction', [
			'orders' => $orders,
			'labels' => $labels,
			'data' => $data,
			'total_profit' => $totalProfit,
		]);
	}

	public function cashflow(Request $request)
	{
		$period = $request->period ?? 'monthly';
		$year = $request->year ?? now()->year;
		$month = $request->month ?? now()->month;

		$labels = [];
		$dataIncome = [];
		$dataExpense = [];

		$cashflowQuery = Cashflow::query();

		if ($period === 'monthly') {
			$startDate = Carbon::create($year, $month)->startOfMonth();
			$endDate = Carbon::create($year, $month)->endOfMonth();
			$cashflowQuery->whereBetween('date', [$startDate, $endDate]);

			for ($i = 1; $i <= $startDate->daysInMonth; $i++) {
				$date = Carbon::create($year, $month, $i);
				$labels[] = $date->format('d');

				$dataIncome[] = Cashflow::whereDate('date', $date)
					->where('type', 'income')
					->sum('nominal');

				$dataExpense[] = Cashflow::whereDate('date', $date)
					->where('type', 'expense')
					->sum('nominal');
			}
		} elseif ($period === 'yearly') {
			$startDate = Carbon::create($year)->startOfYear();
			$endDate = Carbon::create($year)->endOfYear();
			$cashflowQuery->whereBetween('date', [$startDate, $endDate]);

			for ($i = 1; $i <= 12; $i++) {
				$monthDate = Carbon::create($year, $i);
				$labels[] = $monthDate->format('M');

				$dataIncome[] = Cashflow::whereYear('date', $year)
					->whereMonth('date', $i)
					->where('type', 'income')
					->sum('nominal');

				$dataExpense[] = Cashflow::whereYear('date', $year)
					->whereMonth('date', $i)
					->where('type', 'expense')
					->sum('nominal');
			}
		}

		$cashflows = $cashflowQuery->get();
		$totalIncome = $cashflows->where('type', 'income')->sum('nominal');
		$totalExpense = $cashflows->where('type', 'expense')->sum('nominal');

		if ($request->ajax()) {
			return response()->json([
				'labels' => $labels,
				'dataIncome' => $dataIncome,
				'dataExpense' => $dataExpense,
				'cashflows' => $cashflows,
				'totalIncome' => number_format($totalIncome, 2, ',', '.'),
				'totalExpense' => number_format($totalExpense, 2, ',', '.'),
			]);
		}

		return view('report.cashflow', [
			'cashflows' => $cashflows,
			'labels' => $labels,
			'dataIncome' => $dataIncome,
			'dataExpense' => $dataExpense,
			'totalIncome' => number_format($totalIncome, 2, ',', '.'),
			'totalExpense' => number_format($totalExpense, 2, ',', '.'),
		]);
	}

	public function export(Request $request)
	{
		$period = $request->input('period', 'monthly');
		$month = $request->input('month', now()->month);
		$year = $request->input('year', now()->year);

		$orders = Order::with(['cashier', 'paymentMethod', 'orderDetails'])
			->when($period === 'monthly', function ($q) use ($month, $year) {
				return $q->whereMonth('date', $month)->whereYear('date', $year);
			})
			->when($period === 'yearly', function ($q) use ($year) {
				return $q->whereYear('date', $year);
			})
			->get();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Transaction Report');

		// Header
		$sheet->fromArray([['No', 'Cashier', 'Date', 'Payment Method', 'Total Price', 'Total Profit']], null, 'A1');

		// Isi data mulai baris 2
		$row = 2;
		foreach ($orders as $index => $order) {
			$totalPrice = $order->orderDetails->sum(fn($d) => $d->selling_price * $d->qty);
			$totalProfit = $order->orderDetails->sum(fn($d) => ($d->selling_price - $d->capital_price) * $d->qty);

			// Konversi tanggal ke format Excel date
			$excelDate = Date::PHPToExcel(new \DateTime($order->date));

			$sheet->fromArray([
				$index + 1,
				$order->cashier->name,
				$excelDate,
				$order->paymentMethod->method,
				$totalPrice,
				$totalProfit
			], null, "A{$row}");

			$row++;
		}

		// Format tanggal kolom C
		$sheet->getStyle("C2:C" . ($row - 1))->getNumberFormat()
			->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

		// Format rupiah kolom E dan F
		$rupiahFormat = '_("Rp"* #,##0_);_("Rp"* (#,##0);_("Rp"* "-"_);_(@_)';
		$sheet->getStyle("E2:E" . ($row - 1))->getNumberFormat()->setFormatCode($rupiahFormat);
		$sheet->getStyle("F2:F" . ($row - 1))->getNumberFormat()->setFormatCode($rupiahFormat);

		// Optional: auto width kolom supaya rapi
		foreach (range('A', 'F') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// Download file
		$writer = new Xlsx($spreadsheet);
		$fileName = 'transaction_report_' . now()->format('Ymd_His') . '.xlsx';
		$tempFile = tempnam(sys_get_temp_dir(), $fileName);
		$writer->save($tempFile);

		return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
	}


	public function exportPdf(Request $request)
	{
		$period = $request->input('period', 'monthly');
		$month = $request->input('month', now()->month);
		$year = $request->input('year', now()->year);

		$orders = Order::with(['cashier', 'paymentMethod', 'orderDetails'])
			->when($period === 'monthly', function ($q) use ($month, $year) {
				return $q->whereMonth('date', $month)->whereYear('date', $year);
			})
			->when($period === 'yearly', function ($q) use ($year) {
				return $q->whereYear('date', $year);
			})
			->get();

		$totalProfitSum = $orders->sum(function ($order) {
			return $order->orderDetails->sum(function ($detail) {
				return ($detail->selling_price - $detail->capital_price) * $detail->qty;
			});
		});

		$pdf = PDF::loadView('report.pdf_transaction', [
			'orders' => $orders,
			'period' => $period,
			'month' => $period === 'monthly' ? \Carbon\Carbon::create($year, $month)->format('F') : '',
			'year' => $year,
			'totalProfitSum' => $totalProfitSum,
		]);

		$fileName = 'transaction_report_' . now()->format('Ymd_His') . '.pdf';

		return $pdf->download($fileName);
	}
}
