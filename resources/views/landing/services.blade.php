@extends('landing.layouts.landing')

@section('title', 'Servicios')

@section('content')
    
    <section class="relative py-28">
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-800 to-neutral-950"></div>

        <div class="relative max-x-7xl mx-auto px-8">

            <div class="text-center mb-20">
                <span class="text-yellow-500 uppercase tracking-widest text-sm mt-4">
                    Servicios
                </span>

                <h1 class="text-4xl md:text-5xl font-bold mt-4">
                    Nuestros servicios profesionales
                </h1>

                <p class="text-gray-400 text-center max-w-xl mx-auto mt-6">
                    Cortes de calidad, precisión y estilo para que luzcas siempre impecable.
                </p>
                
            </div>

            


            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">

                @foreach ($services as $service)

                    <div class="bg-neutral-800 rounded-3xl p-8 shadow-xl justify-center items-center text-center">
                        <div class="h-48 bg-neutral-700 rounded-2xl mb-6 flex justify-center text-gray-400">
                            @if($service->image)
                                <img src="{{ asset('storage/services/'.$service->image ) }}" alt="Imagen" class="w-full object-cover rounded-2xl">
                            @else
                                <span class="text-zinc-300 text-sm">Sin Imagen</span>
                            @endif
                            
                        </div>

                        <h3 class="font-semibold text-lg mb-2">{{ $service->name }}</h3>

                        <p class="text-gray-400 text-sm mb-4">
                           {{ $service->description }}
                        </p>

                        <p class="text-gray-400 text-sm mb-4">
                           Duración: {{ $service->duration_min }} minutos
                        </p>

                        @auth
                        <div class="flex justify-center items-center">
                            <span class="text-yellow-500  font-bold">
                                Gs {{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </div>
                        @endauth

                    </div>



                @endforeach

            </div>
        </div>
    </section>

@endsection