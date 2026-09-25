@extends('landing.layouts.landing')

@section('title', 'Productos')

@section('content')

    <section class="py-28 bg-gradient-to-b from-neutral-800 to-neutral-900">
        <div class="max-w-7xl mx-auto px-8 text-center">

            <span class="text-yellow-500 uppercase tracking-widest text-sm">
                Productos
            </span>

            <h1 class="text-4xl md:text-5xl font-bold mt-4">
                Cuidado profesional en casa
            </h1>

            <p class="text-gray-400 max-w-xl mx-auto mt-6">
                Productos seleccionados para mantener tu estilo todos los días.
            </p>
        </div>
    </section>

    <section class="py-28 bg-gradient-to-b from-neutral-900 to-neutral-950">
        <div class="max-x-7xl mx-auto px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">

                @foreach ($products as $product)

                    <div class="bg-neutral-800 rounded-3xl p-8 shadow-xl">
                        <div class="h-48 bg-neutral-700 rounded-2xl mb-6 flex justify-center text-gray-400">
                            @if($product->image)
                                <img src="{{ asset('storage/products/'.$product->image ) }}" alt="Imagen" class="w-full object-cover rounded-2xl">
                            @else
                                <span class="text-zinc-300 text-sm">Sin Imagen</span>
                            @endif
                            
                        </div>

                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>

                        <p class="text-gray-400 text-sm mb-4">
                           {{ $product->description }}
                        </p>

                        @auth
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-500  font-bold">
                                Gs {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>  
                        @endauth
                        

                    </div>



                @endforeach

            </div>
        </div>
    </section>
@endsection