@extends('dashboard.layouts.dashboard')

@section('content')
<div class="p-6 text-gray-200">

    <h1 class="text-2xl font-bold mb-4">Clientes</h1>

    <div class="bg-black/40 border border-zinc-700 rounded-xl overflow-x-auto">

        <table class="w-full">
            <thead class="bg-zinc-900/80 text-gray-300">
                <tr>
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Teléfono</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clients as $client)
                    <tr class="border-t border-zinc-700">
                        <td class="px-4 py-3">{{ $client->name }}</td>
                        <td class="px-4 py-3">{{ $client->email }}</td>
                        <td class="px-4 py-3">{{ $client->phone ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                            No hay clientes registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
   

</div>
@endsection
