@extends('dashboard.layouts.dashboard')

@section('content')


    <div class="p-6 text-gray-200">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold mb-6">Agenda de Turnos</h1>

            <a href="{{ route('dashboard.appointments.create') }}"
                class="bg-yellow-600 hover:bg-yellow-400 text-black px-4 py-2 rounded-xl font-semibold">
                Nuevo Turno
            </a>
        </div>


        <div class="bg-black/40 border border-zinc-700 rounded-2xl overflow-x-auto">

            <div class="flex justify-center items-center mb-6 pt-5 ">

                <h2 class="text-lg text-zinc-200 font-semibold">
                    Turnos Reservados
                </h2>
            </div>


            {{-- Tabla --}}
            <table class="w-full text-sm text-zinc-300">
                <thead class="text-left border-b bg-zinc-800 border-zinc-700 ">
                    <tr>
                        <th class="px-4 py-3 text-left">Clientes</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Hora</th>
                        <th class="px-4 py-3 text-left">Servicio</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th colspan="2" class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="border-b border-zinc-700 hover:bg-zinc-800/50 transition">
                            <td class="px-4 py-3 text-left">{{ $appointment->client?->name ?? 'Sin cliente' }}</td>
                            <td class="px-4 py-3 text-left">
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                @forelse ($appointment->services as $service )
                                    <div>
                                        {{ $service->name }}
                                    </div>
                                @empty
                                    <span class="text-neutral-500">
                                        Sin servicios
                                    </span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-left">

                                @if ($appointment->status === 'pending')
                                    <span class="px-3 py-1 text-sm  text-yellow-400">
                                        Pendiente
                                    </span>
                                @elseif ($appointment->status === 'completed')
                                    <span class="px-3 py-1 text-sm  text-green-400">
                                        Completado
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm  text-red-400">
                                        Cancelado
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-left">
                                <div class="flex items-center gap-2 flex-wrap">
                                    {{-- Editar completo (admin) --}}
                                    
                                    <a href="{{ route('dashboard.appointments.edit', $appointment) }}"
                                        class="border border-yellow-600 hover:bg-yellow-600/30 text-yellow-600 px-5 py-3 rounded-xl font-semibold">
                                        Editar
                                    </a>
                                    

                                    {{-- Acciones rápidas --}}
                                    @if(
                                            $appointment->status === 'pending' &&
                                            (auth()->user()->hasRole('admin') || auth()->id() === $appointment->barber_id)
                                        )

                                        {{-- Completar --}}
                                        

                                        <button type="button"
                                            @click="$dispatch('open-payment-modal', {
                                                    action: '{{ route('dashboard.appointments.status', [$appointment, 'completed']) }}',
                                                    mode:complete
                                                })"
                                            class="border border-green-600/30 text-green-400 hover:bg-green-600/30 px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center transition"
                                            title="Completar turno"
                                        >
                                            Completar
                                        </button>
                                        


                                        {{-- Cancelar --}}
                                        <form method="POST"
                                            action="{{ route('dashboard.appointments.status', [$appointment, 'cancelled']) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="border border-red-600/30 text-red-400 hover:bg-red-600/30 px-5 py-3 rounded-xl font-semibold inline-flex items-center justify-center transition"
                                                title="Cancelar turno">
                                                Cancelar
                                            </button>
                                        </form>

                                    @endif

                                    @if (
                                        $appointment->status === 'completed' &&
                                        (auth()->user()->hasRole('admin') || auth()->id() === $appointment->barber_id)
                                    )
                                        <button
                                            type="button"
                                            @click="$dispatch('open-payment-modal', {
                                                action: '{{ route('dashboard.appointments.payment-method', $appointment) }}',
                                                mode:'update'
                                            })"
                                            class="border border-gray-300/30 text-gray-300 hover:bg-gray-200/30 px-5 py-3 rounded-xl font-semibold inline-flex justidy-center transition"
                                            title="Cambiar método de pago"
                                        >
                                            Cambiar pago
                                        </button>  
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-zinc-500 text-xs py-4">
                                (No hay registros aún)
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- -Bloquear Horarios --}}
        @role('admin')
            <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mt-6">

                <h2 class="text-lg text-zinc-200 font-semibold mb-5">
                    Bloquear horario de un barbero
                </h2>

                <form action="{{ route('dashboard.appointments.unavailability.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Barbero --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Barbero
                        </label>

                        <select name="barber_id" required
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">
                            <option value="">Seleccione…</option>

                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" @selected(old('barber_id') == $barber->id)>
                                    {{ $barber->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('barber_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Desde --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Desde
                        </label>

                        <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">

                        @error('start_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hasta --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Hasta
                        </label>

                        <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" required
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">

                        @error('end_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label class="block text-sm text-zinc-300 mb-1">
                            Motivo <span class="text-zinc-500">(opcional)</span>
                        </label>

                        <input type="text" name="reason" value="{{ old('reason') }}" maxlength="255"
                            placeholder="Ej: No estaré en la barbería"
                            class="w-full bg-black/40 border border-zinc-700 focus:outline-none focus:border-yellow-500 focus:ring-0 text-white rounded-xl h-12 px-4">

                        @error('reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botón --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black font-semibold py-3 rounded-xl transition">
                            Bloquear horario
                        </button>
                    </div>
                </form>
            </div>
        @endrole

        {{-- Horarios bloqueados --}}
        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 mt-6">

            <h2 class="text-lg text-zinc-200 font-semibold mb-5">
                Horarios bloqueados
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-zinc-300">
                    <thead class="border-b border-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Barbero</th>
                            <th class="px-4 py-3 text-left">Desde</th>
                            <th class="px-4 py-3 text-left">Hasta</th>
                            <th class="px-4 py-3 text-left">Motivo</th>
                            <th class="px-4 py-3 text-left">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($unavailabilities as $unavailability)
                            <tr class="border-b border-zinc-700 hover:bg-zinc-800/70">

                                <td class="px-4 py-3">
                                    {{ $unavailability->barber?->name ?? 'Sin barbero' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $unavailability->start_time->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $unavailability->end_time->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $unavailability->reason ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route(
                                        'dashboard.appointments.unavailability.destroy',
                                        $unavailability) }}"
                                    >
                                        @csrf
                                        @method('DELETE')

                                            <button type="submit"
                                                class="border border-red-600/30 text-red-400 hover:bg-red-600/30 px-4 py-2 rounded-xl font-semibold transition">
                                                    Eliminar
                                            </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-zinc-500 text-xs py-4">
                                    No hay horarios bloqueados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div
        x-data="{
            isOpen:false,
            formAction: '',
            paymentMethod:'Efectivo',
            modalTitle: '',
            modalDEscription: '',
            modalButton: '',
                
                openModal(action, mode) {
                    this.formAction = action;
                    this.paymentMethod = 'Efectivo';
                    this.isOpen = true;

                    if (mode === 'update') {
                        this.modalTitle = 'Cambiar método de pago';
                        this.modalDescription = 'Selecciona el nuevo método de pago.';
                        this.modalButton = 'Guardar cambios';    
                    } else {    
                        this.modalTitle = 'Completar turno';
                        this.modalDescription = 'Selecciona el método de pago utilizado por el cliente.';
                        this.modalButton = 'Confirmar';
                    }
                }
            }"
        @open-payment-modal.window="openModal($event.detail.action, $event.detail.mode) "
        @keydown.escape.window ="isOpen = false"
    >
        <div
            x-show="isOpen"
            x-transition.opacity
            style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4"
            @click.self="isOpen = false"    
        >
            <div 
                class="w-full max-w-md bg-zinc-900 border border-zinc-700 rounded-2xl p-6 shadow-xl"
                role="dialog"
                aria-modal="true"
            >
                <h2 id="payment-modal-title" class="text-xl font-semibold text-white mb-2" x-text="modalTitle"></h2>

                <p class="text-sm text-zinc-400 mb-6" x-text="modalDescription"></p>
                    
                <form method="POST" :action="formAction" >
                    @csrf
                    @method('PATCH')
                    <div class="mb-6">
                        <label for="payment_method" class="block text-sm tex-zinc-300 mb-2">
                            Método de pago
                        </label>

                        <select 
                            name="payment_method" 
                            id="payment_method" 
                            x-model="paymentMethod"
                            required
                            class="w-full bg-black border border-zinc-700 focus:ring-0 focus:outline-none focus:border-yellow-500 rounded-xl text-white h-12 px-4"
                        >
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="QR">QR</option>

                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                            type="button"
                            @click="isOpen = false"
                            class="w-full border border-zinc-600 text-zinc-300 hover:bg-zinc-800 rounded-xl font-semibold transition"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="w-full bg-yellow-600 hover:bg-yellow-500 text-black py-3 rounded-xl font-semibold transition"
                            x-text="modalButton"
                        ></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection