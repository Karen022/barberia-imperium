@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">

        <a href="{{ route('dashboard.sales.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>

        <div class="max-w-3xl mx-auto text-gray-200">

            <h1 class="text-2xl text-center font-bold mb-6">
                Editar venta
            </h1>

            <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">

                <form
                    action="{{ route('dashboard.sales.update', $sale->id) }}"
                    method="POST"
                    class="space-y-6"
                >
                    @csrf
                    @method('PUT')


                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-800/50 bg-red-950/30 p-4">

                            <p class="text-sm font-semibold text-red-400 mb-2">
                                No se pudo guardar la venta:
                            </p>

                            <ul class="space-y-1 text-sm text-red-300">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    {{-- Cliente --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Cliente (opcional)
                        </label>

                        <select
                            name="client_id"
                            class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0"
                        >
                            <option value="">Consumidor final</option>

                            @foreach ($clients as $client)
                                <option
                                    value="{{ $client->id }}"
                                    {{ old('client_id', $sale->client_id) == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('client_id')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Productos --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">

                            <label class="block text-sm text-zinc-300">
                                Productos
                            </label>

                            <button
                                type="button"
                                id="add-product"
                                class="bg-yellow-600 hover:bg-yellow-500 text-black px-3 py-2 rounded-xl font-semibold text-sm transition"
                            >
                                <i class="fa-solid fa-plus mr-1"></i>
                                Agregar producto
                            </button>

                        </div>


                        <div
                            id="products-container"
                            class="space-y-4"
                            data-next-index="{{ $sale->saleDetails->count() }}"
                        >

                            {{-- Productos existentes --}}
                            @foreach ($sale->saleDetails as $index => $detail)

                                <div class="product-row grid grid-cols-3 gap-4 items-end">

                                    <div class="col-span-2">

                                        <label class="block text-sm text-zinc-300 mb-1">
                                            Producto
                                        </label>

                                        <select
                                            name="items[{{ $index }}][id]"
                                            class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0"
                                        >
                                            <option value="">
                                                Seleccionar producto
                                            </option>

                                            @foreach ($products as $product)
                                                <option
                                                    value="{{ $product->id }}"
                                                    {{ old("items.$index.id", $detail->product_id) == $product->id ? 'selected' : '' }}
                                                >
                                                    {{ $product->name }} (Stock: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </select>

                                        <input
                                            type="hidden"
                                            name="items[{{ $index }}][type]"
                                            value="product"
                                        >

                                    </div>


                                    <div class="flex gap-2">

                                        <div class="flex-1">

                                            <label class="block text-sm text-zinc-300 mb-1">
                                                Cantidad
                                            </label>

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][quantity]"
                                                min="1"
                                                value="{{ old("items.$index.quantity", $detail->quantity) }}"
                                                class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0"
                                            >

                                        </div>

                                        <button
                                            type="button"
                                            class="remove-product bg-yellow-600 hover:bg-yellow-500 text-black px-3 py-2 rounded-xl font-semibold self-end"
                                            title="Quitar producto"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </div>
                                    @error('items')
                                        <p class="mt-1 text-sm text-red-400">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            @endforeach

                        </div>
                    </div>


                    {{-- Template para nuevos productos --}}
                    <template id="product-row-template">

                        <div class="product-row grid grid-cols-3 gap-4 items-end">

                            <div class="col-span-2">

                                <label class="block text-sm text-zinc-300 mb-1">
                                    Producto
                                </label>

                                <select
                                    data-field="id"
                                    class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0"
                                >
                                    <option value="">
                                        Seleccionar producto
                                    </option>

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Stock: {{ $product->stock }})
                                        </option>
                                    @endforeach

                                </select>

                                <input
                                    type="hidden"
                                    data-field="type"
                                    value="product"
                                >

                            </div>


                            <div class="flex gap-2">

                                <div class="flex-1">

                                    <label class="block text-sm text-zinc-300 mb-1">
                                        Cantidad
                                    </label>

                                    <input
                                        type="number"
                                        data-field="quantity"
                                        min="1"
                                        value="1"
                                        class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-yellow-500 focus:ring-0"
                                    >

                                </div>

                                <button
                                    type="button"
                                    class="remove-product bg-yellow-600 hover:bg-yellow-500 text-black px-3 py-2 rounded-xl font-semibold self-end"
                                    title="Quitar producto"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </div>

                        </div>

                    </template>


                    {{-- Botón guardar --}}
                    <div class="pt-4">

                        <button
                            type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition"
                        >
                            Guardar cambios
                        </button>

                    </div>


                    {{-- Error general --}}
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