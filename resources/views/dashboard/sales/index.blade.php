@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- Eliminamos paddings redundantes que puedan apretar el layout --}}
    <div class="w-full">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold">Ventas</h1>

            <a href="{{ route('dashboard.sales.create') }}"
                class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold text-center">
                Nueva Venta
            </a>
        </div>

        
        <div class="w-full bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">

            
            <table class="w-full table-auto visual-table">
                <thead class="bg-zinc-900/80 text-gray-300">
                    <tr>
                        <th class="px-5 py-4 text-left">#</th>
                        <th class="px-5 py-4 text-left ">Detalle de Venta</th>
                        <th class="px-5 py-4 text-center">Cant.</th>
                        <th class="px-5 py-4 text-left">Total</th>
                        <th class="px-5 py-4 text-left">Fecha</th>
                        <th class="px-5 py-4 text-left">Atendido por</th>
                        <th class="px-5 py-4 text-left">Cliente</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                        <tr class="bg-zinc-800/40 font-medium border-t border-zinc-700 hover:bg-zinc-800/70 text-gray-200 align-middle">
                            {{-- ID --}}
                            <td class="px-5 py-4 text-amber-500 font-bold ">#{{ $sale->id }}</td>

                            {{-- DETALLE --}}
                            <td class="px-5 py-4 text-sm text-gray-400">
                                <div class="flex flex-wrap gap-1.5 max-w-md">
                                    @foreach($sale->saleDetails as $detail)
                                        <span class="bg-zinc-700/50 px-2 py-1 rounded text-gray-300 border border-zinc-700/30">
                                            {{ $detail->product->name ?? 'Producto eliminado' }}
                                        </span>
                                    @endforeach

                                    @foreach($sale->services as $service)
                                        <span class="bg-zinc-700/50 px-2 py-1 rounded text-gray-300 border border-zinc-700/30">
                                            {{ $service->name ?? 'Servicio eliminado' }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- CANTIDAD --}}
                            <td class="px-5 py-4 text-sm text-center text-zinc-400 font-mono">
                                <div class="flex flex-col gap-1">
                                    @foreach($sale->saleDetails as $detail)
                                        <div>{{ $detail->quantity }}</div>
                                    @endforeach
                                    @foreach($sale->services as $service)
                                        <div>1</div>
                                    @endforeach
                                </div>
                            </td>
                            
                            {{-- TOTAL --}}
                            <td class="px-5 py-4 text-amber-500 font-bold whitespace-nowrap">
                                Gs {{ number_format($sale->total, 0, ',', '.') }}
                            </td>

                            {{-- FECHA --}}
                            <td class="px-5 py-4 text-sm text-gray-400 whitespace-nowrap">
                                {{ $sale->created_at?->format('d/m/Y H:i') }}
                            </td>

                            {{-- ATENDIDO POR --}}
                            <td class="px-5 py-4 text-sm whitespace-nowrap">
                                <span class="bg-zinc-900/40 border border-zinc-700 px-2.5 py-1 rounded text-zinc-300">
                                    {{ $sale->user->name ?? 'Sistema' }}
                                </span>
                            </td>

                            {{-- CLIENTE --}}
                            <td class="px-5 py-4 text-sm whitespace-nowrap">
                                @if($sale->client)
                                    <span class="bg-amber-950/20 border border-amber-900/50 px-2.5 py-1 rounded text-amber-300">
                                        {{ $sale->client->name }}
                                    </span>
                                @else
                                    <span class="text-zinc-500 italic text-xs">Sin registrar</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-6 text-center text-gray-400">
                                No hay registros aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
@endsection
