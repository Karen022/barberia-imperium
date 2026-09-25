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
                <thead class="bg-zinc-900/80 text-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left"> # </th>
                        <th class="px-4 py-3 text-left">Imagen</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Precio</th>
                        <th class="px-4 py-3 text-left">Duracion</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-left"></th>
                        <th class="px-4 py-3 text-left"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($services as $service)
                        <tr class="border-t border-zinc-700">
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
                            <td class="px-4 py-3 text-center">{{ $service->duration_min }} min</td>
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
                                            class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center cursor-pointer">
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
        </div>

    </div>

@endsection