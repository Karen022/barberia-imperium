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
        </div>

        <!-- Total Products -->
        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mb-6">
            <p class="text-gray-400  text-sm">Total Productos</p>
            <p class="text-3xl font-bold text-yellow-500">
                Gs {{ number_format($totalProducts, 0, ',', '.') }}
            </p>
        </div>

        <!-- Total Services -->
        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mb-6">
            <p class="text-gray-400  text-sm">Total Servicios</p>
            <p class="text-3xl font-bold text-yellow-500">
                Gs {{ number_format($totalServices, 0, ',', '.') }}
            </p>
        </div>
        <!-- Table -->
        <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-900/80">
                    <tr>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Concepto</th>
                        <th class="px-4 py-3 text-left">Cantidad</th>
                        <th class="px-4 py-3 text-left">Precio</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Atendido por</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sales as $sale)

                        {{-- Productos --}}
                        @foreach ($sale->saleDetails as $detail)
                            <tr class="border-t border-zinc-700">
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
                            </tr>
                        @endforeach

                        {{-- Servicios --}}
                        @foreach ($sale->services as $service)
                            <tr class="border-t border-zinc-700">
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
                            </tr>
                        @endforeach

                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                                No hay registros aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection