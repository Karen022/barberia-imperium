@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">

        <a href="{{ route('dashboard.products.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>

        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl text-center text-white font-bold mb-6">Nuevo Producto</h1>

            <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">
                <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="text-sm text-zinc-300">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12">

                        @error('name')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="text-sm text-zinc-300">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 resize-none">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="text-sm text-zinc-300">Precio</label>
                            <input type="number" id="price" name="price" step="0.01" value="{{ old('price') }}"
                                class="w-full rounded-xl bg-black/40 px- focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12">

                            @error('price')
                                <p class="mt-1 text-sem text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="text-sm text-zinc-300">Cantidad</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock') }}"
                                class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12">

                            @error('stock')
                                <p class="mt-1 text-sem text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="image" class="text-sm text-zinc-300">Imagen</label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="w-full mt-1 text-sm text-zinc-300 file:bg-yellow-600 file:border-0 file:text-black file:px-4 file:py-2 file:rounded-lg file:font-semibold">
                        @error('image')
                            <p class="mt-1 text-sem text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition">
                            Agregar producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection