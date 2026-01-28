<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // LIST
    public function index()
    {
        $sizes = Size::orderBy('id', 'asc')->get();
        return view('sizes.index', compact('sizes'));
    }

    // CREATE FORM
    public function create()
    {
        return view('sizes.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name'
        ]);

        Size::create([
            'size_name' => $request->size_name
        ]);

        return redirect()->route('sizes.index')
                         ->with('success', 'Size added successfully');
    }

    // EDIT FORM
    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    // UPDATE
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name,' . $size->id
        ]);

        $size->update([
            'size_name' => $request->size_name
        ]);

        return redirect()->route('sizes.index')
                         ->with('success', 'Size updated successfully');
    }

    // DELETE
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('sizes.index')
                         ->with('success', 'Size deleted successfully');
    }
}

