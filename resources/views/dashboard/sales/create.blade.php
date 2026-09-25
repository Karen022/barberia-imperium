@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">

        <a href="{{ route('dashboard.sales.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>

        <div class="max-w-3xl mx-auto text-gray-200">
            <h1 class="text-2xl text-center font-bold mb-6">Registrar venta</h1>

            <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">
                <form action="{{ route('dashboard.sales.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Cliente --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Cliente (opcional)
                        </label>
                        <select name="client_id"
                            class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white">
                            <option value="">Consumidor final</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Producto --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm text-zinc-300 mb-1">
                                Producto
                            </label>
                            <select name="items[0][id]"
                                class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white">
                                <option value="">Seleccionar producto</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" name="items[0][type]" value="product">
                        </div>

                        <div>
                            <label class="block text-sm text-zinc-300 mb-1">
                                Cantidad
                            </label>
                            <input type="number" name="items[0][quantity]" min="1" value="1"
                                class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white">

                            @error('quantity')
                                <p class="mt-1 text-sm text-red-400">
                                    {{  $message  }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botón --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition">
                            Guardar venta
                        </button>
                    </div>

                    @if($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

@endsection