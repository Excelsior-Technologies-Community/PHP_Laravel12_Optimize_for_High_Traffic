<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index() {
      
        $categories = Category::orderBy('id', 'asc')->get();
        
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'category_name' => 'required|unique:categories'
        ]);

        Category::create($request->only('category_name'));

        return redirect()->route('categories.index')->with('success','Category added');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name,'.$category->id
        ]);

        $category->update($request->only('category_name'));

        return redirect()->route('categories.index')->with('success','Category updated');
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted');
    }
}

