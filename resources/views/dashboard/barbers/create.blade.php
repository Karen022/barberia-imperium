@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">
        <a href="{{ route('dashboard.barbers.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>
    </div>
    <div class="p-6 text-gray-200 max-w-3xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold">Nuevo Barbero</h1>
            <p class="text-gray-400 mt-1">
                Registrá un nuevo barbero para la barbería.
            </p>
        </div>

        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6">

            <form action="{{ route('dashboard.barbers.store') }}" method="POST">
                @csrf

                <!-- Nombre -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nombre
                    </label>

                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full bg-zinc-900 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600">

                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Email
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-zinc-900 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 rounded-xl px-4 h-12 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600">

                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Contraseña
                    </label>

                    <input type="password" id="password" name="password" required
                        class="w-full bg-zinc-900 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600">

                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar contraseña -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                        Confirmar contraseña
                    </label>

                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full bg-zinc-900 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600">

                    @error('password_confirmation')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end gap-3">

                    <a href="{{ route('dashboard.barbers.index') }}"
                        class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 hover:bg-zinc-800 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-400 text-black px-5 py-2 rounded-xl font-semibold transition">
                        Crear Barbero
                    </button>

                </div>

            </form>

        </div>


    </div>

@endsection