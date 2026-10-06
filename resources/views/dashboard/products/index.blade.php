@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="px-6 text-gray-200">
        <div class="flex items-center justify-between mb-6">

            <h1 class="text-2xl font-bold mn-4 ">Productos</h1>

            <a href="{{ route('dashboard.products.create') }}"
                class="bg-yellow-600 hover:bg-yellow-400 text-black px-5 py-3 rounded-xl font-bold">Nuevo Producto
            </a>
        </div>
        <div class="bg-black/40 border border-zinc-700 rounded-2xl  overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-900/80 text-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left">Id</th>
                        <th class="px-4 py-3 text-left">Imagen</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-left">Precio</th>
                        <th class="px-4 py-3 text-left">Cantidad</th>
                        <th class="px-4 py-3 text-left">Destacado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-t border-zinc-700">
                            <td class="px-4 py-3">{{ $product->id }}</td>
                            <td class="px-4 py-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="Imagen"
                                        class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-zinc-300 text-sm">Sin Imagen</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $product->description }}</td>
                            <td class="px-4 py-3">Gs {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $product->stock }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('dashboard.products.toggle-featured', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="hover:scale-105 hover:font-bold transition"
                                        title="{{ $product->is_featured ? 'Quitar de destacados' : 'Destacar producto' }}">
                                        {{ $product->is_featured ? '⭐' : '☆ ' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('dashboard.products.edit', $product) }}"
                                    class="bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td class="px-4 py-3">


                                <x-confirm-dialog title="¿Eliminar producto?"
                                    message="Esta acción eliminará el producto permanentemente.">
                                    <x-slot:trigger>
                                        <span
                                            class="bg-red-600/70 hover:bg-red-500 text-black px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center cursor-pointer">
                                            <i class="fa-solid fa-trash"></i>
                                        </span>
                                    </x-slot:trigger>

                                    <x-slot:confirm>
                                        <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST"
                                            style="display:inline">
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
                            <td colspan="5" class="text-center text-zinc-500 text-xs py-4">
                                (No hay registros aún)
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

@endsection