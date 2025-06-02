<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$members = Member::with('user')->select('members.*');

			return DataTables::eloquent($members)
				->addIndexColumn()
				->addColumn('email', fn($member) => $member->user->email)
				->addColumn('action', function ($member) {
					return '
						<a href="' . route('member.edit', $member->id) . '" class="btn btn-primary btn-sm">Edit</a>
						<form id="delete-member-' . $member->id . '" action="' . route('member.destroy', $member->id) . '" method="POST" style="display:inline-block;">
							' . csrf_field() . method_field('DELETE') . '
							<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $member->id . ')">Delete</button>
						</form>
					';
				})
				->rawColumns(['action'])
				->make(true);
		}

		return view('member.index');
	}

	// ✅ Dashboard untuk member login
	public function dashboard()
	{
		$user = Auth::user();
		$member = $user->member;
	
		if (!$member) {
			return redirect()->route('member.index')->with('error', 'Member data tidak ditemukan.');
		}
	
		// Total transaksi oleh member ini
		$totalTransactions = Order::where('member_id', $member->id)->count();
	
		// Total poin dari member (pastikan field `points` ada di tabel members)
		$totalPoints = $member->points ?? 0;
	
		// Total produk yang dibeli (jumlah quantity dari detail order)
		$totalProductsBought = DB::table('order_details')
			->join('orders', 'order_details.order_id', '=', 'orders.id')
			->where('orders.member_id', $member->id)
			->sum('order_details.quantity');
	
		// Riwayat order terakhir (misal ambil 5 terbaru)
		$recentOrders = $member->orders()
			->with('details') // jika ingin menampilkan produk nanti
			->orderBy('created_at', 'desc')
			->take(5)
			->get();
	
		return view('member.dashboard', compact(
			'member',
			'totalTransactions',
			'totalPoints',
			'totalProductsBought',
			'recentOrders'
		));
	}

	
		public function create() { return view('member.create'); }

	public function store(Request $request)
	{
		$validated = $request->validate([
			'email' => ['required', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'min:8'],
			'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
			'name' => ['required', 'max:100'],
			'phone_number' => ['required', 'max:20'],
		]);

		try {
			DB::beginTransaction();

			$user = User::create([
				'email' => $validated['email'],
				'password' => Hash::make($validated['password']),
				'role' => 'member',
				'image_path' => $request->file('image')?->store('images/users', 'public'),
			]);

			Member::create([
				'user_id' => $user->id,
				'name' => $validated['name'],
				'phone_number' => $validated['phone_number'],
			]);

			DB::commit();

			return redirect()->route('member.index')->with('success', 'Member created.');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('error', 'Failed to create member. ' . $e->getMessage());
		}
	}

	public function edit(Member $member)
	{
		return view('member.edit', compact('member'));
	}

	public function update(Request $request, Member $member)
	{
		$validated = $request->validate([
			'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $member->user->id],
			'password' => ['nullable', 'min:8'],
			'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
			'name' => ['required', 'max:100'],
			'phone_number' => ['required', 'max:20'],
		]);

		$userData = ['email' => $validated['email']];

		if ($request->filled('password')) {
			$userData['password'] = Hash::make($validated['password']);
		}

		if ($request->hasFile('image')) {
			$userData['image_path'] = $request->file('image')->store('images/users', 'public');
		}

		try {
			DB::beginTransaction();

			$member->user->update($userData);

			$member->update([
				'name' => $validated['name'],
				'phone_number' => $validated['phone_number'],
			]);

			DB::commit();

			return redirect()->route('member.index')->with('success', 'Member updated.');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('error', 'Failed to update member. ' . $e->getMessage());
		}
	}

	public function destroy(Member $member)
	{
		try {
			DB::beginTransaction();
			$member->user()->delete();
			$member->delete();
			DB::commit();

			return redirect()->route('member.index')->with('success', 'Member deleted.');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->with('error', 'Failed to delete member. ' . $e->getMessage());
		}
	}
}
