<footer class="bg-neutral-900 text-neutral-300">
    <div class="max-w-7xl mx-auto px-6 py-16 grid gap-12 md:grid-cols-4">

        <!-- Brand -->
        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="text-white text-lg font-semibold">Contacto</span>
            </div>

            <!-- Contact info -->
            <div class="mt-6 space-y-3 text-sm">
                <div class="flex items-center gap-2">
                    📍 <span>Tavapy, Paraguay</span>
                </div>
                <div class="flex items-center gap-2">
                    📞 <span>+595 983 176-430<span>
                </div>
                <div class="flex items-center gap-2">
                    ✉️ <span>imperiumbarberiia@gmail.com</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div>
            <h3 class="text-white font-semibold mb-4">Navegación</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('landing.home') }}" class="hover:text-yellow-500 transition">Inicio</a></li>
                <li><a href="{{ route('landing.services') }}" class="hover:text-yellow-500 transition">Servicios</a>
                </li>
                <li><a href="{{ route('landing.products') }}" class="hover:text-yellow-500 transition">Productos</a>
                </li>
                <li><a href="{{ route('landing.contact') }}" class="hover:text-yellow-500 transition">Contacto</a></li>
                <li><a href="{{ route('landing.appointments.create') }}"
                        class="hover:text-yellow-500 transition">Reservar Turno</a></li>
            </ul>
        </div>

        <!-- Links -->
        <div>
            <h3 class="text-white font-semibold mb-4">Enlaces</h3>
            <ul class="space-y-2 text-sm">
                @guest
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-yellow-500 transition">
                            Iniciar Sesión
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="hover:text-yellow-500 transition">
                            Registrarse
                        </a>
                    </li>
                @endguest

                @auth
                    @role('client')
                    <li>
                        <a href="{{ route('landing.appointments.create') }}" class="hover:text-yellow-500 transition">
                            Reservar Turno
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="hover:text-yellow-500 transition">
                                Cerrar Sesión
                            </button>
                        </form>
                    </li>
                    @endrole
                @endauth
            </ul>
        </div>

        <!-- Social -->
        <div>
            <h3 class="text-white font-semibold mb-4">Seguinos</h3>

            <div class="flex gap-4">
                <a href="https://www.facebook.com/Imperiumbarberiia?mibextid=wwXIfr&rdid=y8i6UyAmOkdlxukx&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F19jG4ghBcv%2F%3Fmibextid%3DwwXIfr#"
                    class="p-3 rounded-full bg-neutral-800 hover:bg-yellow-500 hover:text-black transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/imperiumbarberiia/"
                    class="p-3 rounded-full bg-neutral-800 hover:bg-yellow-500 hover:text-black transition">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://wa.me/595983176430?text=Hola%2C%20quiero%20reservar%20un%20horario"
                    class="p-3 rounded-full bg-neutral-800 hover:bg-yellow-500 hover:text-black transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom -->
    <div class="border-t border-neutral-800 py-6 text-center text-sm text-neutral-500">
        © {{ date('Y') }} Barbería Premium. Todos los derechos reservados.
    </div>
</footer>