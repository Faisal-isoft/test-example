<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function loginPage()
    {
        return view('login');
    }

    /**
     * Handle login submission.
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            return redirect()->route('admin.products');
        }

        return back()->with('error', 'Invalid login credentials');
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
