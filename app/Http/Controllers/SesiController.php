<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Models\User; // Tambahkan use statement

class SesiController extends Controller
{
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);
        }

        Auth::login($user);

        return redirect()->route('home.landing');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return Redirect::route('login.google')->withErrors('Login with Google failed. Please try again.');
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(Str::random()),
                'role' => 'user',
            ]);

            Auth::login($newUser, true);
        }

        return redirect()->route('admin');
    }

    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required!',
            'password.required' => 'Password is required!',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($infologin)) {
            return $this->redirectToRole(Auth::user()->role);
        } else {
            return redirect('')->withErrors('Username or password is incorrect')->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }

    public function registerView()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('login');
    }

    protected function redirectToRole($role)
    {
        if ($role == 'user') {
            return redirect('admin/user');
        } elseif ($role == 'admin') {
            return redirect('admin/admin');
        } elseif ($role == 'operator') {
            return redirect('admin/operator');
        }
    }
}
