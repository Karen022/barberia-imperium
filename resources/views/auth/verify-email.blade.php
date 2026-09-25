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
                    Verifica tu correo electrónico
                </h1>

                <p class="mt-4 text-base text-zinc-400 leading-relaxed">
                    Gracias por registrarte en Barbería Imperium.
                    Antes de comenzar, verifica tu dirección de correo electrónico
                    haciendo clic en el enlace que te enviamos.
                </p>

            </div>

            <!-- Container -->
            <div class="bg-zinc-800/80 border border-zinc-700 rounded-2xl shadow-2xl p-6 md:p-8">

                <!-- Success Message  -->
                @if (session('status') === 'verification-link-sent')
                    <div class="mb-6 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-400">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>

                            <p>
                                Se ha enviado un nuevo enlace de verificación
                                a tu dirección de correo electrónico.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="space-y-4">

                    <!-- Forward Button -->
                    <form
                        method="POST"
                        action="{{ route('verification.send') }}"
                    >
                        @csrf

                        <x-primary-button type="submit">
                        {{ __('Reenviar correo de verificación') }}
                    </x-primary-button>
                    </form>

                    <!-- Logout -->
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="flex items-center justify-center"
                    >
                        @csrf

                        <x-secondary-button type="submit">
                            {{ __('Cerrar Sesión') }}
                        </x-secondary-button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>