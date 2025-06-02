<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemSupply;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ItemSupplyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $itemSupplies = ItemSupply::with(['manager', 'item']);

            return DataTables::eloquent($itemSupplies)
                ->addIndexColumn()
                ->addColumn('manager_name', function($itemSupply) {
                  return $itemSupply->manager->name;
                })
                ->addColumn('item_name', function($itemSupply) {
                  return $itemSupply->item->name;
                })
                // ->addColumn('action', function ($itemSupply) {
                //     return '
                //         <a href="' . route('item-supply.edit', $itemSupply->id) . '" class="btn btn-primary btn-sm">Edit</a>
                //         <form id="delete-form-' . $itemSupply->id . '" action="' . route('item-supply.destroy', $itemSupply->id) . '" method="POST" style="display: inline-block;">
                //             ' . csrf_field() . '
                //             ' . method_field('DELETE') . '
                //             <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $itemSupply->id . ')">Delete</button>
                //         </form>
                //     ';
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }

        return view('item-supply.index');
    }

    public function create()
    {
        $items = Item::all();
        return view('item-supply.create', ['items' => $items]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'qty' => ['required', 'numeric', 'min:1'],
        ]);

        $validated['manager_id'] = Auth::user()->manager->id;
        $validated['date'] = now();

        DB::transaction(function () use ($validated) {
            ItemSupply::create($validated);

            $item = Item::find($validated['item_id']);
            $item->update([
                'qty' => $item->qty + $validated['qty'],
            ]);
        });

        return redirect()->route('item-supply.index')->with('success', 'Item supply added successfully.');
    }

    // public function edit(ItemSupply $itemSupply)
    // {
    //     $items = Item::all();
    //     return view('item-supply.edit', compact('itemSupply', 'items'));
    // }

    // public function update(Request $request, ItemSupply $itemSupply)
    // {
    //     $validated = $request->validate([
    //         'item_id' => ['required', 'exists:items,id'],
    //         'qty' => ['required', 'numeric', 'min:1'],
    //     ]);

    //     $itemSupply->update($validated);

    //     return redirect()->route('item-supply.index')->with('success', 'Item supply updated successfully.');
    // }

    // public function destroy(ItemSupply $itemSupply)
    // {
    //     $itemSupply->delete();
    //     return redirect()->route('item-supply.index')->with('success', 'Item supply deleted successfully.');
    // }
}