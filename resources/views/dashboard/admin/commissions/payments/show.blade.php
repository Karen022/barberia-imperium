@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="space-y-6">
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('dashboard.commissions.payments.index') }}"
                class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
                ← Volver
            </a>

            <a href="{{ route('dashboard.commissions.payments.create') }}" 
                class="px-4 py-2 text-black rounded-xl bg-yellow-600 text-xs hover:bg-yellow-500 transition"
            >
                Registrar Pago
            </a>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-white">
                Historial de Pagos
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                Consulta las comisiones y pagos de {{ $barber->name }}.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
                <p class="text-sm text-gray-400">
                    Total generado
                </p>

                <p class="text-2xl font-bold text-white mt-2">
                    Gs. {{ number_format($totalGenerated, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
                <p class="text-sm text-gray-400">
                    Total pagado
                </p>

                <p class="text-2xl font-bold text-green-400 mt-2">
                    Gs. {{ number_format($totalPaid, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
                <p class="text-sm text-gray-400">
                    Saldo pendiente
                </p>

                <p class="text-2xl font-bold text-yellow-400 mt-2">
                    Gs. {{ number_format($pendingBalance, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-zinc-800 border border-zinc-700 rounded-xl overflow-hidden">
            <div class="p-5 border-b border-zinc-700">
                <h2 class="text-lg font-semibold text-white">
                    Pagos registrados
                </h2>

                <p class="text-sm text-gray-400 mt-1">
                    {{ $payments->total() }} pagos encontrados.
                </p>
            </div>

            <div class="divide-y divide zinc-700">
                @forelse($payments as $payment)
                    <div class="p-5 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <p class="text-xs text-gray-400">
                                    Fecha de pago
                                </p>

                                <p class="text-white font-medium mt-1">
                                    {{ $payment->payment_date->format('d/m/Y') }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    Registrado por:
                                    {{ $payment->registeredBy->name ?? 'No disponible' }}
                                </p>
                            </div>

                            <div class="md:text-right">
                                <p class="text-xs text-gray-400">
                                    Importe total
                                </p>

                                <p class="text-xl font-bold text-green-400 mt-1">
                                    Gs. {{ number_format($payment->amount, 0, ',', '.' ) }}
                                </p>

                                <span class="inline-block text-xs text-gray-300 bg-zinc-700 rounded-full px-3 py-1 mt-2">
                                    {{ $payment->payment_method }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-zinc-900 rounded-xl p-4">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">
                                Liquidaciones abonadas
                            </h3>

                            <div class="space-y-3">
                                @foreach($payment->allocations as $allocation)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div>
                                            <p class="text-sm text-white">
                                                Semana:
                                                {{ $allocation->barberCommission->period->start_date->format('d/m/Y') }}
                                                al 
                                                {{ $allocation->barberCommission->period->end_date->format('d/m/Y') }}
                                            </p>
                                        </div>

                                        <p class="text-sm font-semibold text-yeloow-400 whitespace-nowrap">
                                            Gs. {{ number_format($allocation->amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($payment->notes)
                            <div>
                                <p class="text-xs text-gray-400">
                                    Observaciones
                                </p>

                                <p class="text-sm text-gray-300 mt-1">
                                    {{ $payment->notes }}
                                </p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-gray-400">
                            Este barbero todavía no tiene pagos registrados.
                        </p>
                    </div>
                @endforelse
            </div>
        
            @if($payments->hasPages())
                <div class="p-4 border-t border-zinc-700">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection