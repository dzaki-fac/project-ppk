<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Normalisasi dulu agar rule `lowercase` tidak menolak input kapital
        // sekaligus menutup celah bypass unique/login via perbedaan case.
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $credentials = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower(trim($credentials['email']));
        $throttleKey = Str::transliterate(Str::lower($email).'|'.$request->ip());

        // Brute-force protection: maksimal 5 percobaan per menit per email+IP.
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak percobaan login. Coba lagi dalam '.$seconds.' detik.',
            ]);
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt(['email' => $email, 'password' => $credentials['password']], $remember)) {
            RateLimiter::hit($throttleKey);

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        // Cegah session fixation.
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            // Normalisasi agar unique tidak bisa diakali perbedaan huruf besar/kecil.
            'email' => Str::lower(trim($validated['email'])),
            // Hash eksplisit. Aman dari double-hash karena cast `hashed`
            // mengecek Hash::isHashed() sebelum hashing ulang.
            'password' => Hash::make($validated['password']),
            // Role dikunci: input `role` dari request SELALU diabaikan (anti-eskalasi).
            'role' => 'user',
        ]);

        Auth::login($user);
        // Cegah session fixation setelah autentikasi.
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
