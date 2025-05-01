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
    
    
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.user.profile', compact('user'));
    }

   
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        

        $rules = [
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
        ];
        

        if ($request->filled('current_password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['new_password'] = 'required|string|min:8|confirmed';
        }
        
        $validated = $request->validate($rules);
        

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
        

        if ($request->filled('current_password') && isset($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }
        

        $preferences = $user->preferences ?? [];
        $notifications = $preferences['notifications'] ?? [];
        
        $notifications['weekly_report'] = $request->has('weekly_report');
        $notifications['goal_achieved'] = $request->has('goal_achieved');
        $notifications['product_news'] = $request->has('product_news');
        
        $preferences['notifications'] = $notifications;
        $preferences['theme'] = $request->has('dark_mode') ? 'dark' : 'light';
        $preferences['accent_color'] = $request->accent_color ?? 'indigo';
        $preferences['font_size'] = $request->font_size ?? 'medium';
        
        $user->preferences = $preferences;
        
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès!');
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }
        

        $path = $request->file('profile_image')->store('profile-images', 'public');
        

        $user->profile_image = $path;
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Photo de profil mise à jour avec succès!');
    }
    
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }
        

        $user->delete();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('info', 'Votre compte a été supprimé définitivement.');
    }

  
    public function generateApiKey(Request $request)
    {
        $user = Auth::user();
        

        $user->api_key = \Illuminate\Support\Str::random(60);
        $user->save();
        

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre clé API a été générée avec succès.',
                'api_key' => $user->api_key
            ]);
        }
        
        return redirect()->route('profile')->with('success', 'Votre clé API a été générée avec succès.');
    }
}
