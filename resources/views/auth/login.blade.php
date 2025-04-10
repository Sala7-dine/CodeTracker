<!-- filepath: c:\Users\LENOVO\Herd\CodeTracker\resources\views\auth\login.blade.php -->
<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <!-- Card de connexion avec effet de profondeur -->
        <div class="bg-gray-800/60 backdrop-blur-xl p-8 rounded-2xl border border-gray-700 shadow-2xl relative overflow-hidden">
            <!-- Élément décoratif -->
            <div class="absolute -top-24 -right-24 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-purple-600/20 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Bienvenue</h2>
                    <p class="text-gray-400">Connectez-vous à votre compte</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-gray-400 text-sm font-medium mb-2">Email</label>
                        <div class="relative group">
                            <i class="ph-envelope-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full bg-gray-700/50 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-600' }} rounded-xl py-3 pl-10 pr-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="vous@exemple.com" required>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-gray-400 text-sm font-medium mb-2">Mot de passe</label>
                        <div class="relative group">
                            <i class="ph-lock-key-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                            <input id="password" name="password" type="password" class="w-full bg-gray-700/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-600' }} rounded-xl py-3 pl-10 pr-4 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-700">
                            <label for="remember" class="ml-2 text-sm text-gray-400">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="text-sm text-indigo-400 hover:text-indigo-300 hover:underline transition-all">Mot de passe oublié?</a>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 rounded-xl hover:from-indigo-500 hover:to-indigo-600 transition-colors font-medium shadow-lg transform hover:translate-y-[-2px] active:translate-y-[1px] transition-transform">
                        Se connecter
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-400">
                        Pas encore de compte? 
                        <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium hover:underline transition-all">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Message de sécurité -->
        <div class="mt-6 text-center text-xs text-gray-500 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>Vos données sont sécurisées et chiffrées</span>
        </div>
    </div>
</x-guest-layout>