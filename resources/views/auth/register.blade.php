<!-- filepath: c:\Users\LENOVO\Herd\CodeTracker\resources\views\auth\register.blade.php -->
<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <!-- Card d'inscription avec effet de profondeur -->
        <div class="bg-gray-800/60 backdrop-blur-xl p-6 sm:p-8 rounded-2xl border border-gray-700 shadow-2xl relative overflow-hidden">
            <!-- Élément décoratif -->
            <div class="absolute -top-24 -right-24 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-purple-600/20 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <div class="text-center mb-4">
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1">Créer un compte</h2>
                    <p class="text-sm text-gray-400">Commencez à tracker votre temps de code</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="firstname" class="block text-gray-400 text-sm font-medium mb-1">Prénom</label>
                            <div class="relative group">
                                <i class="ph-user-circle-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                                <input id="firstname" value="{{ old('firstname') }}" name="firstname" type="text" class="w-full bg-gray-700/50 border {{ $errors->has('firstname') ? 'border-red-500' : 'border-gray-600' }} rounded-lg py-3 pl-9 pr-3 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="saisé votre prenom" required>
                            </div>
                        </div>
                        <div>
                            <label for="lastname" class="block text-gray-400 text-sm font-medium mb-1">Nom</label>
                            <div class="relative group">
                                <i class="ph-identification-badge-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                                <input id="lastname" value="{{ old('lastname') }}" name="lastname" type="text" class="w-full bg-gray-700/50 border {{ $errors->has('lastname') ? 'border-red-500' : 'border-gray-600' }} rounded-lg py-3 pl-9 pr-3 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="saisié votre nom" required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-gray-400 text-sm font-medium mb-1">Email</label>
                        <div class="relative group">
                            <i class="ph-envelope-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                            <input id="email" value="{{ old('email') }}" name="email" type="email" class="w-full bg-gray-700/50 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-600' }} rounded-lg py-3 pl-9 pr-3 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="vous@exemple.com" required>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-gray-400 text-sm font-medium mb-1">Mot de passe</label>
                        <div class="relative group">
                            <i class="ph-lock-key-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                            <input id="password" name="password" type="password" class="w-full bg-gray-700/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-600' }} rounded-lg py-3 pl-9 pr-3 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-gray-400 text-sm font-medium mb-1">Confirmer le mot de passe</label>
                        <div class="relative group">
                            <i class="ph-lock-key-bold absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-400 transition-colors"></i>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-700/50 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-600' }} rounded-lg py-3 pl-9 pr-3 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" class="w-3.5 h-3.5 rounded border-gray-600 text-indigo-600 focus:ring-indigo-500 bg-gray-700" required>
                        </div>
                        <label for="terms" class="ml-2 text-sm text-gray-400">
                            J'accepte les <a href="#" class="text-indigo-400 hover:text-indigo-300 font-medium underline">conditions d'utilisation</a>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 rounded-lg hover:from-indigo-500 hover:to-indigo-600 transition-colors font-medium shadow-lg transform hover:translate-y-[-1px] active:translate-y-[1px] transition-transform text-sm">
                        Créer un compte
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-400">
                        Déjà un compte? 
                        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium hover:underline transition-all">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Message de sécurité -->
        <div class="mt-3 text-center text-sm text-gray-500 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span class="text-sm">Vos données sont sécurisées et chiffrées</span>
        </div>
    </div>
</x-guest-layout>