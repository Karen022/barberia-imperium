@extends('dashboard.layouts.dashboard')

@section('content')


    <div class="p-6 text-gray-200">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold mb-6">Agenda de Turnos</h1>

            <a href="{{ route('dashboard.appointments.create') }}"
                class="bg-yellow-600 hover:bg-yellow-400 text-black px-4 py-2 rounded-xl font-semibold">
                Nuevo Turno
            </a>
        </div>


        <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">

            <div class="flex justify-center items-center mb-6 pt-5">

                <h2 class="text-lg text-zinc-200 font-semibold">
                    Turnos Reservados
                </h2>
            </div>


            {{-- Tabla --}}
            <table class="w-full text-sm text-zinc-300">
                <thead class="text-left border-b border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Clientes</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Hora</th>
                        <th class="px-4 py-3 text-left">Servicio</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="border-b border-zinc-700">
                            <td class="px-4 py-3 text-left">{{ $appointment->client?->name ?? 'Sin cliente' }}</td>
                            <td class="px-4 py-3 text-left">
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                @forelse ($appointment->services as $service )
                                    <div>
                                        {{ $service->name }}
                                    </div>
                                @empty
                                    <span class="text-neutral-500">
                                        Sin servicios
                                    </span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-left">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                                @if($appointment->status === 'pending') text-yellow-400
                                                                @elseif($appointment->status === 'completed') text-green-400
                                                                @else text-red-400 
                                                                @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                <div class="flex items-center gap-2 flex-wrap">
                                    {{-- Editar completo (admin) --}}
                                    @role('admin')
                                    <a href="{{ route('dashboard.appointments.edit', $appointment) }}"
                                        class="border border-yellow-600 hover:bg-yellow-600/30 text-yellow-600 px-5 py-3 rounded-xl font-semibold">
                                        Editar
                                    </a>
                                    @endrole

                                    {{-- Acciones rápidas --}}
                                    @if(
                                            $appointment->status === 'pending' &&
                                            (auth()->user()->hasRole('admin') || auth()->id() === $appointment->barber_id)
                                        )

                                        {{-- Completar --}}
                                        <form method="POST"
                                            action="{{ route('dashboard.appointments.status', [$appointment, 'completed']) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="border border-green-600/30 text-green-400 hover:bg-green-600/30 px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center transition"
                                                title="Completar turno">
                                                Completar
                                            </button>
                                        </form>


                                        {{-- Cancelar --}}
                                        <form method="POST"
                                            action="{{ route('dashboard.appointments.status', [$appointment, 'cancelled']) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="border border-red-600/30 text-red-400 hover:bg-red-600/30 px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center transition"
                                                title="Cancelar turno">
                                                Cancelar
                                            </button>
                                        </form>

                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-zinc-500 text-xs py-4">
                                (No hay registros aún)
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- -Bloquear Horarios --}}
        @role('admin')
            <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mt-6">

                <h2 class="text-lg text-zinc-200 font-semibold mb-5">
                    Bloquear horario de un barbero
                </h2>

                <form action="{{ route('dashboard.appointments.unavailability.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Barbero --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Barbero
                        </label>

                        <select name="barber_id" required
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">
                            <option value="">Seleccione…</option>

                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" @selected(old('barber_id') == $barber->id)>
                                    {{ $barber->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('barber_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Desde --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Desde
                        </label>

                        <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">

                        @error('start_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hasta --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Hasta
                        </label>

                        <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" required
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">

                        @error('end_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Motivo <span class="text-zinc-500">(opcional)</span>
                        </label>

                        <input type="text" name="reason" value="{{ old('reason') }}" maxlength="255"
                            placeholder="Ej: No estaré en la barbería"
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">

                        @error('reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botón --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition">
                            Bloquear horario
                        </button>
                    </div>
                </form>
            </div>
        @endrole

        {{-- Horarios bloqueados --}}
        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mt-6">

            <h2 class="text-lg text-zinc-200 font-semibold mb-5">
                Horarios bloqueados
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-zinc-300">
                    <thead class="border-b border-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Barbero</th>
                            <th class="px-4 py-3 text-left">Desde</th>
                            <th class="px-4 py-3 text-left">Hasta</th>
                            <th class="px-4 py-3 text-left">Motivo</th>
                            <th class="px-4 py-3 text-left">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($unavailabilities as $unavailability)
                                            <tr class="border-b border-zinc-700">

                                                <td class="px-4 py-3">
                                                    {{ $unavailability->barber?->name ?? 'Sin barbero' }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    {{ $unavailability->start_time->format('d/m/Y H:i') }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    {{ $unavailability->end_time->format('d/m/Y H:i') }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    {{ $unavailability->reason ?? '—' }}
                                                </td>

                                                <td class="px-4 py-3">
                                                    <form method="POST" action="{{ route(
                                                        'dashboard.appointments.unavailability.destroy',
                                                        $unavailability) }}"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            class="border border-red-600/30 text-red-400 hover:bg-red-600/30 px-4 py-2 rounded-xl font-semibold transition">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-zinc-500 text-xs py-4">
                                    No hay horarios bloqueados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection