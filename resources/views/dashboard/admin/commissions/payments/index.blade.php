@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="space-y-6">

        <div class="p-6">

            <a href="{{ route('dashboard.commissions.index') }}"
                class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
                ← Volver
            </a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-white">
                    Pago de comisiones
                </h1>

                <p class="text-sm text-gray-400 mt-1">
                    Historial general de pagos realizados a los barberos.
                </p>
            </div>

            <a href="{{ route('dashboard.commissions.payments.create') }}"
                class="px-5 py-3 rounded-xl bg-yellow-600 text-black text-sm font-semibold hover:bg-yellow-500 transition text-center"
            >
                Registrar Pago
            </a>
        </div>

        <div class="bg-zinc-800 border border-zinc-700 rounded-xl overflow-hidden">

            <div class="p-5 border-b border-zinc-700">
                <h2 class="text-lg font-semibold text-white">
                    Historial de Pagos
                </h2>

                <p class="text-sm text-gray-400 mt-1">
                    Total de registros: {{ $payments->total() }}
                </p>
            </div>

            <!-- Filters -->
            <form action="{{ route('dashboard.commissions.payments.index') }}" 
                method="GET"
                class="p-5 border-b border-zinc-700"
            >
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Barber -->
                    <div>
                        <label for="barber_id" class="block text-sm text-gray-400 mb-2">
                            Barbero
                        </label>

                        <select name="barber_id" id="barber_id" 
                            class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                        >
                            <option value="">Todos los barberos</option>

                            @foreach($barbers as $barber)
                                <option value="{{ $barber->id }}"
                                    {{ request('barber_id') === $barber->id ? 'selected' : '' }}
                                >
                                    {{ $barber->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month -->
                    <div>
                        <label for="month" class="block text-sm text-gray-400 mb-2">
                            Mes
                        </label>

                        <select name="month" id="month" 
                            class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 5 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                        >
                            <option value="">Todos los meses</option>

                            @foreach(range(1,12) as $month)
                                <option value="{{ $month }}"
                                    {{ request('month') === $month ? 'selected' : '' }}
                                >
                                    {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm text-gray-400 mb-2">
                            Año
                        </label>

                        <input type="number" 
                            name="year"
                            id="year"
                            value="{{ request('year') }}"
                            placeholder="Ej. 2026"
                            min="2000" max="2100"
                            class="w-full rounded-lg bg-zinc-900 boder border-zinc-700 text-white px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                        >
                    </div>

                    <!-- Week start -->
                    <div>
                        <label for="week_start" class="block text-sm text-gray-400 mb-2">
                            Semana desde
                        </label>

                        <input type="date" 
                            name="week_start"
                            id="week_start"
                            value="{{ request('week_start') }}"
                            class="w-full rounded-lg bg-zinc-900 boder border-zinc-700 text-white px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                        >
                    </div>

                    <!-- Week end -->
                    <div>
                        <label for="week_end" class="block text-sm text-gray-400 mb-2">
                            Semana hasta
                        </label>

                        <input type="date" 
                            name="week_end"
                            id="week_end"
                            value="{{ request('week_end') }}"
                            class="w-full rounded-lg bg-zinc-900 boder border-zinc-700 text-white px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                        >
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-5">
                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-lg bg-yellow-600 text-black font-semibold text-sm hover:bg-yellow-500 transition">
                        Filtrar
                    </button>

                    <a href="{{ route('dashboard.commissions.payments.index') }}" 
                        class="px-5 py-2.5 rounded-lg bg-zinc-700 text-white text-sm hover:bg-zinc-600 transition"
                    >
                        Limpiar filtros
                    </a>
                </div>  
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-300">
                    <thead class="bg-zinc-900 text-gray-400 uppercase text-xs">
                        <tr>
                            <td class="px-4 py-3">Fecha</td>
                            <td class="px-4 py-3">Barbero</td>
                            <td class="px-4 py-3 text-right">Importe</td>
                            <td class="px-4 py-3">Método de pago</td>
                            <td class="px-4 py-3 text-right">Liquidaciones</td>
                            <td class="px-4 py-3">Registrado por</td>
                            <td class="px-4 py-3">Acciones</td>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="border-t border-zinc-700 hover:bg-zinc-700/30 transition">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $payment->payment_date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $payment->barber->name}}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    Gs. {{number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $payment->payment_method}}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $payment->allocations_count }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $payment->registeredBy->name ?? 'No disponible' }}
                                </td>
                                
                                <td class="px-4 py-3">
                                    <a href="{{ route('dashboard.commissions.payments.show', $payment->barber_id) }}"
                                        class="text-yellow-400 hover:text-yellow-300 font-medium">
                                            Ver detalles
                                    </a>
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                    Todavía no hay pagos de comisiones registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-zinc-700">

                    <p class="text-sm text-zinc-400">
                        Mostrando
                        <span class="text-yellow-500 font-semibold">{{ $payments->firstItem() }}</span>
                        a
                        <span class="text-yellow-500 font-semibold">{{ $payments->lastItem() }}</span>
                        de
                        <span class="text-zinc-200 font-semibold">{{ $payments->total() }}</span>
                        pagos
                    </p>

                    <div class="flex items-center gap-2">

                        {{-- Anterior --}}
                           @if ($payments->onFirstPage())
                            <span class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-600 cursor-not-allowed">
                                <i class="fa-solid fa-angle-left"></i>
                            </span>
                        @else
                            <a href="{{ $payments->previousPageUrl() }}"
                                class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-yellow-600 hover:text-yellow-500 transition">
                                   <i class="fa-solid fa-angle-left"></i>
                            </a>
                        @endif

                        {{-- Página actual --}}
                        <span class="px-4 py-2 rounded-lg bg-yellow-600/20 border border-yellow-600/40 text-yellow-500 font-semibold">
                            {{ $payments->currentPage() }}
                        </span>

                        {{-- Siguiente --}}
                        @if ($payments->hasMorePages())
                            <a href="{{ $payments->nextPageUrl() }}"
                                class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-yellow-600 hover:text-yellow-500 transition">
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-600 cursor-not-allowed">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection