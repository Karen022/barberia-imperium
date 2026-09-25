@extends(
    $user->HasAnyRole(['admin', 'barber'])
    ? 'dashboard.layouts.dashboard'
    : 'landing.layouts.landing'
)

@section('content')
    

    <div class="p-6 text-gray-200">

        <h1 class="text-2xl text-center font-bold mb-6">Mi Perfil</h1>

        <div class="bg-black/40 border border-zinc-700 rounded-2xl p-6 space-y-4">

            <div class="flex justify-center">
                <img src="{{ auth()->user()->profile_image ? asset('storage/profiles/' . auth()->user()->profile_image) : asset('images/avatar-default.png') }}"
                    alt="Imagen de perfil" class="w-32 h-32 rounded-full border-2 border-yellow-500 object-cover ">
            </div>

            <div>
                <span class="text-gray-400 text-sm">Nombre</span>
                <p class="text-lg">{{ auth()->user()->name }}</p>
            </div>

            <div>
                <span class="text-gray-400 text-sm">Email</span>
                <p class="text-lg">{{ auth()->user()->email }}</p>
            </div>

            <div>
                <span class="text-gray-400 text-sm">Teléfono</span>
                <p class="text-lg">{{  auth()->user()->phone ?: '--' }}</p>

            </div>

            <a href="{{ route('profile.edit') }}"
                class="inline-block bg-yellow-600 hover:bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold mt-4">
                Editar perfil
            </a>
        </div>
    </div>



@endsection