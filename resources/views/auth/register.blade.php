<x-guest-layout>
    <div
        class="min-h-screen w-full bg-gradient-to-br from-black via-zinc-900 to-neutral-900 flex items-center justify-center p-4 py-12 md:py-20">
        <a href="/"
            class="absolute top-6 left-6 flex items-center gap-2 text-gray-400 hover:text-yellow-500 transition-colors duration-200 group bg-zinc-900/50 backdrop-blur-md px-4 py-2 rounded-full border border-white/5 shadow-lg">
            <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span class="text-sm font-medium hidden sm:inline">Volver al inicio</span>
        </a>
        <div
            class="w-full max-w-5xl bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 mt-16 md:mt-0">

            {{-- IZQUIERDA: imagen o logo --}}
            <div class="hidden md:flex items-center justify-center bg-black/40">
                <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-full">
            </div>

            {{-- DERECHA: formulario --}}
            <div class="p-10 flex flex-col justify-center">

                <h2 class="text-2xl text-center font-semibold text-white mb-6">Registrar Cuenta</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">

                    @csrf

                    {{-- Logo exclusivo para moviles --}}
                    <div class="flex md:hidden justify-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="logo" class="h-20 w-auto object-contain">
                    </div>
                    <!-- Name -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nombre de Usuario')" class="text-gray-300" />
                        <x-text-input id="name" class="block mt-2 w-full h-12 px-4
                                                    bg-black/30 border border-zinc-700
                                                    text-white placeholder-gray-400
                                                    rounded-lg
                                                    focus:outline-none focus:ring-2 focus:ring-yellow-500" name="name"
                            required autofocus autocomplete="username" />
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                        <x-text-input id="email" class="block mt-2 w-full h-12 px-4
                                                    bg-black/30 border border-zinc-700
                                                    text-white placeholder-gray-400
                                                    rounded-lg
                                                    focus:outline-none focus:ring-2 focus:ring-yellow-500" name="email"
                            type="email" required autofocus autocomplete="email" />

                        @error('email')
                            <p class="mt-1 text-sm text-red-400">
                                {{  $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Contraseña')" class="text-gray-300" />
                        <x-text-input id="password" class="block mt-2 w-full h-12 px-4
                                                    bg-black/30 border border-zinc-700
                                                    text-white placeholder-gray-400
                                                    rounded-lg
                                                    focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            type="password" name="password" required autocomplete="password" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">
                                {{  $message }}
                            </p>
                        @enderror
                    </div>

                    <!--Confirmation Password -->
                    <div class="mb-4">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')"
                            class="text-gray-300" />
                        <x-text-input id="password_confirmation" class="block mt-2 w-full h-12 px-4
                                                    bg-black/30 border border-zinc-700
                                                    text-white placeholder-gray-400
                                                    rounded-lg
                                                    focus:outline-none focus:ring-2 focus:ring-yellow-500"
                            type="password" name="password_confirmation" required autocomplete="current-password" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">
                                {{  $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center mb-4">
                        <input id="remember_me" name="remember" type="checkbox" value="1" @checked(old('remember'))
                            class="rounded border-zinc-700 text-yellow-500 focus:ring-yellow-500 bg-black/20">
                        <label for="remember_me" class="ml-2 text-sm text-gray-300">
                            {{ __('Recordarme') }}
                        </label>
                    </div>

                    <!-- Botón -->
                    <x-primary-button type="submit">
                        {{ __('Registrarme') }}
                    </x-primary-button>


                    {{-- Register link --}}
                    <p class="text-center text-sm text-gray-300 mt-4">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="text-gold hover:underline">
                            Iniciar Sesión
                        </a>
                    </p>

                </form>
            </div>

        </div>

    </div>
</x-guest-layout>