@extends('landing.layouts.landing')

@section('content')

    <section class="bg-gradient-to-b from-neutral-800 to-neutral-950 min-h-screen py-20 text-neutral-100">
        <div class="max-w-5xl mx-auto px-6">

            <h1 class="text-3xl font-bold mb-10 text-center">Mis Turnos</h1>

            @forelse($appointments as $appointment)
                <div class="bg-neutral-800 rounded-lg p-6 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">
                            {{ $appointment->service->name }}
                        </p>

                        <p class="text-neutral-400 text-sm">
                            {{ $appointment->barber->name }}
                        </p>

                        <p class="text-neutral-400 text-sm">
                            {{ $appointment->scheduled_at->format('d/m/Y H:i') }}
                        </p>

                        <p class="text-sm capitalize">
                            Estado: {{ $appointment->status }}
                        </p>
                    </div>
                    @if ($appointment->scheduled_at->isFuture() && $appointment->status === 'pending')
                        <form method="POST" action="{{ route('landing.appointments.cancel', $appointment) }}">
                            @csrf
                            @method('PATCH')

                            <button class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-sm">
                                Cancelar
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-center text-neutral-400">
                    Aún no tenés turnos reservados.
                </p>
            @endforelse
        </div>

    </section>
@endsection