<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Affiche la page de profil de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.user.profile', compact('user'));
    }

    /**
     * Met à jour les informations du profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);
        
        // Mise à jour manuelle des champs
        $user->firstname = $validated['firstname'];
        $user->lastname = $validated['lastname'];
        $user->email = $validated['email'];
        
        if (isset($validated['username'])) {
            $user->username = $validated['username'];
        }
        
        $user->bio = $validated['bio'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->website = $validated['website'] ?? null;
        $user->country = $validated['country'] ?? null;
        $user->city = $validated['city'] ?? null;
        
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès!');
    }

    /**
     * Met à jour l'image de profil
     */
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Supprimer l'ancienne image si elle existe
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }
        
        // Stocker la nouvelle image
        $path = $request->file('profile_image')->store('profile-images', 'public');
        
        // Mettre à jour le chemin de l'image
        $user->profile_image = $path;
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Photo de profil mise à jour avec succès!');
    }

    /**
     * Met à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->password = Hash::make($validated['new_password']);
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Mot de passe mis à jour avec succès!');
    }

    /**
     * Met à jour les préférences utilisateur
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $preferences = $user->preferences ?? [];
        $notifications = $preferences['notifications'] ?? [];
        
        $notifications['weekly_report'] = $request->has('weekly_report');
        $notifications['goal_achieved'] = $request->has('goal_achieved');
        $notifications['product_news'] = $request->has('product_news');
        
        $preferences['notifications'] = $notifications;
        $preferences['theme'] = $request->theme ?? 'dark';
        $preferences['accent_color'] = $request->accent_color ?? 'indigo';
        $preferences['font_size'] = $request->font_size ?? 'medium';
        
        $user->preferences = $preferences;
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Préférences mises à jour avec succès!');
    }
}
