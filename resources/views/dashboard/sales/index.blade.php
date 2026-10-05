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
                        @role('admin')
                        <th class="px-5 py-4 text-left">Estado</th>
                        <th class="px-5 py-4 text-left">Acciones</th>
                        @endrole

                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                        <tr
                            class="bg-zinc-800/40 font-medium border-t border-zinc-700 hover:bg-zinc-800/70 text-gray-200 align-middle">
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
                            <td class="px-5 py-4 text-amber-500 font-bold ">
                                Gs {{ number_format($sale->total, 0, ',', '.') }}
                            </td>

                            {{-- FECHA --}}
                            <td class="px-5 py-4 text-sm text-gray-400 ">
                                {{ $sale->created_at?->format('d/m/Y H:i') }}
                            </td>

                            {{-- ATENDIDO POR --}}
                            <td class="px-5 py-4 text-sm ">
                                <span class="px-2.5 py-1 rounded text-zinc-300">
                                    {{ $sale->user->name ?? 'Sistema' }}
                                </span>
                            </td>

                            {{-- CLIENTE --}}
                            <td class="px-5 py-4 text-sm ">
                                @if($sale->client)
                                    <span class="px-2.5 py-1 rounded text-amber-300">
                                        {{ $sale->client->name }}
                                    </span>
                                @else
                                    <span class="text-zinc-500 italic text-xs">Sin registrar</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            @role('admin')
                            <td class="px-5 py-4 text-sm ">
                                @if($sale->status === 'completed')
                                    <span class="px-2.5 py-1 rounded text-green-300">
                                        Completada
                                    </span>
                                @elseif($sale->status === 'cancelled')
                                    <span class="px-2.5 py-1 rounded text-red-300">
                                        Anulada
                                    </span>
                                @endif
                            </td>

                            {{-- ACCIONES --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if($sale->status === 'completed')

                                        @if($sale->saleDetails->isNotEmpty())
                                            {{-- Editar --}}
                                            <a href="{{ route('dashboard.sales.edit', $sale) }}"
                                                class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center"
                                                title="Editar venta">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endif
                                        {{-- Anular --}}
                                        <x-confirm-dialog title="¿Anular venta?"
                                            message="Esta acción anulará la venta y restaurará el stock de los productos.">
                                            <x-slot:trigger>
                                                <span
                                                    class="bg-red-600/70 hover:bg-red-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center cursor-pointer"
                                                    title="Anular venta">
                                                    <i class="fa-solid fa-ban"></i>
                                                </span>
                                            </x-slot:trigger>

                                            <x-slot:confirm>
                                                <form action="{{ route('dashboard.sales.cancel', $sale) }}" method="POST"
                                                    style="display:inline">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                                                        Anular
                                                    </button>
                                                </form>
                                            </x-slot:confirm>
                                        </x-confirm-dialog>

                                    @else
                                        <span class="text-zinc-600 text-sm">
                                            —
                                        </span>
                                    @endif
                                </div>
                            </td>
                            @endrole
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