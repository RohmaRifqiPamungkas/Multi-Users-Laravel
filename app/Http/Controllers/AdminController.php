<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $data = User::all(); 
        return view('admin', compact('role', 'data'));
    }

    public function user()
    {
        $role = 'User';
        $data = User::where('role', 'user')->get(); 
        return view('admin', compact('role', 'data'));
    }

    public function admin()
    {
        $role = 'Admin';
        $data = User::where('role', 'admin')->get(); 
        return view('admin', compact('role', 'data'));
    }

    public function operator()
    {
        $role = 'Operator';
        $data = User::where('role', 'operator')->get(); 
        return view('admin', compact('role', 'data'));
    }
}
