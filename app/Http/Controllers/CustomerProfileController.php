<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        return view('customer.profile', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = auth('customer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // ✅ Image Upload
        if ($request->hasFile('profile_image')) {

            // delete old image
            if ($customer->profile_image && file_exists(public_path('images/'.$customer->profile_image))) {
                unlink(public_path('images/'.$customer->profile_image));
            }

            $imageName = time().'_'.$request->profile_image->getClientOriginalName();
            $request->profile_image->move(public_path('images'), $imageName);

            $customer->profile_image = $imageName;
        }

        $customer->name = $request->name;
        $customer->save();

        return back()->with('success', 'Profile updated successfully');
    }
}
