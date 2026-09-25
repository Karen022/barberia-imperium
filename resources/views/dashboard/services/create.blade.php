@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">

        <a href="{{ route('dashboard.services.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>

        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl text-center font-bold text-white mb-6">
                Nuevo Servicio
            </h1>

            <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">
                <form action="{{ route('dashboard.services.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    {{-- Nombre --}}
                    <div>
                        <label for="name" class="block text-sm text-zinc-300 mb-1">
                            Nombre
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 h-12">

                        @error('name')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Imagen --}}
                    <div>
                        <label for="image" class="block text-sm text-zinc-300 mb-1">
                            Imagen
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" class="w-full text-sm text-zinc-300
                                   file:bg-yellow-600 file:border-0 file:text-black
                                   file:px-4 file:py-2 file:rounded-lg file:font-semibold">

                        @error('image')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Precio --}}
                    <div>
                        <label for="price" class="block text-sm text-zinc-300 mb-1">
                            Precio
                        </label>
                        <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}"
                            class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 h-12">

                        @error('price')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Duración --}}
                    <div>
                        <label for="duration_min" class="block text-sm text-zinc-300 mb-1">
                            Duración (minutos)
                        </label>
                        <input type="number" min="0" step="5" name="duration_min" id="duration_min"
                            value="{{ old('duration_min', 30) }}"
                            class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 h-12">

                        @error('duration_min')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="description" class="block text-sm text-zinc-300 mb-1">
                            Descripción
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 resize-none">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Botón --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition">
                            Guardar servicio
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection