@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6 text-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">

            <div>
                <h1 class="text-2xl font-bold text-white">
                    Comisiones
                </h1>
            </div>

            <a href="{{ route('dashboard.commissions.payments.index') }}"
            class="px-5 py-3 rounded-xl bg-zinc-700 text-white text-sm font-semibold hover:bg-zinc-600 transition text-center"
            >
                Historial de pagos
            </a>
        </div>


        

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-900/30 border border-red-700 text-red-400">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Cerrar semana --}}
        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mb-8">

            <h2 class="text-lg font-semibold text-white mb-2">
                Cierre semanal de comisiones
            </h2>

            <p class="text-sm text-gray-400 mb-5">
                Seleccioná una fecha correspondiente a la semana que deseas liquidar.
                El sistema calculará automáticamente el período de lunes a sábado.
            </p>

            <form action="{{ route('dashboard.commissions.close') }}" method="POST"
                class="flex flex-col sm:flex-row sm:items-end gap-4">

                @csrf

                <div class="w-full sm:max-w-xs">
                    <label for="date" class="block text-sm text-gray-300 mb-2">
                        Fecha de referencia
                    </label>

                    <input type="date" name="date" id="date" value="{{ old('date', now()->toDateString()) }}" required
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-yellow-600">
                </div>

                <x-confirm-dialog
                    title="Cerrar las comisiones de esta semana?"
                    message="Se generará la liquidación de comisiones para los barberos correspondientes. Verifica que todos los turnos de la semana estén correctamente registrados."
                    confirmText="Cerrar semana"
                    cancelText="Cancelar"
                >
                    <x-slot:trigger>
                        <span class="px-5 py-2 rounded-xl bg-yellow-600 hover:bg-yellow-500 text-black font-semibold transition inline-flex items-center justify-center cursor-">
                            Cerrar semana
                        </span>
                    </x-slot:trigger>

                    <x-slot:confirm>
                        <button
                            type="submit" 
                            class="rounded-xl bg-yellow-600 hover:bg-yellow-500 px-4 py-2 text-sm font-semibold text-black transition"
                        >
                            Cerrar semana
                        </button>
                    </x-slot:confirm>
                </x-confirm-dialog>
            </form>
        </div>

        <div class="space-y-6">

            @forelse ($periods as $period)
                <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-white">
                                Semana:
                                {{  \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') }}
                                --
                                {{ \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') }}
                            </h2>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm w-fit
                                {{  $period->status === 'closed'
                                    ? 'bg-green-900/40 text-green-400'
                                    : 'bg-yellow-900/400 text-yellow-400'
                                }}
                        ">
                            {{  $period->status === 'closed' ? 'Cerrada' : 'Abierta' }}
                        </span>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-800 text-gray-300">
                                <tr>
                                    <th class="px-4 py-3 text-left">Barbero</th>
                                    <th class="px-4 py-3 text-left">Servicios</th>
                                    <th class="px-4 py-3 text-left">Total Generado</th>
                                    <th class="px-4 py-3 text-left">Comisión (60%)</th>
                                    <th class="px-4 py-3 text-left">Barbería (40%)</th>
                                    <th class="px-4 py-3 text-left">Acciones</th>
                                    <th class="px-4 py-3 text-left">Estado de pago</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($commissions->get($period->id, collect()) as $commission)
                                    <tr class="border-t border-zinc-700 hover:bg-zinc-800/50">
                                        <td class="px-4 py-3 font-medium">
                                            {{ $commission->barber_name }}
                                        </td>

                                        <td class="px-4 py-3 font-medium">
                                            {{ number_format($commission->services_total, 0, ',', '.')  }}
                                        </td>

                                        <td class="px-4 py-3 font-medium">
                                            Gs. {{ number_format($commission->services_total, 0, ',', '.')  }}
                                        </td>

                                        <td class="px-4 py-3 font-medium">
                                            Gs. {{ number_format($commission->barber_amount, 0, ',', '.')  }}
                                        </td>

                                        <td class="px-4 py-3 font-medium">
                                            Gs. {{ number_format($commission->shop_amount, 0, ',', '.')  }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <a href="{{ route('dashboard.commissions.show', $commission->id) }}"
                                                class="text-yellow-400 hover:text-yellow-300 font-medium">
                                                Ver detalles
                                            </a>
                                        </td>

                                        @php
                                            $totalPaid = (float) $commission->total_paid;
                                            $totalCommission = (float) $commission->barber_amount;

                                            $pendingBalance = max(0, $totalCommission - $totalPaid);
                                        @endphp

                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($pendingBalance <= 0)
                                                <span class="px-3 py-1 text-xs font-semibold  text-green-400">
                                                    Pagado
                                                </span>

                                            @elseif ($totalPaid > 0)
                                                <span class="px-3 py-1 text-xs font-semibold  text-yellow-400">
                                                    Pago parcial
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold  text-red-400">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                                            No hay liquidaciones registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-black/40 border border-zinc-700 rounded-xl p-8 text-center">
                    <p class="text-gray-400">
                        Todavía no hay períodos de comisiones registrados.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $periods->links() }}
        </div>
    </div>
@endsection