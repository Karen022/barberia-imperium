@extends('landing.layouts.landing')

@section('title', 'Contacto')

@section('content')

    <section class="relative py-28">
        <!-- fondo suave -->
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-800 to-neutral-950"></div>

        <div class="relative max-w-7xl mx-auto px-8">

            <!-- TÍTULO -->
            <div class="text-center mb-20">
                <span class="text-yellow-500 uppercase tracking-widest text-sm mt-4">
                    Contacto
                </span>

                <h1 class="text-4xl md:text-5xl font-bold mt-4">
                    Hablemos de tu próximo estilo
                </h1>

                <p class="text-gray-400 max-w-xl mx-auto mt-6">
                    Reservá tu turno o escribinos para cualquier consulta.
                    Estamos listos para atenderte.
                </p>
            </div>

            <!-- GRID PRINCIPAL -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">

                <!-- INFO -->
                <div class="space-y-10">
                    <div class="bg-neutral-800 p-8 rounded-2xl flex items-center gap-6">
                        <div
                            class="w-12 h-12 rounded-full border border-yellow-500 flex items-center justify-center text-yellow-500">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Teléfono</h3>
                            <p class="text-gray-400 text-sm">
                                +595 983 176 430
                            </p>
                        </div>
                    </div>

                    <div class="bg-neutral-800 p-8 rounded-2xl flex items-center gap-6">
                        <div
                            class="w-12 h-12 rounded-full border border-yellow-500 flex items-center justify-center text-yellow-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Email</h3>
                            <p class="text-gray-400 text-sm">
                                imperiumbarberiia@gmail.com
                            </p>
                        </div>
                    </div>

                    <div class="bg-neutral-800 p-8 rounded-2xl flex items-center gap-6">
                        <div
                            class="w-12 h-12 rounded-full border border-yellow-500 flex items-center justify-center text-yellow-500">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Dirección</h3>
                            <p class="text-gray-400 text-sm">
                                Tavapy, Paraguay
                            </p>
                        </div>
                    </div>

                    <div class="relative w-full h-0 pb-[56.25%] rounded-2xl overflow-hidden shadow-lg">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115079.76218010069!2d-55.14727440273434!3d-25.663252600000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94f66f79ac34b629%3A0x53ca7500e65b862e!2sImperium%20Barberia!5e0!3m2!1ses-419!2spy!4v1769048809692!5m2!1ses-419!2spy"
                            class="absolute top-0 left-0 w-full h-full border-0" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">

                        </iframe>
                    </div>

                </div>

                <!-- FORMULARIO -->
                <div class="bg-neutral-800 p-12 rounded-3xl shadow-2xl">

                    <form action="{{ route('landing.contact.send') }}" method="POST" class="space-y-6">

                        @csrf
                        <h3 class="text-2xl font-semibold text-center mt-2">Puedes escribirnos cualquier duda o consulta...
                        </h3>
                        <div>
                            <label class="block text-sm mb-2">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0">

                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0">

                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-2">Mensaje</label>
                            <textarea rows="4" name="message"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0">
                                {{ old('message') }}
                                </textarea>

                            @error('message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-yellow-500 text-black py-4 rounded-full font-semibold  hover:bg-yellow-400 transition">
                            Enviar mensaje
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </section>

@endsection