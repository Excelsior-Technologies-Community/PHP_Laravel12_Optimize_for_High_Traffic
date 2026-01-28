<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    // Register page
    public function register()
    {
        return view('customer.register');
    }

    // Register store
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')
            ->with('success', 'Register successful');
    }

    // Login page
    public function login()
    {
        return view('customer.login');
    }

    // ✅ LOGIN POST (UPDATED)
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {

            //  Login ke baad DIRECT products page
            return redirect()->route('customer.products');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ]);
    }

    // (Optional) Dashboard – ab use nahi hoga
    public function dashboard()
    {
        return view('customer.dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }
}
