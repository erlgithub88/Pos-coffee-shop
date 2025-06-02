<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use App\Models\Manager;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;
use Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$users = User
				::with(['owner', 'manager', 'cashier'])
				->where('role', '!=', 'member');

			return DataTables::eloquent($users)
				->addIndexColumn()
				->addColumn('name', function ($user) {
					switch ($user->role) {
						case 'owner':
							return $user->owner->name ?? 'N/A';
						case 'manager':
							return $user->manager->name ?? 'N/A';
						case 'cashier':
							return $user->cashier->name ?? 'N/A';
						default:
							return 'N/A';
					}
				})
				->addColumn('action', function ($user) {
					return '
											<a href="' . route('user.edit', ['user' => $user->id]) . '" class="btn btn-primary btn-sm">Edit</a>
											<form id="delete-user-' . $user->id . '" action="' . route('user.destroy', ['user' => $user->id]) . '" method="POST" style="display: inline-block;">
													' . csrf_field() . '
													' . method_field('DELETE') . '
													<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $user->id . ')">Delete</button>
											</form>
									';
				})
				->rawColumns(['action'])
				->make(true);
		}

		return view('user.index');
	}

	public function create()
	{
		return view('user.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'], // Name sekarang disimpan di tabel terkait
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8'],
			'role' => ['required', 'in:owner,manager,cashier'],
			'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
			'phone_number' => ['nullable', 'string', 'max:20'],
			'address' => ['nullable', 'string', 'max:255'],
			'monthly_wage' => ['nullable', 'numeric', 'min:0'],
		]);

		$validated['password'] = bcrypt($request->password);

		if ($request->hasFile('image')) {
			$imagePath = $request->file('image')->store('images/users', 'public');
			$validated['image_path'] = $imagePath;
		}

		$user = User::create($validated);

		switch ($request->role) {
			case 'owner':
				Owner::create([
					'user_id' => $user->id,
					'name' => $request->name,
					'phone_number' => $request->phone_number,
					'address' => $request->address,
					'monthly_wage' => $request->monthly_wage,
				]);
				break;

			case 'manager':
				Manager::create([
					'user_id' => $user->id,
					'name' => $request->name,
					'phone_number' => $request->phone_number,
					'address' => $request->address,
					'monthly_wage' => $request->monthly_wage,
				]);
				break;

			case 'cashier':
				Cashier::create([
					'user_id' => $user->id,
					'name' => $request->name,
					'phone_number' => $request->phone_number,
					'address' => $request->address,
					'monthly_wage' => $request->monthly_wage,
				]);
				break;
		}

		return redirect()->route('user.index')->with('success', 'User created.');
	}

	public function edit(User $user)
	{
		return view('user.edit', ['user' => $user]);
	}

	public function update(Request $request, User $user)
	{
		$validated = $request->validate([
			'name' => ['nullable', 'string', 'max:255'], // Name sekarang disimpan di tabel terkait
			'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
			'password' => ['nullable', 'string', 'min:8'],
			'role' => ['nullable', 'in:owner,manager,cashier'],
			'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
			'phone_number' => ['nullable', 'string', 'max:20'],
			'address' => ['nullable', 'string', 'max:255'],
			'monthly_wage' => ['nullable', 'numeric', 'min:0'],
		]);

		if ($request->filled('password')) {
			$validated['password'] = bcrypt($request->password);
		} else {
			unset($validated['password']);
		}

		if ($request->hasFile('image')) {
			if ($user->image_path) {
				Storage::disk('public')->delete($user->image_path);
			}

			$imagePath = $request->file('image')->store('images/users', 'public');
			$validated['image_path'] = $imagePath;
		}

		$user->update($validated);

		switch ($user->role) {
			case 'owner':
				$owner = Owner::where('user_id', $user->id)->first();
				if ($owner) {
					$owner->update([
						'name' => $request->name,
						'phone_number' => $request->phone_number,
						'address' => $request->address,
						'monthly_wage' => $request->monthly_wage,
					]);
				}
				break;

			case 'manager':
				$manager = Manager::where('user_id', $user->id)->first();
				if ($manager) {
					$manager->update([
						'name' => $request->name,
						'phone_number' => $request->phone_number,
						'address' => $request->address,
						'monthly_wage' => $request->monthly_wage,
					]);
				}
				break;

			case 'cashier':
				$cashier = Cashier::where('user_id', $user->id)->first();
				if ($cashier) {
					$cashier->update([
						'name' => $request->name,
						'phone_number' => $request->phone_number,
						'address' => $request->address,
						'monthly_wage' => $request->monthly_wage,
					]);
				}
				break;
		}

		return redirect()->route('user.index')->with('success', 'User updated.');
	}

	public function destroy(User $user)
	{
		if ($user->image_path) {
			Storage::disk('public')->delete($user->image_path);
		}

		$user->delete();

		return redirect()->route('user.index')->with('success', 'User deleted.');
	}
}
