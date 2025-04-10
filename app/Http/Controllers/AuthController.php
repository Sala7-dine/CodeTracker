<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function showRegister(){

        return view("auth.register");

    }

    public function showLogin(){

        return view("auth.login");

    }
    
    public function register(Request $request){
        try {
            $validated = $request->validate([
                "firstname" => 'required|string|max:255',
                "lastname" => 'required|string|max:255',
                "email" => 'required|email|unique:users',
                "password" => 'required|string|min:8|confirmed'
            ]);

            $user = User::create($validated);
            Auth::login($user);
            
            return redirect()->route('user.dashboard')->with('success', 'Compte créé avec succès!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }


    public function login(Request $request){

        $validated = $request->validate([
            "email" => 'required|email',
            "password" => 'required|string'
        ]);

        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            return redirect()->route("user.dashboard");
        }

        return back()->withErrors([
            'login_error' => 'Les identifiants fournis ne correspondent à aucun compte.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request){

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("home");

    }

}
