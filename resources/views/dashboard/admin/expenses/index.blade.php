@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-white">
                    Egresos
                </h1>

            </div>

            <a href="{{ route('dashboard.expenses.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-black bg-yellow-500 font-medium hover:bg-yellow-400 transition">
                Nuevo egreso
            </a>
        </div>

        <div class="bg-black/40 border border-zinc-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-800 text-zinc-300">
                        <tr>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Concepto</th>
                            <th class="px-4 py-3">Categoría</th>
                            <th class="px-4 py-3">Método</th>
                            <th class="px-4 py-3">Monto</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Registrado por</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($expenses as $expense)
                            <tr class="border-t border-zinc-700 hover:bg-zinc-800/50 transition">
                                <td class="px-4 py-3 text-zinc-300 whitespace-nowrap">
                                    {{ $expense->expense_date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ $expense->concept }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ $expense->category }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ $expense->payment_method }}
                                </td>
                                <td class="px-4 py-3 text-yellow-500 font-medium whitespace-nowrap">
                                    Gs {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ $expense->description }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ $expense->user->name }}
                                </td>

                                <td class="px-5 py-4 text-sm ">
                                    @if($expense->status === 'active')
                                        <span class="px-2.5 py-1 rounded text-green-300">
                                            Registrado
                                        </span>
                                    @elseif($expense->status === 'cancelled')
                                        <span class="px-2.5 py-1 rounded text-red-300">
                                            Anulada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($expense->status === 'active')

                                            <a href="{{ route('dashboard.expenses.edit', $expense) }}"
                                                class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center"
                                                title="Editar egreso">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <x-confirm-dialog title="¿Anular egreso?" message="Esta acción anulará el egreso.">
                                                <x-slot:trigger>
                                                    <span
                                                        class="bg-red-600/70 hover:bg-red-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center cursor-pointer"
                                                        title="Anular egreso">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </span>
                                                </x-slot:trigger>

                                                <x-slot:confirm>
                                                    <form action="{{ route('dashboard.expenses.cancel', $expense) }}" method="POST">
                                                        @csrf

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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-zinc-500">
                                    No hay egresos registrados todavía.
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
                                <a href="{{ $expenses->previousPageUrl() }}"
                                    class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-yellow-600 hover:text-yellow-500 transition">
                                    <i class="fa-solid fa-angle-left"></i>
                                </a>
                            @endif

                            {{-- Página actual --}}
                            <span class="px-4 py-2 rounded-lg bg-yellow-600/20 border border-yellow-600/40 text-yellow-500 font-semibold">
                                {{ $expenses->currentPage() }}
                            </span>

                            {{-- Siguiente --}}
                            @if ($sales->hasMorePages())
                                <a href="{{ $sales->nextPageUrl() }}"
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