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

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($services as $service)
                                <label
                                    class="flex items-center gap-3 bg-black/40 border border-zinc-700 rounded-xl px-4 py-3 cursor-pointer hover:border-yellow-500 transition">
                                    <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                        class="service-checkbox w-4 h-4 accent-yellow-500 shrink-0"
                                        data-duration="{{ $service->duration_min }}" data-price="{{ $service->price }}"
                                        @checked(in_array($service->id, old('service_ids', [])))>

                                            <span class=" flex-1 min-w-0">
                                    <span class="block text-white font-medium">
                                        {{ $service->name}}
                                    </span>
                                    <span class="block text-sm text-neutral-400">
                                        {{ $service->duration_min }} min
                                    </span>
                                    </span>


                                    <span class="text-yellow-500 font-semibold whitespace-nowrap">
                                        Gs. {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('service_ids')
                            <p class="text-xs text-red-500 mt-2">
                                {{  $message }}
                            </p>
                        @enderror

                        @error('service_ids.*')
                            <p class="text-xs text-red-500 mt-2">
                                {{  $message }}
                            </p>
                        @enderror
                    </div>

                    <div id="services-summary" class="mt-4 mb-5 px-4 py-3 bg-black/30 border border-zinc-700 rounded-xl text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <span id="services-count" class="text-neutral-400">
                                Seleccioná uno o más servicios
                            </span>

                            <span id="services-duration" class="text-yellow-500 font-semibold whitespace-nowrap"></span>
                        </div>

                        <div id="services-total" class="mt-1 text-right text-white font-semibold"></div>
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
                        <input type="date" name="date" id="date" value="{{ old('date') }}" min="{{ now()->toDateString() }}"
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
                        <label class="block mb-4 text-sm font-medium">
                            Horarios disponibles
                        </label>

                        <div id="slots" class="grid grid-cols-3 gap-3 text-center text-sm text-neutral-400"></div>

                        @error('scheduled_at')
                            <p class="text-xs text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Horario seleccionado -->
                    <input type="hidden" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}">

                    <!-- BOTON -->
                    <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 rounded-lg transition">
                        Confirmar reserva
                    </button>
                </form>
            </div>
        </div>
    </section>

@endsection