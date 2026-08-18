<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->orderBy('id', 'desc')->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'image'       => 'required',
            'link'        => 'nullable|url',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $image = $request->image;

        // If file uploaded, save it; else use URL directly
        if ($request->hasFile('image_file')) {
            $request->validate(['image_file' => 'image|max:2048']);
            $filename = time() . '.' . $request->image_file->extension();
            $request->image_file->move(public_path('images/banners'), $filename);
            $image = $filename;
        }

        Banner::create([
            'title'       => $request->title,
            'image'       => $image,
            'link'        => $request->link,
            'description' => $request->description,
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner added successfully');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'link'        => 'nullable|url',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $image = $banner->image;

        if ($request->hasFile('image_file')) {
            $request->validate(['image_file' => 'image|max:2048']);
            $filename = time() . '.' . $request->image_file->extension();
            $request->image_file->move(public_path('images/banners'), $filename);
            $image = $filename;
        } elseif ($request->filled('image')) {
            $image = $request->image;
        }

        $banner->update([
            'title'       => $request->title,
            'image'       => $image,
            'link'        => $request->link,
            'description' => $request->description,
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner updated successfully');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Banner deleted');
    }
}
