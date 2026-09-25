@extends('dashboard.layouts.dashboard')


@section('content')
    <h2 class="text-2xl font-bold mb-6">
        Bienvenido, {{ auth()->user()->name }}
    </h2>

    <h3 class="text-xl font-semibold mb-4 text-zinc-200">
        Mis métricas
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Mis turnos hoy</p>
            <p class="text-3xl font-bold mt-2">
                {{ $data['personal']['appointmentsToday'] }}
            </p>
        </div>

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Turnos este mes</p>
            <p class="text-3xl font-bold mt-2">
                {{ $data['personal']['monthlyAppointments'] }}
            </p>
        </div>

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Mis ingresos hoy</p>
            <p class="text-3xl font-bold mt-2 text-yellow-500">
                Gs {{ number_format($data['personal']['incomeToday'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Ventas realizadas</p>
            <p class="text-3xl font-bold mt-2">
                {{ $data['personal']['salesCount'] }}
            </p>
        </div>

    </div>

    @role('admin')
    <h3 class="text-xl font-semibold mb-4 mt-10 text-zinc-200">
        Métricas generales
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Turnos hoy</p>
            <p class="text-3xl font-bold mt-2">
                {{ $data['global']['appointmentsToday'] }}
            </p>
        </div>

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Ingresos totales hoy</p>
            <p class="text-3xl font-bold mt-2 text-yellow-500">
                Gs {{ number_format($data['global']['incomeToday'], 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Clientes</p>
            <p class="text-3xl font-bold mt-2">
                {{ $data['global']['clientsCount'] }}
            </p>
        </div>

        <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800">
            <p class="text-zinc-400 text-sm">Barberos</p>
            <p class="text-3xl font-bold mt-2">
                {{ $data['global']['barbersCount'] }}
            </p>
        </div>

    </div>
    @endrole


@endsection