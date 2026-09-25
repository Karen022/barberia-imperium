@extends('landing.layouts.landing')

@section('title', 'Inicio')

@section('content')
    <section class="relative overflow-hidden">
        <!-- fondo decorativo -->
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-800 to-neutral-900"></div>

        <div class="relative max-w-7xl mx-auto px-8 py-28 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

            <!-- TEXTO -->
            <div>
                <span class="text-yellow-500 uppercase tracking-widest text-sm">
                    Barbería Imperium
                </span>

                <h1 class="text-4xl md:text-6xl font-bold leading-tight mt-4 mb-6">
                    Estilo que<br>
                    define presencia
                </h1>

                <p class="text-gray-400 max-w-lg mb-10">
                    Cortes modernos, clásicos y personalizados.
                    Reservá tu turno y viví una experiencia profesional.
                </p>

                <div class="flex gap-4">
                    <a href="{{ route('landing.appointments.create') }}"
                        class="bg-yellow-500 text-black px-8 py-4 rounded-full font-semibold hover:bg-yellow-300 hover:scale-105 hover:font-bold transition">
                        Reservar turno
                    </a>

                    <a href="{{ route('landing.services') }}"
                        class="border border-yellow-500 text-yellow-500 px-8 py-4 rounded-full hover:bg-yellow-500 hover:scale-105 hover:font-bold hover:text-black transition">
                        Ver servicios
                    </a>
                </div>
            </div>

            <!-- BLOQUE VISUAL -->
            <div class="relative w-full flex justify-center md:justify-end">
                <div class="bg-neutral-800 rounded-3xl shadow-2xl w-full md:w-[500px] lg:w-[600px] h-72 md:h-[420px] lg:h-[500px] overflow-hidden">
                    <img src="{{ asset('images/barba.jpeg') }}" alt="barbaCare" 
                        class="rounded-3xl w-full h-full object-cover">
                </div>
            </div>

        </div>

    </section>

    <x-info-section></x-info-section>

    <section class="bg-neutral-900 py-20">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

            <!-- BLOQUE DE TEXTOS -->
            <div class="text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Nuestros Servicios
                </h2>

                <p class="text-neutral-300 mb-6 leading-relaxed">
                    Cortes clásicos y modernos, arreglos de barba y una experiencia
                    pensada para que salgas renovado. En nuestra barbería, cada
                    detalle importa.
                </p>

                <a href="{{ route('landing.services') }}"
                    class="inline-block bg-yellow-500 text-black px-6 py-3 rounded-full font-semibold hover:bg-yellow-400 hover:scale-105 hover:font-bold transition">
                    Ver servicios
                </a>
            </div>

            <!-- IMAGEN A LA DERECHA -->
            <div class="flex justify-center md:justify-end">
                <img src="{{ asset('images/Barber.jpeg') }}" alt="Servicios Barbería"
                    class="rounded-3xl shadow-2xl object-cover w-full h-full">
            </div>

        </div>
    </section>


    <section class="bg-neutral-950 py-20">
        <div class="max-x-6xl mx-auto px-6">

            <h2 class="text-3xl  md:text-4xl font-bold text-white text-center mb-10">
                Productos Destacados
            </h2>


            <div class="flex gap-6 overflow-x-auto pb-8 px-4 md:px-0 justify-center">

                @foreach ($featuredProducts as $product)
                    <div class="min-w-[280px] md:min-w-[320px] bg-neutral-900 rounded-2xl p-6 flex-shrink-0 shadow-lg text-white">
                        <div class="w-full h-56 md:h-64 rounded-xl overflow-hidden mb-4">
                            <img src="{{ asset('storage/products/' . $product->image) }}" class="w-full h-full object-cover"
                                alt="{{ $product->name }}">
                        </div>
                        <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                        <p class="text-sm text-neutral-400">{{ $product->description }}</p>
                    </div>
                @endforeach

                <a href="{{ route('landing.products') }}" class="min-w-[250px] flex flex-col items-center justify-center
                                  border-2 border-dashed border-yellow-500
                                  rounded-2xl text-yellow-500
                                  hover:bg-yellow-500 hover:text-black
                                  transition font-semibold">
                    Ver todos →
                </a>

            </div>
        </div>
    </section>

@endsection