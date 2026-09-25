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

                {{-- Servicio --}}
                <div>
                    <label class="text-sm text-zinc-300">Servicio</label>
                    <select name="service_id"
                        class="mt-1 w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">
                        <option value="">Seleccione…</option>

                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id', $appointment->service_id) == $service->id)>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('service_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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