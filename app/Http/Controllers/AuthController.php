<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Determine login intent: 'admin' or 'user' (default 'user')
        $intent = $request->input('intent', 'user');

        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check if user is active
            if ($user->is_active != 1) {
                return back()->with('error', 'Your account is not active.');
            }

            // Enforce intent-based role restriction before authenticating
            if ($intent === 'admin' && $user->role_id != 1) {
                return back()->with('error', 'Unauthorized: only admin accounts can log in here.');
            }

            if ($intent === 'user' && $user->role_id == 1) {
                return back()->with('error', 'Unauthorized: admin accounts cannot log in from user pages.');
            }

            // Attempt login
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                // Redirect berdasarkan role: role_id 1 = admin, role_id 2 = user
                if ($user->role_id == 1) {
                    return redirect()->route('admin.dashboard');
                } else {
                    // redirect regular users to the new Trade page after login
                    return redirect()->route('user.trade');
                }
            }
        }

        return back()->with('error', 'Login failed. Please check your email and password');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'alamat.required' => 'Address is required.',
            'alamat.string' => 'Address must be a string.',
            'alamat.max' => 'Address may not be greater than 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'Email is already registered.',
            'email.max' => 'Email may not be greater than 255 characters.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Rename 'name' ke 'nama' sesuai database schema
        $validated['nama'] = $validated['name'];
        unset($validated['name']);

        // Hash password before saving
        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = 1; // Default active
        $validated['role_id'] = 2; // Default role user biasa

        User::create($validated);

        return redirect('/login')->with('success', 'Registration successful. Please log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    }
}
