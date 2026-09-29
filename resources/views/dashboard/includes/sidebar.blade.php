<aside
    :class="sidebarOpen
        ? 'translate-x-0 lg:w-64'
        : '-translate-x-full lg:translate-x-0 lg:w-20'"
    class="fixed inset-y-0 left-0 z-40 w-64  bg-black border-r border-yellow-600/20 flex flex-col transition-all duration-300 lg:static"
>

    {{-- Logo --}}
    <div class="h-16 flex items-center justify-center border-b boreder-yellow-600/20">

        <span x-show="sidebarOpen" class="text-xl font-bold text-yellow-500">

            Imperium
        </span>
    </div>

    {{-- Menu --}}

    <nav class="flex-1 p-4 space-y-2">

        <a href="{{ route('dashboard.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-chart-line w-5 text-center"></i>

            <span x-show="sidebarOpen">Dashboard</span>
        </a>

        <a href="{{ route('dashboard.appointments.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.appointments.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-calendar-days w-5 text-center"></i>

            <span x-show="sidebarOpen">Agenda</span>
        </a>

        <a href="{{ route('dashboard.sales.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.sales.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-file-lines w-5 text-center"></i>

            <span x-show="sidebarOpen">Registrar Venta</span>
        </a>

        @role('admin')
        <a href="{{ route('dashboard.clients.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.clients.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-users w-5 text-center"></i>

            <span x-show="sidebarOpen">Clientes</span>
        </a>
        <a href="{{ route('dashboard.barbers.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.barbers.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            
            <i class="fa-solid fa-user w-5 text-center"></i>
            <span x-show="sidebarOpen">Barberos</span>
        </a>
        
        <a href="{{ route('dashboard.cash.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.cash.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-cash-register w-5 text-center"></i>

            <span x-show="sidebarOpen">Caja</span>
        </a>

        <a href="{{ route('dashboard.services.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.services.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-scissors"></i>

            <span x-show="sidebarOpen">Servicios</span>
        </a>    

        <a href="{{ route('dashboard.products.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
                {{ request()->routeIs('dashboard.products.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-box-open w-5 text-center"></i>

            <span x-show="sidebarOpen">Productos</span>
        </a> 

        @endrole

        <!-- <a href="{{ route('dashboard.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition">

            <i class="fa-solid fa-gear w-5 text-center"></i>

            <span x-show="sidebarOpen">Configuracion</span>
        </a> -->

        <a href="{{ route('profile.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-300 hover:bg-yellow-600 hover:text-black transition
            {{ request()->routeIs('profile.index') ? 'bg-yellow-600 text-black' : 'text-zinc-300 hover-bg-yellow-600 hover:text-black' }}">

            <i class="fa-solid fa-circle-user"></i>

            <span x-show="sidebarOpen">Mi Perfil</span>
        </a>

        
    </nav>
</aside>