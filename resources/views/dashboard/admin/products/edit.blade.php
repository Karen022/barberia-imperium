@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">
        <a href="{{ route('dashboard.products.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>
    </div>

    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl text-white text-center font-bold mb-6">
            Editar Producto
        </h1>

        <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">
            <form
                action="{{ route('dashboard.products.update', $product) }}"
                method="POST"
                class="space-y-5"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <label for="name" class="text-sm text-zinc-300">
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full rounded-xl bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 px-4 mt-1 h-12 text-white"
                        value="{{ old('name', $product->name) }}"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="description" class="text-sm text-zinc-300">
                        Descripción
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 resize-none"
                    >{{ old('description', $product->description) }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Precio y cantidad --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Precio --}}
                    <div>
                        <label for="price" class="text-sm text-zinc-300">
                            Precio
                        </label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            step="0.01"
                            min="0"
                            class="w-full rounded-xl bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 px-4 mt-1 h-12 text-white"
                            value="{{ old('price', number_format($product->price, 0, '.', '')) }}"
                        >

                        @error('price')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <label for="stock" class="text-sm text-zinc-300">
                            Cantidad
                        </label>

                        <input
                            type="number"
                            id="stock"
                            name="stock"
                            min="0"
                            class="w-full rounded-xl bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 px-4 mt-1 h-12 text-white"
                            value="{{ old('stock', $product->stock) }}"
                        >

                        @error('stock')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Imagen --}}
                <div>
                    <label for="image" class="text-sm text-zinc-300">
                        Imagen
                    </label>

                    @if($product->image)
                        <div class="my-2">
                            <img
                                src="{{ asset('storage/products/' . $product->image) }}"
                                alt="Imagen actual"
                                class="w-48 h-48 object-cover rounded-lg border border-neutral-700"
                            >
                        </div>
                    @endif

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        class="w-full mt-1 text-sm text-zinc-300 file:bg-yellow-600 file:border-0 file:text-black file:px-4 file:py-2 file:rounded-lg file:font-semibold"
                    >

                    @error('image')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Botón --}}
                <div class="pt-4">
                    <button
                        class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition-all"
                        type="submit"
                    >
                        Editar
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

