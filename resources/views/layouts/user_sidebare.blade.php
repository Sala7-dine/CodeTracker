<aside class="fixed left-0 top-0 h-screen w-20 bg-gray-800 border-r border-gray-700 flex flex-col items-center py-8 space-y-8">
    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center">
        <i class="ph-code text-2xl text-white"></i>
    </div>
    
    <nav class="flex flex-col space-y-6">
        <a href="{{ route("user.dashboard") }}" class="w-12 h-12 rounded-xl bg-gray-700 flex items-center justify-center text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all">
            <i class="ph-squares-four text-xl"></i>
        </a>
        <a href="#" class="w-12 h-12 rounded-xl hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all" 
           id="open-project-modal" onclick="openProjectModal()">
            <i class="ph-folder-simple text-xl"></i>
        </a>
        <a href="#" class="w-12 h-12 rounded-xl hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all">
            <i class="ph-chart-line-up text-xl"></i>
        </a>
        <a href=" {{ route('profile') }} " class="w-12 h-12 rounded-xl hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-all">
            <i class="ph-gear-six text-xl"></i>
        </a>
        <!-- Logout icon -->

        <form action="{{ route('logout') }}" method="POST">
            @csrf 
            <button class="w-12 h-12 rounded-xl hover:bg-red-600 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <i class="ph-sign-out text-xl"></i>
            </button>
        </form>


            
        </a>
    </nav>
    
</aside>