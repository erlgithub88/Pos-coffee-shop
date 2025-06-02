<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubmissionController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$submissions = Submission::with('member')->get();

			return DataTables::of($submissions)
				->addIndexColumn()
				->addColumn('action', function ($submission) {

					if (!in_array(Auth::user()->role, ['cashier', 'manager'])) {
						if ($submission->status == 'pending') {
							if (Auth::user()->role == 'owner') {
								return '
								<form action="' . route('submission.accept', ['submission' => $submission->id]) . '" method="GET" style="display: inline-block;">
										' . csrf_field() . '
										<button type="submit" class="btn btn-primary btn-sm">Accept</button>
								</form>
								<form action="' . route('submission.decline', ['submission' => $submission->id]) . '" method="GET" style="display: inline-block;">
										' . csrf_field() . '
										<button type="submit" class="btn btn-danger btn-sm">Decline</button>
								</form>';
							}
	
							return '<a href="' . route('submission.edit', ['submission' => $submission->id]) . '" class="btn btn-primary btn-sm">Edit</a>
							<form id="delete-submission-' . $submission->id . '" action="' . route('submission.destroy', ['submission' => $submission->id]) . '" method="POST" style="display: inline-block;">
									' . csrf_field() . '
									' . method_field('DELETE') . '
									<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $submission->id . ')">Delete</button>
							</form>';
						}
					}
					

					return '-';
				})
				->addColumn('member_name', function ($submission) {
					return $submission->member->name;
				})
				->rawColumns(['action'])
				->make(true);
		}

		return view('submission.index');
	}

	public function create()
	{
		$members = Member::all();
		return view('submission.create', ['members' => $members]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'nama_barang' => ['required', 'string', 'max:100'],
		]);

		$validated['member_id'] = Auth::user()->member->id;
		$validated['tanggal'] = now();
		$validated['status'] = 'pending';

		Submission::create($validated);

		return redirect()->route('submission.index')->with('success', 'submission created.');
	}

	public function edit(submission $submission)
	{
		return view('submission.edit', ['submission' => $submission]);
	}

	public function update(Request $request, submission $submission)
	{
		$validated = $request->validate([
			'nama_barang' => ['nullable', 'string', 'max:100'],
			'status' => ['nullable', 'in:pending,accepted,declined'],
		]);

		$submission->update($validated);

		return redirect()->route('submission.index')->with('success', 'submission updated.');
	}

	public function destroy(submission $submission)
	{
		$submission->delete();

		return redirect()->route('submission.index')->with('success', 'submission deleted.');
	}

	public function accept(submission $submission)
	{
		$submission->update(['status' => 'accepted']);

		return redirect()->route('submission.index')->with('success', 'submission accepted.');
	}

	public function decline(submission $submission)
	{
		$submission->update(['status' => 'declined']);

		return redirect()->route('submission.index')->with('success', 'submission decline.');
	}
}