<!-- filepath: c:\Users\LENOVO\Herd\CodeTracker\resources\views\layouts\guest.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://unpkg.com/phosphor-icons@1.4.2/src/css/icons.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen flex items-center justify-center py-12">
        <!-- Notifications -->
        @if ($errors->any() || session('success'))
        <div id="notification" class="fixed top-4 right-4 z-50 max-w-sm">
            @if ($errors->any())
                <div class="bg-red-500/90 backdrop-blur-sm text-white px-4 py-3 rounded-xl shadow-lg transform transition-all duration-500 flex items-start space-x-3 mb-2">
                    <div class="flex-shrink-0 mt-0.5">
                        <i class="ph-warning-circle-bold text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Erreur</p>
                        <ul class="mt-1 text-sm opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="flex-shrink-0 text-white focus:outline-none" onclick="document.getElementById('notification').classList.add('opacity-0'); setTimeout(() => {document.getElementById('notification').style.display = 'none'}, 500)">
                        <i class="ph-x-bold"></i>
                    </button>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-500/90 backdrop-blur-sm text-white px-4 py-3 rounded-xl shadow-lg transform transition-all duration-500 flex items-start space-x-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <i class="ph-check-circle-bold text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Succès</p>
                        <p class="mt-1 text-sm opacity-90">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="flex-shrink-0 text-white focus:outline-none" onclick="document.getElementById('notification').classList.add('opacity-0'); setTimeout(() => {document.getElementById('notification').style.display = 'none'}, 500)">
                        <i class="ph-x-bold"></i>
                    </button>
                </div>
            @endif
        </div>
        
        <script>
            // Auto-hide notifications after 5 seconds
            setTimeout(() => {
                const notification = document.getElementById('notification');
                if (notification) {
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 500);
                }
            }, 5000);
        </script>
        @endif
        
        {{ $slot }}
    </body>
</html>