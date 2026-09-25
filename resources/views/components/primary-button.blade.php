<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full bg-yellow-600 hover:bg-yellow-500 text-black flex items-center justify-center  font-semibold py-3 rounded-xl 
            transition-all transition ease-in-out duration-150 hover:scale-105 hover:font-bold']) }}>
    {{ $slot }}
</button>
