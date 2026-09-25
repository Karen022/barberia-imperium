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
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-left">
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}</td>
                            <td class="px-4 py-3 text-left">{{ $appointment->service?->name ?? 'Sin servicio' }}</td>
                            <td class="px-4 py-3 text-left">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if($appointment->status === 'pending') bg-yellow-600/20 text-yellow-400
                                                @elseif($appointment->status === 'completed') bg-green-600/20 text-green-400
                                                @else bg-red-400 
                                                @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-left">

                                {{-- Editar completo (admin) --}}
                                @role('admin')
                                <a href="{{ route('dashboard.appointments.edit', $appointment) }}"
                                    class="text-yellow-500 hover:underline">
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
                                        <button class="text-green-400 hover:underline">
                                            Completar
                                        </button>
                                    </form>

                                    {{-- Cancelar --}}
                                    <form method="POST"
                                        action="{{ route('dashboard.appointments.status', [$appointment, 'cancelled']) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-red-400 hover:underline">
                                            Cancelar
                                        </button>
                                    </form>

                                @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-zinc-500 text-xs py-4">
                                (No hay registros aún?dashboard)
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>     
    </div>
@endsection