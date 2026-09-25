@if (session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition
        class="mb-6"
    >
        @if (session('success'))
            <div class="flex items-center justify-between gap-4 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-green-400">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    <p class="text-sm">
                        {{ session('success') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="text-green-400 hover:text-green-300"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center justify-between gap-4 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-red-400">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <p class="text-sm">
                        {{ session('error') }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="text-red-400 hover:text-red-300"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>
@endif