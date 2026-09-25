@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="p-6">
        <a href="{{ route('dashboard.services.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>
    </div>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-center text-white mb-6">Editar Servicio</h1>

        <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">

            <form action="{{ route('dashboard.services.update', $service) }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="text-sm text-zinc-300">Nombre</label>
                    <input type="text" name="name" id="name"
                        class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 mt-1 h-12"
                        value="{{ old('name', $service->name) }}">

                    @error('name')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label for="image" class="text-sm text-zinc-300">Imagen</label>
                    <input type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        class="w-full text-sm text-zinc-300
                                   file:bg-yellow-600 file:border-0 file:text-black
                                   file:px-4 file:py-2 file:rounded-lg file:font-semibold">
                    @if(isset($service) && $service->image)
                        <img src="{{ asset('storage/services/' . $service->image) }}" alt="Imagen actual"
                            class="mt-2 w-32 h-32 object-cover rounded" />
                    @endif

                    @error('image')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label for="price" class="text-sm text-zinc-300">Precio</label>
                    <input type="number" name="price" id="price"
                        class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 mt-1 h-12"
                        value="{{ old('price', number_format($service->price, 0, '.', '')) }}">

                    @error('price')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label for="duration_min" class="text-sm text-zinc-300">Duración (minutos)</label>
                    <input type="number" min="0" step="5" name="duration_min" id="duration_min"
                        class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 mt-1 h-12"
                        value="{{ old('duration_min', $service->duration_min)}}">

                    @error('duration_min')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label for="description" class="text-sm text-zinc-300">Descripción</label>
                    <textarea type="text" name="description" id="description"
                        class="w-full rounded-xl bg-black/40 px-4 text-white border border-zinc-700 mt-1 h-20"
                        value="{{ old('description', $service->description) }}">
                        </textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button
                        class="w-full bg-yellow-600 hover:bg-yellow-400 text-black font-semibold py-3 rounded-xl transition-all"
                        type="submit">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection