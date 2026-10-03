@extends(
    $user->HasAnyRole(['admin', 'barber'])
    ? 'dashboard.layouts.dashboard'
    : 'landing.layouts.landing'
)

@section('content')
    <section class="bg-neutral-900 text-neutral-100 py-20">

        <div class="p-6">
            <a href="{{ route('profile.index') }}"
                class="px-4 py-2 rounded-xl border border-zinc-700 text-gray-300 text-xs hover:bg-zinc-800 transition">
                ← Volver
            </a>
        </div>
        <div class="max-w-3xl mx-auto px-6">

            <!-- Encabezado -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold">Editar Perfil</h1>
                <p class="text-gray-400 mt-2">Actualiza tus datos personales</p>
            </div>

            <!-- DATOS DEL PERFIL -->
            <div class="bg-neutral-800 rounded-xl shadow-lg p-8 mb-8">

                <h2 class="text-xl font-semibold mb-6">
                    Datos personales
                </h2>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm text-gray-300">
                            Nombre
                        </label>

                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                            required>

                        @error('name')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm text-gray-300">
                            Correo Electrónico
                        </label>

                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                            required>

                        @error('email')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm text-gray-300">
                            Teléfono
                        </label>

                        <div class="flex">
                            <span
                                class="inline-flex items-center px-4 rounded-l-lg bg-neutral-800 border border-r-0 border-neutral-700 text-gray-300">
                                +595
                            </span>

                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone', $user->phone ? substr($user->phone, 3) : '') }}" maxlength="9"
                                inputmode="numeric" placeholder="981234567"
                                class="w-full rounded-r-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
                    <div class="mb-6">
                        <label for="document_type" class="block text-sm text-gray-300">
                            Tipo de Documento
                        </label>

                        <select 
                            name="document_type" 
                            id="document_type"
                            class="w-full bg-neutral-900 rounded-lg border border-neutral-700 text-neutral-100 px-4 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                        >
                            <option value="">Seleccionar</option>

                            <option value="CI" {{ old('document_type', $user->document_type) == 'CI' ? 'selected' : '' }}>
                                C.I.
                            </option>

                            <option value="RUC" {{ old('document_type', $user->document_type) == 'RUC' ? 'selected' : '' }}>
                                RUC
                            </option>
                        </select>
                        @error('document_type')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Número de Documento -->
                    <div class="mb-6">
                        <label for="document_number" class="block text-sm text-gray-300">
                            Número de Documento
                        </label>

                        <input type="text" name="document_number" id="document_number"
                            value="{{ old('document_number', $user->document_number) }}"
                            class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                        >
                        @error('document_number')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Imagen de Perfil -->
                    <div class="mb-6">
                        <label for="profile_image" class="block text-sm text-gray-300">
                            Imagen de Perfil
                        </label>

                        @if($user->profile_image)
                            <div class="mb-4">
                                <img src="{{ asset('storage/profiles/' . $user->profile_image) }}" alt="Profile"
                                    class="w-24 h-24 rounded-full object-cover border border-gray-600">
                            </div>
                        @endif

                        <input type="file" name="profile_image" id="profile_image" accept="image/*"
                            class="w-full mt-1 text-sm text-gray-300 file:bg-yellow-600 file:border-0 file:text-black file:px-4 file:py-2 file:rounded-lg file:font-semibold">

                        @error('profile_image')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botón de Actualizar -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 rounded-lg transition">
                            Actualizar Perfil
                        </button>
                    </div>
                </form>
            </div>


            <!-- CAMBIAR CONTRASEÑA -->
            <div class="bg-neutral-800 rounded-xl shadow-lg p-8">

                <div class="mb-6">
                    <h2 class="text-xl font-semibold">
                        Cambiar contraseña
                    </h2>

                    <p class="text-sm text-gray-400 mt-2">
                        Actualiza tu contraseña para mantener tu cuenta segura.
                    </p>
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Contraseña actual -->
                    <div class="mb-6">
                        <label for="current_password" class="block text-sm text-gray-300">
                            Contraseña actual
                        </label>

                        <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                            class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:ring-yellow-500 focus:border-yellow-500">

                        @if ($errors->updatePassword->has('current_password'))
                            <p class="mt-1 text-sm text-red-400">
                                {{ $errors->updatePassword->first('current_password') }}
                            </p>
                        @endif
                    </div>

                    <!-- Nueva contraseña -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm text-gray-300">
                            Nueva contraseña
                        </label>

                        <input type="password" name="password" id="password" autocomplete="new-password"
                            class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:ring-yellow-500 focus:border-yellow-500">

                        @if ($errors->updatePassword->has('password'))
                            <p class="mt-1 text-sm text-red-400">
                                {{ $errors->updatePassword->first('password') }}
                            </p>
                        @endif
                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm text-gray-300">
                            Confirmar nueva contraseña
                        </label>

                        <input type="password" name="password_confirmation" id="password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-neutral-100 px-4 py-2 focus:ring-yellow-500 focus:border-yellow-500">

                        @if ($errors->updatePassword->has('password_confirmation'))
                            <p class="mt-1 text-sm text-red-400">
                                {{ $errors->updatePassword->first('password_confirmation') }}
                            </p>
                        @endif
                    </div>

                    <!-- Botón -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 rounded-lg transition">
                            Cambiar contraseña
                        </button>
                    </div>

                </form>
            </div>

            <!-- ELIMINAR CUENTA -->
            <div class="bg-neutral-800 rounded-xl shadow-lg p-8 mt-8 border border-red-900/50">

                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-red-400">
                        Eliminar cuenta
                    </h2>

                    <p class="text-sm text-gray-400 mt-2">
                        Una vez eliminada tu cuenta, todos tus datos asociados serán eliminados
                        permanentemente. Esta acción no se puede deshacer.
                    </p>
                </div>

                <x-confirm-dialog title="¿Eliminar tu cuenta?"
                    message="Esta acción es permanente. Para confirmar, deberás ingresar tu contraseña actual."
                    confirmText="Eliminar cuenta" cancelText="Cancelar">

                    <x-slot:trigger>
                        <span
                            class="block w-full rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 text-center transition cursor-pointer">
                            Eliminar mi cuenta
                        </span>
                    </x-slot:trigger>

                    <x-slot:confirm>

                        <form action="{{ route('profile.destroy') }}" method="POST"
                            class="flex justify-center items-center gap-3">
                            @csrf
                            @method('DELETE')

                            <input type="password" name="password" placeholder="Contraseña actual"
                                autocomplete="current-password"
                                class="w-48 rounded-xl border border-zinc-700 bg-zinc-800 px-4 py-2 text-sm text-white placeholder-zinc-500 focus:border-red-500 focus:ring-red-500">

                            <button type="submit"
                                class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                                Eliminar
                            </button>
                        </form>

                    </x-slot:confirm>

                </x-confirm-dialog>

                @if ($errors->userDeletion->has('password'))
                    <p class="mt-3 text-sm text-red-400">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif

            </div>

        </div>
    </section>
@endsection