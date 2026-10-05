@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6 text-gray-200">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold mb-6">Barberos</h1>

            <a href="{{ route('dashboard.barbers.create') }}"
                class="bg-yellow-600 hover:bg-yellow-400 text-black px-4 py-2 rounded-xl font-semibold">
                Nuevo Barbero
            </a>
        </div>



        <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-900/80 text-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Acciones</th>

                    </tr>
                </thead>

                <tbody>
                    @forelse ($barbers as $barber)
                        <tr class="border-t border-zinc-700">
                            <td class="px-4 py-3">{{ $barber->name }}</td>
                            <td class="px-4 py-3">{{ $barber->email }}</td>
                            <td class="px-4 py-3">
                                @if ($barber->status === 'active')
                                    <span class="px-3 py-1 text-sm  text-green-400">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm  text-red-400">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('dashboard.barbers.toggleStatus', $barber) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    @if ($barber->status === 'active')
                                        <button type="submit"
                                            class="border border-red-600/30 text-red-400 hover:bg-red-600/30 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                                            Desactivar
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="border border-green-600/30 text-green-400 hover:bg-green-600/30 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                                            Activar
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-400">
                                No hay barberos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>


@endsection