<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function login()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login submission.
     */
    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            // Check if user is admin
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Logged in as Admin successfully.');
            }

            // If authenticated but not admin, log out immediately
            Auth::logout();
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'You do not have administrative access.']);
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have logged out successfully.');
    }
}
