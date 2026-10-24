@extends('dashboard.layouts.dashboard')

@section('content')
    @php
        $totalPaid = $commission->paymentAllocations->sum('amount');
        $pendingBalance = max(0, (float) $commission->barber_amount - (float) $totalPaid);

        if ($pendingBalance <= 0) {
            $paymentStatus = 'Pagado';
            $statusColor = 'green';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'Pago parcial';
            $statusColor = 'yellow';
        } else {
            $paymentStatus = 'Pendiente';
            $statusColor = 'red';
        }
    @endphp

    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('dashboard.commissions.payments.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
                ← Volver
        </a>

        <a href="{{ route('dashboard.commissions.payments.create', [
                'barber_id' => $commission->barber_id
            ]) }}" 
            class="px-4 py-2 text-black rounded-xl bg-yellow-600 text-xs hover:bg-yellow-500 transition"
        >
            Registrar Pago
        </a>
    </div>

    <div class="space-y-6 py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <h1 class="text-2xl font-bold text-white">Detalle de Liquidación</h1>

            <p class="text-sm text-gray-400 mt-1">
                Semana de
                {{ $commission->period->start_date->format('d/m/Y') }}
                al
                {{ $commission->period->end_date->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5 mb-5">
        <p class="text-sm text-gray-400">Barbero</p>

        <h2 class="text-xl font-semibold text-white mt-1">
            {{ $commission->barber->name }}
        </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
        <div class="border border-zinc-700 rounded-xl p-5 bg-zinc-800">

            <p class="text-sm text-gray-400">Total generado</p>

            <p class="text-xl font-bold text-white mt-2">
                Gs. {{  number_format($commission->services_total, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
            <p class="text-sm text-gray-400">
                Comisión del barbero ({{ $commission->barber_percentage }} %)
            </p>

            <p class="text-xl text-green-400 font-bold mt-2">
                Gs. {{ number_format($commission->barber_amount, 0, ',', '.') }}
            </p>
        </div>

        <div class="border border-zinc-700 rounded-xl p-5 bg-zinc-800">

            <p class="text-sm text-gray-400">
                Parte de la barbería ({{ $commission->shop_percentage }} %)
            </p>

            <p class="text-xl font-bold text-white mt-2">
                Gs. {{  number_format($commission->shop_amount, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5">
            <p class="text-sm text-gray-400">Saldo pendiente</p>
            <p class="text-xl font-bold text-yellow-400 mt-2">
                Gs. {{ number_format($pendingBalance, 0, ',', '.') }}
            </p>

            <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full 
                        @if($statusColor === 'green')
                            bg-green-500/20 text-green-400
                        @elseif($statusColor === 'yellow')
                            bg-yellow-500/20 text-yellow-400
                        @else
                            bg-red-500/20 text-red-400
                        @endif">
                {{ $paymentStatus }}
            </span>
        </div>

    </div>

    <!-- @if ($availableCommissions->isNotEmpty())
        <div
            class="bg-zinc-800 border border-zinc-700 rounded-xl p-5 mb-5"
            x-data="{
                selected: @js(
                    $availableCommissions
                        ->where('id', $commission->id)
                        ->pluck('id')
                        ->map(fn ($id) => (string) $id)
                        ->values()
                ),
                amounts: @js(
                    $availableCommissions->mapWithKeys(function ($item) {
                        return [
                            (string) $item->id => round(
                                (float) $item->barber_amount - (float) ($item->total_paid ?? 0),
                                2
                            )
                        ];
                    })
                ),
                get totalAmount() {
                    return this.selected.reduce((total, id) => {
                        return total + (Number(this.amounts[id]) || 0);
                    }, 0);
                },
                formatAmount(amount) {
                    return Number(amount).toLocaleString('es-PY', {
                        maximumFractionDigits: 0
                    });
                }
            }"
        >
            <div class="mb-5">
                <h3 class="text-lg font-semibold ltext-white">
                    Registrar pago de comisión
                </h3>

                <p class="text-sm text-gray-400 mt-1">
                    Puedes distribuir un mismo pago entre varias liquidaciones del barbero.
                </p>
            </div>

            <form action="{{ route('dashboard.commissions.payments.store', ['commission' => $commission->id]) }}"
                class="space-y-5" method="POST">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="payment_date" class="block text-sm text-gray-300 mb-2">
                            Fecha del pago
                        </label>

                        <input type="date" name="payment_date" id="payment_date"
                            value="{{ old('payment_date', now()->format('Y-m-d')) }}" required
                            class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        @error('payment_date')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm text-gray-300 mb-2">
                            Método de pago
                        </label>

                        <select name="payment_method" id="payment_method" required
                            class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Seleccionar método</option>
                            @foreach(['Efectivo', 'Transferencia', 'QR', 'En Línea'] as $method)
                                <option value="{{ $method }}" @selected(old('payment_method') === $method)>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>

                        @error('payment_method')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-white mb-3">
                        Liquidaciones a pagar
                    </h4>

                    <div class="space-y-3">
                        @foreach($availableCommissions as $index => $item)
                            @php
                                $itemPendingBalance = max(
                                    0,
                                    (float) $item->barber_amount - (float) ($item->total_paid ?? 0)
                                );
                            @endphp

                            <div class="border border-zinc-700 rounded-lg p-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" name="allocations[{{ $index }}][commission_id]"
                                            value="{{ $item->id }}" x-model="selected"
                                            class="mt-1 rounded border-zinc-600 bg-zinc-900 text-blue-500 focus:ring-blue-500">

                                        <span>
                                            <span class="block text-sm font-medium text-white">
                                                Semana
                                                {{ $item->period->start_date->format('d/m/Y') }}
                                                al
                                                {{ $item->period->end_date->format('d/m/Y') }}
                                            </span>

                                            <span class="block text-xs text-gray-400 mt-1">
                                                Comisión: Gs. {{ number_format($item->barber_amount, 0, ',', '.') }}
                                            </span>

                                            <span class="block text-xs text-yellow-400 mt-1">
                                                Saldo pendiente: Gs. {{ number_format($itemPendingBalance, 0, ',', '.') }}
                                            </span>
                                        </span>
                                    </label>

                                    <div class="sm:w-56">
                                        <label for="allocation_amount_{{ $item->id }}" class="block text-xs text-gray-400 mb-1">
                                            Importe a pagar
                                        </label>

                                        <input type="number" name="allocations[{{ $index }}][amount]"
                                            id="allocation_amount_{{ $item->id }}" x-model.number="amounts['{{ $item->id }}']"
                                            :disabled="!selected.includes('{{ $item->id }}')" min="1"
                                            max="{{ $itemPendingBalance }}" step="1"
                                            class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 disabled:opacity-40 disabled:cursor-not-allowed focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('allocations')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    @error('allocations.*.amount')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm text-gray-300 mb-2">
                        Notas (opcional)
                    </label>

                    <textarea name="notes" id="notes" rows="2" maxlength="1000"
                        class="w-full rounded-lg bg-zinc-900 border border-zinc-700 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Observaciones sobre el pago...">{{ old('notes') }}</textarea>

                    @error('notes')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-zinc-700 pt-4">
                    <div>
                        <p class="text-sm text-gray-400">Total del pago</p>

                        <p class="text-2xl font-bold text-green-400">
                            Gs. <span x-text="formatAmount(totalAmount)"></span>
                        </p>
                    </div>

                    <button type="submit" :disabled="selected.length === 0 || totalAmount <= 0"
                        class="px-5 py-2.5 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-500 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        Registrar pago
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-5 mb-5">
            <h3 class="text-lg font-semibold text-white">
                Registro de pagos
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Este barbero no tiene liquidaciones cerradas con saldo pendiente.
            </p>
        </div>
    @endif -->

    <div class="bg-zinc-800 border border-zinc-700 rounded-xl overflow-hidden mb-5">

        <div class="p-5 border-b border-zinc-700">
            <h3 class="text-lg font-semibold text-white">
                Servicios realizados
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Detalle de los servicios incluídos en esta liquidación.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="bg-zinc-900 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Servicio</th>
                        <th class="px-4 py-3 text-center">Cantidad</th>
                        <th class="px-4 py-3 text-right">Precio unitario</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($commission->items as $item)
                        <tr class="border-t border-zinc-700">
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{  $item->sale?->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td class="px-4 py-3 font-medium text-white">
                                {{ $item->description }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{  $item->quantity }}
                            </td>

                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                Gs. {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right font-medium whitespace-nowrap">
                                Gs. {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                No hay servicios registrados en esta liquidación.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot class="bg-zinc-900 border-t border-zinc-700">
                    <tr>
                        <td class="px-4 py-3 text-right font-semibold text-white">
                            Total generado:
                        </td>

                        <td class="px-4 py-3 text-right font-bold text-white whitespace-nowrap">
                            Gs {{  number_format($commission->services_total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Historial de Pagos -->
    <div class="bg-zinc-800 border border-zinc-700 rounded-xl overflow-hidden">

        <div class="p-5 border-b border-zinc-700">
            <h3 class="text-lg font-semibold text-white">
                Historial de pagos
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Pagos aplicados a esta liquidación.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="bg-zinc-900 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Método</th>
                        <th class="px-4 py-3">Registrado por</th>
                        <th class="px-4 py-3">Notas</th>
                        <th class="px-4 py-3 text-right">Importe aplicado</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($commission->paymentAllocations as $allocation)
                        <tr class="border-t border-zinc-700">
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $allocation->payment->payment_date->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $allocation->payment->payment_method }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $allocation->payment->registeredBy->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $allocation->payment->notes ?: '-' }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                Gs. {{ number_format($allocation->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                Todavía no se registraron pagos para esta liquidación.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot class="bg-zinc-900 border-t border-zinc-700">
                    <tr>
                        <td class="px-4 py-3 text-right font-semibold text-white">
                            Total pagado:
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-400 whitespace-nowrap">
                            Gs. {{ number_format($totalPaid, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection