<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
class ColorController extends Controller
{
    public function index() {
        $colors = Color::orderBy('id', 'asc')->get();
        return view('colors.index', compact('colors'));
    }

    public function create() {
        return view('colors.create');
    }

    public function store(Request $request) {
        $request->validate([
            'color_name' => 'required|unique:colors'
        ]);

        Color::create($request->only('color_name'));

        return redirect()->route('colors.index')->with('success','Color added');
    }

    public function edit(Color $color) {
        return view('colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color) {
        $request->validate([
            'color_name' => 'required|unique:colors,color_name,'.$color->id
        ]);

        $color->update($request->only('color_name'));

        return redirect()->route('colors.index')->with('success','Color updated');
    }

    public function destroy(Color $color) {
        $color->delete();
        return redirect()->route('colors.index')->with('success','Color deleted');
    }
}
