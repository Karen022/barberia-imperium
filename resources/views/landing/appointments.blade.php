@extends('landing.layouts.landing')

@section('content')
    <section class="bg-gradient-to-b from-neutral-800 to-neutral-950 text-neutral-100 py-20">
        <div class="max-w-3xl mx-auto px-6">

            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-white">Reservar Turno</h1>

                <p class="text-neutral-400 mt-2">
                    Elegí tu servicio, barbero y horario ideal
                </p>
            </div>

            <div class="bg-neutral-800 rounded-xl shadow-lg p-8">
                <form action="{{ route('landing.appointments.store') }}" method="POST">
                    @csrf
                    <!-- SERVICIO -->
                    <div class="mb-6">
                        <label for="service" class="block mb-2 text-sm font-medium">
                            Servicio
                        </label>
                        <select name="service_id" id="service"
                            class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                            required>
                            <option value="">Selecciona un servicio</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                    {{ $service->name }} ({{ $service->duration_min }} min )
                                </option>
                            @endforeach
                        </select>

                        @error('service_id')
                            <p class="text-xs text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- BARBERO -->
                    <div class="mb-6">
                        <label for="barber" class="block mb-2 text-sm font-medium">
                            Barbero (opcional)
                        </label>
                        <select name="barber_id" id="barber"
                            class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Cualquiera Disponible</option>
                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" @selected(old('barber_id') == $barber->id)>
                                    {{ $barber->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('barber_id')
                            <p class="text-xs text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- FECHA -->
                    <div class="mb-6">
                        <label for="date" class="block mb-2 text-sm font-medium">
                            Fecha
                        </label>
                        <input type="date" name="date" id="date"
                            value="{{ old('date') }}"
                            min="{{ now()->toDateString() }}"
                            class="w-full bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                            required>

                        @error('date')
                            <p class="text-xs text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- HORARIOS -->
                    <div class="mb-8">
                        <label  class="block mb-4 text-sm font-medium">
                            Horarios disponibles
                        </label>

                        <div  id="slots" class="grid grid-cols-3 gap-3 text-center text-sm text-neutral-400"></div>
                        
                        @error('scheduled_at')
                            <p class="text-xs text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Horario seleccionado -->
                    <input type="hidden" 
                            name="scheduled_at" 
                            id="scheduled_at"
                            value="{{ old('scheduled_at') }}"
                    >

                    <!-- BOTON -->
                         <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 rounded-lg transition">
                            Confirmar reserva
                         </button>
                </form>
            </div>
        </div>
    </section>

@endsection