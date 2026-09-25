<x-guest-layout>

    <div class="min-h-[80vh] flex items-center justify-center px-6 py-16">

        <div class="w-full max-w-xl">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Barbería Imperium"
                    class="w-40 h-auto"
                >
            </div>

            <!-- Header -->
            <div class="text-center mb-10">

                <h1 class="text-3xl md:text-4xl font-bold text-white">
                    ¿Olvidaste tu contraseña?
                </h1>

                <p class="mt-4 text-base text-zinc-400 leading-relaxed">
                    No hay problema. Ingresa el correo electrónico de tu cuenta
                    y te enviaremos un enlace para restablecer tu contraseña.
                </p>

            </div>

            <!-- Form -->
            <div class="bg-zinc-800/80 border border-zinc-700 rounded-2xl shadow-2xl p-6 md:p-8">

                <!-- Session Status -->
                <x-auth-session-status
                    class="mb-6"
                    :status="session('status')"
                />

                <form
                    method="POST"
                    action="{{ route('password.email') }}"
                    class="space-y-6"
                >

                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-zinc-300 mb-2"
                        >
                            Correo electrónico
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="w-full rounded-xl bg-zinc-900 border border-zinc-700 text-white px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-yellow-500
                                   focus:border-yellow-500"
                        >

                        @error('email')
                            <p class="mt-2 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Button -->
                    <x-primary-button type="submit">
                        {{ __('Enviar enlace') }}
                    </x-primary-button>

                </form>

            </div>

        </div>

    </div>

</x-guest-layout>