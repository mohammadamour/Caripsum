<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;


class SignupController extends Controller
{
    public function create(){
        return view('auth.signup');
    }
 public function sign(Request $request)
{
    $validated = $request->validate([
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed'], // needs password_confirmation
        'fname' => ['required', 'string', 'max:100'],
        'lname' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
    ]);

    // 'password' is auto-hashed by your User model cast
    User::create([
        'fname' => $validated['fname'],
        'lname' => $validated['lname'],
        'phone' => $validated['phone'],
        'email' => $validated['email'],
        'password' => $validated['password'],
    ]);

    return redirect()->route('login')->with('success', 'Account created! Please login.');
}
}
