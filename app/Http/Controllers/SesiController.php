<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email Wajib di Isi!',
            'password.required' => 'Password Wajib di Isi!',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->role == 'user') {
                return redirect('admin/user');
            } elseif (Auth::user()->role == 'admin') {
                return redirect('admin/admin');
            } elseif (Auth::user()->role == 'operator') {
                return redirect('admin/operator');
            }
        } else {
            return redirect('')->withErrors('Username atau password tidak sesuai')->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }

    // Tambahkan method untuk menampilkan halaman registrasi
    public function registerView()
    {
        return view('register');
    }

    // Tambahkan method untuk proses registrasi
    public function register(Request $request)
    {
        // Validasi data yang diterima dari formulir registrasi
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat pengguna baru
        $user = \App\Models\User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 'user', // Default role saat registrasi
        ]);

        // Redirect atau berikan respons sesuai kebutuhan aplikasi Anda
        return redirect()->route('login');
    }
}
