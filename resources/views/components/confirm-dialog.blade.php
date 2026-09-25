
@props([
    'title' => '¿Estás seguro?',
    'message' => 'Esta acción no se puede deshacer.',
    'confirmText' => 'Eliminar',
    'cancelText' => 'Cancelar',
])

<div
    x-data="{ open: false }"
    {{ $attributes }}
>
    {{-- Botón que abre el modal --}}
    <button
        type="button"
        @click="open = true"
    >
        {{ $trigger }}
    </button>

    {{-- Modal --}}
    <div
        x-show="open"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4"
    >
        <div
            @click.outside="open = false"
            x-transition
            class="w-full max-w-md rounded-2xl border border-zinc-700 bg-zinc-900 p-6 shadow-2xl"
        >
            {{-- Encabezado --}}
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-red-400">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-white">
                        {{ $title }}
                    </h2>

                    <p class="mt-2 text-sm text-zinc-400">
                        {{ $message }}
                    </p>
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    @click="open = false"
                    class="rounded-xl border border-zinc-700 px-4 py-2 text-sm font-semibold text-zinc-300 transition hover:bg-zinc-800"
                >
                    {{ $cancelText }}
                </button>

                {{ $confirm }}
            </div>
        </div>
    </div>
</div>

