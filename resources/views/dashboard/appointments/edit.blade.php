@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">
        <a href="{{ route('dashboard.appointments.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>
    </div>
    <div class="p-6 max-w-3xl mx-auto">


        <h1 class="text-2xl font-bold text-white mb-6">
            Editar Turno
        </h1>

        <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">

            <form action="{{ route('dashboard.appointments.update', $appointment->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

            
                {{-- Cliente --}}
                <div>
                    <label class="text-sm text-zinc-300">Nombre del cliente</label>
                    <select name="client_id"
                        class="mt-1 w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">
                        <option value="">Seleccionar…</option>

                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $appointment->client_id) == $client->id)>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('client_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Barber --}}
                <div>
                    <label class="text-sm text-zinc-300">Barbero</label>
                    <select name="barber_id"
                        class="mt-1 w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">
                        <option value="">Seleccione…</option>

                        @foreach ($barbers as $barber)
                            <option value="{{ $barber->id }}" @selected(old('barber_id', $appointment->barber_id) == $barber->id)>
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

                    @php
                        $selectedServices = old(
                        'service_ids',
                        $appointment->services->pluck('id')->all()
                        );
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($services as $service)
                            <label class="flex items-center gap-3 bg-black/40 border border-zinc-700 rounded-xl px-3 py-2.5 cursor poitner hover:border-yellow-500 transition">
                                <input 
                                    type="checkbox"
                                    name="service_ids[]"
                                    value="{{ $service->id }}"
                                    data-duration="{{ $service->duration_min }}"
                                    data-price="{{ $service->price }}"
                                    class="w-4 h-4 acent-yellow-500 shrink-0"
                                    @checked(in_array($service->id, old('service_ids',  $selectedServices)))
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
                

                {{-- Fecha + Hora --}}
                <div>
                    <label class="text-sm text-zinc-300">
                        Fecha y hora
                    </label>

                    <input type="datetime-local" name="scheduled_at" 
                        class="mt-1 w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4"
                        value="{{ old('scheduled_at', \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i')) }}">

                    @error('scheduled_at')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="text-sm text-zinc-300">Estado</label>
                    <select name="status"
                        class="mt-1 w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">
                        <option value="pending" @selected(old('status', $appointment->status) === 'pending')>
                            Pendiente
                        </option>

                        <option value="completed" @selected(old('status', $appointment->status) === 'completed')>
                            Completado
                        </option>

                        <option value="cancelled" @selected(old('status', $appointment->status) === 'cancelled')>
                            Cancelado
                        </option>
                    </select>

                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Botón --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition-all">
                        Guardar
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection