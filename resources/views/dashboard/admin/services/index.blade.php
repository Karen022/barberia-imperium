@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="px-6 text-gray-200">
        <div class="flex items-center justify-between mb-6">

            <h1 class="text-2xl font-bold mn-4">Servicios</h1>

            <a href="{{ route('dashboard.services.create') }}"
                class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-bold">
                Nuevo Servicio
            </a>
        </div>

        <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">

            <table class="w-full">
                <thead class="bg-zinc-800 text-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left"> # </th>
                        <th class="px-4 py-3 text-left">Imagen</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Precio</th>
                        <th class="px-4 py-3 text-left">Duracion</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th colspan="2" class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($services as $service)
                        <tr class="border-t border-zinc-700 hover:bg-zinc-800/50 transition">
                            <td class="px-4 py-3">{{ $service->id }}</td>
                            <td class="px-4 py-3">
                                @if($service->image)
                                    <img src="{{ asset('storage/services/' . $service->image) }}" alt="Imagen"
                                        class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-zinc-300 text-sm">Sin Imagen</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $service->name }}</td>
                            <td class="px-4 py-3">Gs {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $service->duration_min }} min</td>
                            <td class="px-4 py-3">{{ $service->description }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('dashboard.services.edit', $service) }}"
                                    class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td class="px-4 py-3">

                                <x-confirm-dialog title="¿Eliminar servicio?"
                                    message="Esta acción eliminará el servicio permanentemente.">
                                    <x-slot:trigger>
                                        <span
                                            class="bg-red-600/70 hover:bg-red-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center cursor-pointer">
                                            <i class="fa-solid fa-trash"></i>
                                        </span>
                                    </x-slot:trigger>

                                    <x-slot:confirm>
                                        <form action="{{ route('dashboard.services.destroy', $service) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                                                Eliminar
                                            </button>
                                        </form>
                                    </x-slot:confirm>
                                </x-confirm-dialog>
                            

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-400">
                                No hay registros aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($services->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 border-t border-zinc-700">

                    <p class="text-sm text-zinc-400">
                        Mostrando
                        <span class="text-yellow-500 font-semibold">{{ $services->firstItem() }}</span>
                        a
                        <span class="text-yellow-500 font-semibold">{{ $services->lastItem() }}</span>
                        de
                        <span class="text-zinc-200 font-semibold">{{ $services->total() }}</span>
                        servicios
                    </p>

                        <div class="flex items-center gap-2">

                            {{-- Anterior --}}
                            @if ($services->onFirstPage())
                                <span class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-600 cursor-not-allowed">
                                    <i class="fa-solid fa-angle-left"></i>
                                </span>
                            @else
                                <a href="{{ $services->previousPageUrl() }}"
                                    class="px-3 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:border-yellow-600 hover:text-yellow-500 transition">
                                    <i class="fa-solid fa-angle-left"></i>
                                </a>
                            @endif

                            {{-- Página actual --}}
                            <span class="px-4 py-2 rounded-lg bg-yellow-600/20 border border-yellow-600/40 text-yellow-500 font-semibold">
                                {{ $services->currentPage() }}
                            </span>

                            {{-- Siguiente --}}
                            @if ($services->hasMorePages())
                                <a href="{{ $services->nextPageUrl() }}"
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