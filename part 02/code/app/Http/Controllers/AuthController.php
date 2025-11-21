<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // Display Login Form
    public function showLogin()
    {
        // If the user is already logged in, redirect them to the book list
        if (Auth::check()) {
            return redirect('/books');
        }
        return view('auth.login');
    }

    // Login logic
    public function login(Request $request)
    {
        //  Validate the input fields
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $userRole = Auth::user()->Role;

            //load different dashboards based on role
            if ($userRole === 'Librarian') {
                return redirect()->intended('/books')->with('success', 'Welcome, Librarian!');
            }

            return redirect()->intended('/books')->with('success', 'Welcome back, Customer!');
        }

        // 3. Handle Failed Authentication
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // register form display
    public function showRegister()
    {
        return view('auth.register');
    }

    // registration logic
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'Role' => ['required', 'string', 'in:Customer,Librarian'], // Ensures only valid roles are accepted
        ]);

        // Create the user using the validated and filled 'Role' field
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'Role' => $request->Role,
        ]);

        // Log the user in immediately after registration
        Auth::login($user);

        return redirect('/books')->with('success', 'Registration successful! Welcome to the library.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
