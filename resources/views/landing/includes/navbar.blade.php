<header class="border-b border-neutral-800 relative">
    <div class="max-w-7xl mx-auto px-8 py-6 flex justify-between items-center">

        <!-- Logo -->
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-16 md:h-20">
        </div>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex gap-8 text-sm text-gray-300 items-center">
            <a href="/"
                class="{{ request()->is('/') ? 'text-yellow-500 border-b-2 border-yellow-500' : '' }} hover:text-yellow-500 transition">Inicio</a>
            <a href="{{ route('landing.services') }}"
                class="{{ request()->is('services') ? 'text-yellow-500 border-b-2 border-yellow-500' : '' }} hover:text-yellow-500 transition">Servicios</a>
            <a href="{{ route('landing.products') }}"
                class="{{ request()->is('products') ? 'text-yellow-500 border-b-2 border-yellow-500' : '' }} hover:text-yellow-500 transition">Productos</a>
            <a href="{{ route('landing.contact') }}"
                class="{{ request()->is('contact') ? 'text-yellow-500 border-b-2 border-yellow-500' : '' }} hover:text-yellow-500 transition">Contacto</a>

            <a href="{{ route('landing.appointments.create') }}"
                class="{{ request()->is('turnos') ? 'text-yellow-500 border-b-2 border-yellow-500' : '' }} hover:text-yellow-500 transition">Reservar turno</a>

            @guest
                    <a href=" {{ route('login') }}"
                    class="inline-flex items-center justify-center text-black font-bold bg-yellow-500 rounded-full px-6 h-10 hover:bg-yellow-300 hover:scale-105 hover:font-bold transition">Iniciar
                    sesión</a>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center text-yellow-500 font-bold border border-yellow-500 rounded-full px-6 h-10 hover:bg-yellow-400 hover:scale-105 hover:font-bold hover:text-black transition">Registrarse</a>
            @endguest

            @auth
                    @role('client')
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center gap-3 hover:text-yellow-500 transition">

                            <img src="{{ auth()->user()->profile_image
                ? asset('storage/profiles/' . auth()->user()->profile_image)
                : asset('images/avatar-default.png') }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-yellow-500">

                            <span class="font-semibold">{{ auth()->user()->name }}</span>

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div id="user-dropdown"
                            class="hidden absolute right-0 mt-3 w-48 bg-neutral-900 border border-neutral-700 rounded-xl shadow-lg overflow-hidden z-50">

                            <a href="{{ route('profile.index') }}"
                                class="block text-center px-4 py-3 hover:bg-yellow-500 hover:text-black transition">
                                Mi perfil
                            </a>

                            <a href="{{ route('landing.appointments.my') }}"
                                class="block text-center px-4 py-3 hover:bg-yellow-500 hover:text-black  transition">
                                Mis turnos
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="flex justify-center items-center">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-center px-4 py-2 hover:bg-yellow-500 hover:text-black transition">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    @endrole
            @endauth

        </nav>

        <!-- Mobile Hamburger -->
        <div class="md:hidden">
            <button id="menu-btn" class="text-yellow-500 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

    </div>

    <!-- Mobile Menu -->
    <nav id="mobile-menu"
        class="hidden md:hidden flex-col gap-4 p-4 bg-neutral-900 absolute w-full left-0 top-full z-50">
        <a href="/" class="block px-4 py-2 text-center hover:bg-yellow-500 hover:text-black transition">Inicio</a>
        <a href="{{ route('landing.services') }}"
            class="block px-4 py-2 text-center hover:bg-yellow-500 hover:text-black transition">Servicios</a>
        <a href="{{ route('landing.products') }}"
            class="block px-4 py-2 text-center hover:bg-yellow-500 hover:text-black transition">Productos</a>
        <a href="{{ route('landing.contact') }}"
            class="block px-4 py-2 text-center hover:bg-yellow-500 hover:text-black transition">Contacto</a>
        <a href="{{ route('landing.appointments.create') }}"
            class="block px-4 py-2 text-center hover:bg-yellow-500 hover:text-black transition">Reservar turno</a>

        @guest
            <div class="mt-6 mb-4 border-t border-neutral-700"></div>
            <a href="{{ route('login') }}"
                class="block px-4 py-2 bg-yellow-500 text-center text-black font-bold rounded-lg hover:bg-yellow-300 transition">Iniciar
                sesión</a>

            <div class="mt-6 mb-4 border-t border-neutral-700"></div>
            <a href="{{ route('register') }}"
                class="block px-4 py-2 border border-yellow-500  text-center text-yellow-500 font-bold rounded-lg hover:bg-yellow-400 hover:text-black transition">Registrarse</a>
        @endguest
        @auth


            <div class="mt-6 mb-4 border-t border-neutral-700"></div>

            @role('client')

            <div class="flex flex-col items-center gap-3 pt-4 pb-4">

                <img src="{{ auth()->user()->profile_image
            ? asset('storage/profiles/' . auth()->user()->profile_image)
            : asset('images/avatar-default.png') }}"
                    class="w-16 h-16 rounded-full border-2 border-yellow-500 object-cover shadow-md">

                <span class="font-semibold text-yellow-500 text-sm">
                    {{ auth()->user()->name }}
                </span>

            </div>

            <div class="flex flex-col gap-2">

                <a href="{{ route('profile.index') }}"
                    class="block text-center px-4 py-2 rounded-lg hover:bg-yellow-500 hover:text-black transition">
                    Mi Perfil
                </a>

                <a href="{{ route('landing.appointments.my') }}"
                    class="block text-center px-4 py-2 rounded-lg hover:bg-yellow-500 hover:text-black transition">
                    Mis turnos
                </a>



            </div>

            @endrole
            <div class="mt-6 mb-4 border-t border-neutral-700"></div>

            <div class="mt-4">
                <form method="POST" action="{{ route('logout') }}" class="flex justify-center items-center">
                    @csrf
                    <button
                        class="block w-full text-center px-4 py-2 rounded-lg hover:bg-yellow-500 hover:text-black transition">
                        Cerrar Sesión
                    </button>
                </form>
            </div>

        @endauth


    </nav>
</header>