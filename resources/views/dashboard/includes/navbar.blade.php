<header class="h-16 bg-black border-b border-yellow-600/20 flex items-center justify-between px-4 sm:px-6 min-w-0">

 
    <div class="flex items-center min-w-0">

        {{-- Sidebar toggle --}}
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="text-yellow-500 hover:text-yellow-400 transition"
        >
            <i class="fa-solid fa-bars"></i>
        </button>

    </div>

    <!-- Right -->
    <div class="flex items-center gap-4 min-w-0" x-data="{ open: false }">

        <span class="hidden sm:block text-sm text-zinc-400 truncate max-w-32">
            {{ auth()->user()->name }}
        </span>

        <button @click="open = !open" class="text-yellow-500 shrink-0">
           <img src="{{ auth()->user()->profile_image ? asset('storage/profiles/' . auth()->user()->profile_image) : 'https://via.placeholder.com/40' }}" 
                alt="Perfil" class="w-10 h-10 rounded-full border border-zinc-700 object-cover">
        </button>

        <!-- Dropdown  -->
        <div
            x-show="open"
            @click.outside="open = false"
            class="absolute right-6 top-16 bg-zinc-900 border border-zinc-700 rounded-xl w-40 p-2"
        >
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full text-left px-4 py-2 text-sm text-zinc-300 hover:bg-yellow-600 hover:text-black rounded"
                >
                    Cerrar sesión
                </button>
            </form>
        </div>

    </div>

</header>
