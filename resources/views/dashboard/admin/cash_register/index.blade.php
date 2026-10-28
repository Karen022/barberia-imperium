@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="p-6 text-gray-200">
        <h1 class="text-2xl font-bold mb-6">Caja</h1>

        <div class="flex gap-4 mb-6">
            <a href="?period=daily"
                class="px-4 py-2 rounded-xl {{ $period === 'daily' ? 'bg-yellow-600 text-black' : 'bg-zinc-800' }}">
                Hoy
            </a>

            <a href="?period=weekly"
                class="px-4 py-2 rounded-xl {{ $period === 'weekly' ? 'bg-yellow-600 text-black' : 'bg-zinc-800' }}">
                Semana
            </a>

            <a href="?period=monthly"
                class="px-4 py-2 rounded-xl {{ $period === 'monthly' ? 'bg-yellow-600 text-black' : 'bg-zinc-800' }}">
                Mes
            </a>

            <a href="?period=yearly"
                class="px-4 py-2 rounded-xl {{ $period === 'yearly' ? 'bg-yellow-600 text-black' : 'bg-zinc-800' }}">
                Año
            </a>
        </div>

        

        <!--- Resumen de Caja ----->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

            <!-- Total Productos -->
            <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6">
                <p class="text-gray-400 text-sm">
                    Total Productos
                </p>
                <p class="text-3xl font-bold text-yellow-500">
                    Gs.  {{ number_format($totalProducts, 0, ',', '.') }} 
                </p>

            </div>

            <!-- Total Services -->
            <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6">
                <p class="text-gray-400 text-sm">
                    Total Servicios
                </p>
                <p class="text-3xl font-bold text-yellow-500">
                    Gs. {{ number_format($totalServices, 0, ',', '.') }}
                </p>
            </div>

            <!-- Total Ingresos -->
            <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6">
                <p class="text-gray-400 text-sm">
                    Total Ingresos
                </p>
                <p class="text-3xl font-bold text-yellow-500">
                    Gs. {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
            </div>

            <!-- Total Egresos -->
            <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6">
                <p class="text-gray-400 text-sm">
                    Total Egresos
                </p>
                <p class="text-3xl font-bold text-yellow-500">
                    Gs.  {{ number_format($totalExpenses, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Resumen por metodo de pago -->
         <div class="mb-8">
            <h2 class="text-xl text-center font-semibold text-white mb-6">
                Resumen por método de pago
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($paymentSummary as $method => $summary)
                    
                    <div class="bg-black border border-zinc-700 rounded-2xl p-6">
                        <h3 class="text-g font-semibold text-white mb-4">
                            {{ $method }}
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-400">Ingresos</p>
                                <p class="text-lg font-semibold text-green-500">
                                    Gs. {{ number_format($summary['income'], 0, ',', '.') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-400">Egresos</p>
                                <p class="text-lg font-semibold text-red-500">
                                    Gs. {{ number_format($summary['expenses'], 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="border-t border-zinc-700 pt-3">
                                <p class="text-sm text-gray-400">Saldo disponible</p>
                                <p class="text-xl font-bold {{ $summary['balance'] >= 0 ? 'text-yellow-500' : 'text-red-500' }}">
                                    Gs {{ number_format($summary['balance'], 0, ',','.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
         </div>

        <!-- Saldo de Caja -->
        <div class="flex justify-center mb-8">
            <div class="w-full md:w-2/3 lg:w-1/2 bg-black/40 border-2 border-yellow-600/40 rounded-2xl p-6 text-center">
                <p class="text-gray-400  mb-2">
                    Saldo de Caja
                </p>
                <p class="text-4xl font-bold {{ $cashBalance >= 0 ? 'text-yellow-500' : 'text-red-500'}}">
                    Gs. {{ number_format($cashBalance, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">

            <h2 class="text-2xl font-bold text-white">Detalles Caja</h2>
            <form action="{{ route('dashboard.cash.index') }}" method="GET" class="mb-6">
                <input type="hidden" name="period" value="{{ $period }}">

                <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    <div>
                        <label for="payment_method" 
                            class="block text-sm text-zinc-300 mb-2"
                        >
                            Filtrar por método de pago
                        </label>

                        <select name="payment_method" id="payment_method" 
                            class="bg-black border border-zinc-700 rounded-xl px-4 py-3 text-white focus:ring-0 focus:outline-none focus:border-yellow-500"
                        >
                            <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>
                                Todos los métodos
                            </option>
                            
                            <option value="Efectivo" {{ $paymentMethod === 'Efectivo' ? 'selected' : '' }}>
                                Efectivo
                            </option>

                            <option value="Transferencia" {{ $paymentMethod === 'Transferencia' ? 'selected' : '' }}>
                                Transferencia
                            </option>

                            <option value="QR" {{ $paymentMethod === 'QR' ? 'selected' : '' }}>
                                QR
                            </option>

                            <option value="En Línea" {{ $paymentMethod === 'En Línea' ? 'selected' : '' }}>
                                En Línea
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="px-5 py-3 rounded-xl bg-yellow-600 text-black font-semibold hover:bg-yellow-500 transition">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Ingresos-->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-white mb-4">
                Detalle de Ingresos
            </h2>

            <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-800">
                        <tr>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Concepto</th>
                            <th class="px-4 py-3 text-left">Cantidad</th>
                            <th class="px-4 py-3 text-left">Precio</th>
                            <th class="px-4 py-3 text-left">Total</th>
                            <th class="px-4 py-3 text-left">Atendido por</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Método</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($sales as $sale)

                            {{-- Productos --}}
                            @foreach ($sale->saleDetails as $detail)
                                <tr class="border-t border-zinc-700 hover:bg-zinc-800/50 transition">
                                    <td class="px-4 py-3">
                                        Producto
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $detail->product->name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $detail->quantity }}
                                    </td>

                                    <td class="px-4 py-3">
                                        Gs {{ number_format($detail->product->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-yellow-500 font-semibold">
                                        Gs {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $sale->user->name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $detail->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-4 py-3 font-semibold">
                                        {{ $sale->payment_method ?? 'No especificado' }}
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Servicios --}}
                            @foreach ($sale->services as $service)
                                <tr class="border-t border-zinc-700 hover:bg-zinc-800/50 transition">
                                    <td class="px-4 py-3">
                                        Servicio
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $service->name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        1
                                    </td>

                                    <td class="px-4 py-3">
                                        Gs {{ number_format($service->pivot->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-yellow-500 font-semibold">
                                        Gs {{ number_format($service->pivot->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $sale->user->name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $service->pivot->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-4 py-3 font-semibold">
                                        {{ $sale->payment_method ?? 'No especificado' }}
                                    </td>
                                </tr>
                            @endforeach

                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-400">
                                    No hay registros aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($sales->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-zinc-700">

                    <p class="text-sm text-zinc-400">
                        Mostrando
                        <span class="text-yellow-500 font-semibold">{{ $sales->firstItem() }}</span>
                        a
                        <span class="text-yellow-500 font-semibold">{{ $sales->lastItem() }}</span>
                        de
                        <span class="text-zinc-200 font-semibold">{{ $sales->total() }}</span>
                        ingresos
                    </p>

                        <div class="flex items-center gap-2">

                            {{-- Anterior --}}
                            @if ($sales->onFirstPage())
                                <span class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-600 cursor-not-allowed">
                                    <i class="fa-solid fa-angle-left"></i>
                                </span>
                            @else
                                <a href="{{ $sales->appends(request()->except('sales_page'))->url($sales->currentPage() - 1) }}"
                                    class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-yellow-600 hover:text-yellow-500 transition">
                                    <i class="fa-solid fa-angle-left"></i>
                                </a>
                            @endif

                            {{-- Página actual --}}
                            <span class="px-4 py-2 rounded-lg bg-yellow-600/20 border border-yellow-600/40 text-yellow-500 font-semibold">
                                {{ $sales->currentPage() }}
                            </span>

                            {{-- Siguiente --}}
                            @if ($sales->hasMorePages())
                                <a href="{{ $sales->appends(request()->except('sales_page'))->url($sales->currentPage() + 1) }}"
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

        <!-- Table Egresos -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-white mb-4">
                Detalle de Egresos
            </h2>

            <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-800">
                        <tr class="border-t border-zinc-700 ">
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Concepto</th>
                            <th class="px-4 py-3 text-left">Categoría</th>
                            <th class="px-4 py-3 text-left">Monto</th>
                            <th class="px-4 py-3 text-left">Método</th>
                            <th class="px-4 py-3 text-left">Registrado por</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($expenses as $expense)

                            <tr class="border-t border-zinc-700 hover:bg-zinc-800/50 transition">

                                <td class="px-4 py-3">
                                    {{ $expense->expense_date->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $expense->concept }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $expense->category }}
                                </td>

                                <td class="px-4 py-3 text-yellow-500 font-semibold">
                                   Gs. {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3  font-semibold">
                                    {{ $expense->payment_method }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $expense->user->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                                    No hay egresos registrados en este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($expenses->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-zinc-700">

                    <p class="text-sm text-zinc-400">
                        Mostrando
                        <span class="text-yellow-500 font-semibold">{{ $expenses->firstItem() }}</span>
                        a
                        <span class="text-yellow-500 font-semibold">{{ $expenses->lastItem() }}</span>
                        de
                        <span class="text-zinc-200 font-semibold">{{ $expenses->total() }}</span>
                        egresos
                    </p>

                        <div class="flex items-center gap-2">

                            {{-- Anterior --}}
                            @if ($expenses->onFirstPage())
                                <span class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-600 cursor-not-allowed">
                                    <i class="fa-solid fa-angle-left"></i>
                                </span>
                            @else
                                <a href="{{ $expenses->appends(request()->except('expenses_page'))->url($expenses->currentPage() - 1) }}"
                                    class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-yellow-600 hover:text-yellow-500 transition">
                                    <i class="fa-solid fa-angle-left"></i>
                                </a>
                            @endif

                            {{-- Página actual --}}
                            <span class="px-4 py-2 rounded-lg bg-yellow-600/20 border border-yellow-600/40 text-yellow-500 font-semibold">
                                {{ $expenses->currentPage() }}
                            </span>

                            {{-- Siguiente --}}
                            @if ($expenses->hasMorePages())
                                <a href="{{ $expenses->appends(request()->except('expenses_page'))->url($expenses->currentPage() + 1) }}"
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

    </div>
@endsection