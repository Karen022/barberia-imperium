@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="p-6">

        <a href="{{ route('dashboard.expenses.index') }}"
            class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
            ← Volver
        </a>

        <div class="max-w-3xl mx-auto">
            <h1 class="text-2xl text-center text-white font-bold mb-6">
                Editar Egreso
            </h1>

            <div class="bg-zinc-900 border border-zinc-700 rounded-2xl p-6">

                <form action="{{ route('dashboard.expenses.update', $expense) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="expense_date" class="text-sm text-zinc-300">
                            Fecha del gasto
                        </label>

                        <input
                            type="date"
                            id="expense_date"
                            name="expense_date"
                            value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12"
                        >

                        @error('expense_date')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="concept" class="text-sm text-zinc-300">
                            Concepto
                        </label>

                        <input
                            type="text"
                            id="concept"
                            name="concept"
                            value="{{ old('concept', $expense->concept ) }}"
                            placeholder="Ej. Pago de electricidad"
                            maxlength="255"
                            required
                            class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12"
                        >

                        @error('concept')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="text-sm text-zinc-300">
                            Categoría
                        </label>

                        <select
                            id="category"
                            name="category"
                            required
                            class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12"
                        >
                            <option value="">Seleccionar categoría</option>

                            @foreach ([
                                'Personal',
                                'Servicios y alquiler',
                                'Insumos',
                                'Limpieza e higiene',
                                'Equipamiento',
                                'Mantenimiento y reparaciones',
                                'Administración',
                                'Publicidad y marketing',
                                'Otros',
                            ] as $category)
                                <option value="{{ $category }}"
                                    @selected(old('category', $expense->category) === $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>

                        @error('category')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label for="amount" class="text-sm text-zinc-300">
                                Monto
                            </label>

                            <input
                                type="number"
                                id="amount"
                                name="amount"
                                value="{{ old('amount', number_format($expense->amount, 0, '.', '')) }}"
                                min="1"
                                step="0.01"
                                placeholder="0"
                                required
                                class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12"
                            >

                            @error('amount')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="text-sm text-zinc-300">
                                Método de pago
                            </label>

                            <select
                                id="payment_method"
                                name="payment_method"
                                required
                                class="w-full rounded-xl bg-black/40 px-4 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 h-12"
                            >
                                <option value="">Seleccionar método</option>

                                @foreach ([
                                    'Efectivo',
                                    'Transferencia',
                                    'QR',
                                    'En Línea',
                                ] as $paymentMethod)
                                    <option value="{{ $paymentMethod }}"
                                        @selected(old('payment_method', $expense->payment_method)=== $paymentMethod)>
                                        {{ $paymentMethod }}
                                    </option>
                                @endforeach
                            </select>

                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div>
                        <label for="description" class="text-sm text-zinc-300">
                            Descripción <span class="text-zinc-500">(opcional)</span>
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            maxlength="1000"
                            placeholder="Agrega algún detalle adicional sobre el gasto..."
                            class="w-full rounded-xl bg-black/40 px-4 py-3 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white border border-zinc-700 mt-1 resize-none"
                        >{{ old('description', $expense->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition"
                        >
                            Actualizar 
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection