<?php

namespace App\Http\Controllers;

// panggil package bawaan laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// panggil model
use App\Models\User;


class AuthController extends Controller
{
    // Menampilkan form login
    public function index()
    {
        $data = [
            'title' => 'Halaman Login'
        ];
        return view('pages.auth.login', $data);
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password harus minimal 6 karakter.',
        ]);

        // Ambil email dan password
        $credentials = $request->only('email', 'password');

        // Autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();  // Regenerasi sesi untuk keamanan

            // custom set session (jika dibutuhkan saja, kalau defaultnya bawaan kolom dari tabel users)
            session([
                'nama_user' => Auth::user()->name,
                'detail' => [
                    'email' => Auth::user()->email,
                ],
            ]);

            return redirect()->intended(route('dashboard'));
        }

        // Jika gagal, kembali ke halaman login dengan pesan kesalahan
        return back()->withErrors([
            'errors' => 'Email atau password salah.',
        ]);
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();  // Hapus autentikasi pengguna
        $request->session()->invalidate();  // Hapus sesi
        $request->session()->regenerateToken();  // Regenerasi CSRF token
        return redirect()->route('login');
    }
}
