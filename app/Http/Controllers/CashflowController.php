<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CashflowController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $cashflows = Cashflow::with('manager');

      return DataTables::eloquent($cashflows)
        ->addIndexColumn()
        ->addColumn('manager_name', function($cashflow) {
          return $cashflow->manager->name;
        })
        ->addColumn('action', function ($cashflow) {
					if (Auth::user()->role != 'manager') return '-';

          return '
            <a href="' . route('cashflow.edit', ['cashflow' => $cashflow->id]) . '" class="btn btn-primary btn-sm">Edit</a>
            <form id="delete-form-' . $cashflow->id . '" action="' . route('cashflow.destroy', ['cashflow' => $cashflow->id]) . '" method="POST" style="display: inline-block;">
                ' . csrf_field() . '
                ' . method_field('DELETE') . '
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $cashflow->id . ')">Delete</button>
            </form>
          ';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    return view('cashflow.index');
  }

  public function create(Request $request)
  {
    return view('cashflow.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => ['required', 'string', 'max:50'],
      'desc' => ['required', 'string'],
      'nominal' => ['required', 'numeric'],
      'type' => ['required', 'in:income,expense'],
      'date' => ['required', 'date'],
    ]);

    $validated['user_id'] = Auth::id();

    Cashflow::create($validated);

    return redirect()->route('cashflow.index')->with('success', 'Cashflow created.');
  }

  public function edit(Cashflow $cashflow)
  {
    if ($cashflow->manager_id !== Auth::user()->manager->id) {
      return redirect()->route('cashflow.index')->with('error', 'You are not authorized to edit this cashflow.');
    }

    return view('cashflow.edit', ['cashflow' => $cashflow]);
  }

  public function update(Request $request, Cashflow $cashflow)
  {
    if ($cashflow->manager_id !== Auth::user()->manager->id) {
      return redirect()->route('cashflow.index')->with('error', 'You are not authorized to update this cashflow.');
    }

    $validated = $request->validate([
      'title' => ['required', 'string', 'max:50'],
      'desc' => ['required', 'string'],
      'nominal' => ['required', 'numeric'],
      'type' => ['required', 'in:income,expense'],
      'date' => ['required', 'date'],
    ]);

    $cashflow->update($validated);

    return redirect()->route('cashflow.index')->with('success', 'Cashflow updated.');
  }

  public function destroy(Cashflow $cashflow)
  {
    if ($cashflow->manager_id !== Auth::user()->manager->id) {
      return redirect()->route('cashflow.index')->with('error', 'You are not authorized to delete this cashflow.');
    }

    $cashflow->delete();

    return redirect()->route('cashflow.index')->with('success', 'Cashflow deleted.');
  }
}