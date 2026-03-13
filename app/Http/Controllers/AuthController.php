<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Seed default categories for new user
        $defaultCategories = [
            ['name' => 'Gaji', 'type' => 'income', 'icon' => '💼', 'color' => '#10b981'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#06b6d4'],
            ['name' => 'Investasi', 'type' => 'income', 'icon' => '📈', 'color' => '#8b5cf6'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => '🎁', 'color' => '#f59e0b'],
            ['name' => 'Makanan', 'type' => 'expense', 'icon' => '🍜', 'color' => '#ef4444'],
            ['name' => 'Transport', 'type' => 'expense', 'icon' => '🚗', 'color' => '#f97316'],
            ['name' => 'Belanja', 'type' => 'expense', 'icon' => '🛍️', 'color' => '#ec4899'],
            ['name' => 'Hiburan', 'type' => 'expense', 'icon' => '🎮', 'color' => '#6366f1'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => '💊', 'color' => '#14b8a6'],
            ['name' => 'Tagihan', 'type' => 'expense', 'icon' => '📄', 'color' => '#64748b'],
        ];

        foreach ($defaultCategories as $cat) {
            Category::create(['user_id' => $user->id, ...$cat]);
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
