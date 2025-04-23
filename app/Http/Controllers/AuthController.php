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

            // dd($validated);
            $user = User::create($validated);
            Auth::login($user);
            
            // Stocker l'ID utilisateur dans la session
            session(['auth_user_id' => $user->id]);
            
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

        // Vérifier si l'utilisateur existe et est actif avant la tentative d'authentification
        $user = User::where('email', $validated['email'])->first();
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'error' => 'Votre compte a été désactivé par l\'administrateur. Pour plus d\'informations, veuillez nous contacter.'
            ])->withInput($request->only('email'));
        }

        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            
            // Stocker l'ID utilisateur dans la session
            session(['auth_user_id' => Auth::id()]);
            
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
