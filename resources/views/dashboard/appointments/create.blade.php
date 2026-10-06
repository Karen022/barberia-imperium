@extends('dashboard.layouts.dashboard')

@section('content')

<div class="p-6">
    <a
        href="{{ route('dashboard.appointments.index') }}"
        class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition"
    >
        ← Volver
    </a>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl text-center font-bold text-white mb-6">
            Nuevo Turno
        </h1>

        <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">
            <form action="{{ route('dashboard.appointments.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Cliente --}}
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">
                        Cliente
                    </label>
                    <select
                        name="client_id"
                        class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4"
                    >
                        <option value="">Seleccionar…</option>
                        @foreach ($clients as $client)
                            <option 
                                value="{{ $client->id }}"
                                @selected(old('client_id') == $client->id)
                            >
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Barbero --}}
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">
                        Barbero
                    </label>
                    <select
                        name="barber_id"
                        class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4"
                    >
                        <option value="">Seleccione…</option>
                        @foreach ($barbers as $barber)
                            <option 
                                value="{{ $barber->id }}"
                                @selected(old('barber_id') == $barber->id)
                            >
                                {{ $barber->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('barber_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Servicios --}}
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">
                        Servicio
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forEach($services as $service)
                            <label class="flex items-center gap-3 bg-black/40 border border-zinc-700 rounded-xl px-3 py-2.5 cursor poitner hover:border-yellow-500 transition">
                                <input 
                                    type="checkbox"
                                    name="service_ids[]"
                                    value="{{ $service->id }}"
                                    data-duration="{{ $service->duration_min }}"
                                    data-price="'{{ $service->price }}"
                                    class="w-4 h-4 acent-yellow-500 shrink-0"
                                    @checked(in_array($service->id, old('service_ids', [])))
                                >
                                <span class="flex-1 min-w-0">
                                    <span class="block text-white-text-sm font-medium truncate">
                                        {{ $service->name }}
                                    </span>

                                    <span class="block text-xs text-neutral-400">
                                        {{ $service->duration_min }} min
                                    </span>

                                    <span class="text-yellow-500 text-sm font-semibold whitespace-nowrap">
                                    Gs.  {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('service_ids')
                        <p class="text-red-500 mt-1 text-xs">
                            {{ $message }}
                        </p>
                    @enderror
                    @error('service_ids.*')
                        <p class="text-red-500 mt-1 text-xs">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div id="services-summary" class="mt-4 mb-5 px-4 py-3 bg-black/30 border border-zinc-700 rounded-xl text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span id="services-count" class="text-neutral-400">
                            Seleccioná uno o más servicios
                        </span>

                        <span id="services-duration" class="text-yellow-500 font-semibold whitespace-nowrap"></span>
                    </div>

                    <div id="services-total" class="mt-1 text-right text-white font-semibold"></div>
                </div>

                {{-- Fecha y hora --}}
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">
                        Fecha y hora
                    </label>
                    <input
                        type="datetime-local"
                        name="scheduled_at"
                        value="{{ old('scheduled_at') }}"
                        class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4"
                    >

                    @error('scheduled_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botón --}}
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition"
                    >
                        Guardar turno
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
