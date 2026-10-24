
@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="p-4">
        <a href="{{ route('dashboard.commissions.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>
    </div>

    <div class="space-y-6 py-6">

        <div>
            <h1 class="text-2xl font-bold text-white">
                Registrar pago de comisión
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                Selecciona un barbero y las liquidaciones que deseas pagar.
            </p>
        </div>

        {{-- Seleccionar barbero --}}
        <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
            <form action="{{ route('dashboard.commissions.payments.create') }}"
                method="GET">

                <div class="max-w-xl">
                    <label for="barber_id" class="block text-sm text-gray-300 mb-2">
                        Seleccionar barbero
                    </label>

                    <select name="barber_id" id="barber_id"
                        onchange="this.form.submit()"
                        class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-yellow-600 focus:outline-none">

                        <option value="">Seleccionar barbero</option>

                        @foreach($barbers as $barber)
                            <option value="{{ $barber->id }}"
                                @selected($selectedBarber?->id == $barber->id)>
                                {{ $barber->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('barber_id')
                        <p class="text-red-400 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </form>
        </div>

        @if($selectedBarber)

            {{-- Información del barbero --}}
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
                <p class="text-sm text-gray-400">Barbero seleccionado</p>

                <h2 class="text-xl font-semibold text-white mt-1">
                    {{ $selectedBarber->name }}
                </h2>
            </div>

            {{-- Formulario de pago --}}
            <form action="{{ route('dashboard.commissions.payments.store') }}"
                method="POST"
                x-data="{
                    selected: {},
                    amounts: {},

                    get total() {
                        return Object.keys(this.selected)
                            .filter(id => this.selected[id])
                            .reduce((sum, id) => {
                                return sum + (Number(this.amounts[id]) || 0);
                            }, 0);
                    },

                    formatAmount(value) {
                        return new Intl.NumberFormat('es-PY', {
                            maximumFractionDigits: 0
                        }).format(value || 0);
                    }
                }">

                @csrf

                <input type="hidden" name="barber_id"
                    value="{{ $selectedBarber->id }}">

                {{-- Liquidaciones pendientes --}}
                <div class="bg-zinc-800 border border-zinc-700 rounded-xl overflow-hidden">

                    <div class="p-5 border-b border-zinc-700">
                        <h3 class="text-lg font-semibold text-white">
                            Liquidaciones pendientes
                        </h3>

                        <p class="text-sm text-gray-400 mt-1">
                            Selecciona las liquidaciones que deseas incluir en este pago.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-300">

                            <thead class="bg-zinc-900 text-gray-400 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Seleccionar</th>
                                    <th class="px-4 py-3">Semana</th>
                                    <th class="px-4 py-3 text-right">Comisión</th>
                                    <th class="px-4 py-3 text-right">Pagado</th>
                                    <th class="px-4 py-3 text-right">Pendiente</th>
                                    <th class="px-4 py-3 text-right">Importe a pagar</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($availableCommissions as $item)

                                    @php
                                        $totalPaid = (float) ($item->total_paid ?? 0);

                                        $pendingBalance = max(
                                            0,
                                            (float) $item->barber_amount - $totalPaid
                                        );
                                    @endphp

                                    <tr class="border-t border-zinc-700 hover:bg-zinc-700/30 transition">

                                        {{-- Checkbox --}}
                                        <td class="px-4 py-3">
                                            <input
                                                type="checkbox"
                                                x-model="selected['{{ $item->id }}']"
                                                @change="
                                                    if ($event.target.checked) {
                                                        amounts['{{ $item->id }}'] = {{ $pendingBalance }};
                                                    } else {
                                                        delete amounts['{{ $item->id }}'];
                                                    }
                                                "
                                                class="w-4 h-4 rounded border-zinc-600 bg-zinc-900 text-yellow-500 focus:ring-yellow-500">
                                        </td>

                                        {{-- Semana --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $item->period->start_date->format('d/m/Y') }}
                                            al
                                            {{ $item->period->end_date->format('d/m/Y') }}
                                        </td>

                                        {{-- Comisión --}}
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            Gs. {{ number_format($item->barber_amount, 0, ',', '.') }}
                                        </td>

                                        {{-- Pagado --}}
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            Gs. {{ number_format($totalPaid, 0, ',', '.') }}
                                        </td>

                                        {{-- Pendiente --}}
                                        <td class="px-4 py-3 text-right whitespace-nowrap text-yellow-400 font-medium">
                                            Gs. {{ number_format($pendingBalance, 0, ',', '.') }}
                                        </td>

                                        {{-- Importe editable --}}
                                        <td class="px-4 py-3">
                                            <input
                                                type="number"
                                                name="allocations[{{ $item->id }}][amount]"
                                                x-model.number="amounts['{{ $item->id }}']"
                                                :disabled="!selected['{{ $item->id }}']"
                                                min="1"
                                                max="{{ $pendingBalance }}"
                                                step="1"
                                                placeholder="0"
                                                class="w-36 ml-auto block text-right rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-yellow-600 focus:outline-none disabled:opacity-30 disabled:cursor-not-allowed">

                                            <input
                                                type="hidden"
                                                name="allocations[{{ $item->id }}][commission_id]"
                                                value="{{ $item->id }}"
                                                :disabled="!selected['{{ $item->id }}']">
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6"
                                            class="px-4 py-8 text-center text-gray-400">
                                            Este barbero no tiene liquidaciones cerradas con saldo pendiente.
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                @error('allocations')
                    <p class="text-red-400 text-sm mt-2">
                        {{ $message }}
                    </p>
                @enderror

                @error('allocations.*.amount')
                    <p class="text-red-400 text-sm mt-2">
                        {{ $message }}
                    </p>
                @enderror

                {{-- Resumen del pago --}}
                @if($availableCommissions->isNotEmpty())

                    <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5 space-y-5 mt-5">

                        <h3 class="text-lg font-semibold text-white">
                            Información del pago
                        </h3>

                        {{-- Total --}}
                        <div class="bg-zinc-900 rounded-xl p-4">
                            <p class="text-sm text-gray-400">
                                Total del pago
                            </p>

                            <p class="text-2xl font-bold text-yellow-400 mt-1">
                                Gs. <span x-text="formatAmount(total)">0</span>
                            </p>
                        </div>

                        {{-- Fecha --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label for="payment_date"
                                    class="block text-sm text-gray-300 mb-2">
                                    Fecha de pago
                                </label>

                                <input type="date"
                                    name="payment_date"
                                    id="payment_date"
                                    value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                    required
                                    class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-yellow-600 focus:outline-none">

                                @error('payment_date')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Método de pago --}}
                            <div>
                                <label for="payment_method"
                                    class="block text-sm text-gray-300 mb-2">
                                    Método de pago
                                </label>

                                <select name="payment_method"
                                    id="payment_method"
                                    required
                                    class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-yellow-600 focus:outline-none">

                                    <option value="">Seleccionar método</option>

                                    @foreach(['Efectivo', 'Transferencia', 'QR', 'En Línea'] as $method)
                                        <option value="{{ $method }}"
                                            @selected(old('payment_method') === $method)>
                                            {{ $method }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('payment_method')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        {{-- Observaciones --}}
                        <div>
                            <label for="notes"
                                class="block text-sm text-gray-300 mb-2">
                                Observaciones (opcional)
                            </label>

                            <textarea name="notes"
                                id="notes"
                                rows="3"
                                maxlength="1000"
                                placeholder="Agregar alguna observación sobre el pago..."
                                class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-yellow-600 focus:outline-none">{{ old('notes') }}</textarea>

                            @error('notes')
                                <p class="text-red-400 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Botón --}}
                        <div class="flex justify-end">
                            <button type="submit"
                                :disabled="total <= 0"
                                class="px-6 py-3 rounded-xl bg-yellow-600 text-black font-semibold text-sm hover:bg-yellow-500 transition disabled:opacity-40 disabled:cursor-not-allowed">

                                Registrar pago
                            </button>
                        </div>

                    </div>

                @endif

            </form>

        @endif

    </div>
@endsection
