<footer class="bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-300 pt-16 pb-8 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            
            {{-- COLUMNA 1: LOGO Y REDES --}}
            <div class="space-y-4">
                <h4 class="text-2xl font-black italic text-gray-900 dark:text-white">
                    ⚽ Pichanga<span class="text-green-500">Ya</span>
                </h4>
                <p class="text-sm opacity-80">Encuentra y reserva en las mejores canchas de Cusco.</p>
                
                <div class="flex items-center gap-4 pt-4">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/51940766968" target="_blank" class="text-gray-400 hover:text-green-500 transition" aria-label="WhatsApp">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    {{-- Email --}}
                    <a href="mailto:soporte@pichangaya.com" class="text-gray-400 hover:text-red-500 transition" aria-label="Correo Electrónico">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 8.138h-18.745l5.479-8.133zm9.208-1.259l4.616-3.741v9.462l-4.616-5.721z"/></svg>
                    </a>
                </div>
            </div>

            {{-- COLUMNA 2: EXPLORAR --}}
            <div>
                <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Explorar</h5>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-green-500 transition">🔍 Buscar Canchas</a></li>
                    <li><a href="{{ route('register-pitch') }}" class="text-green-600 dark:text-green-400 font-bold hover:underline">🏟️ Registra tu Cancha</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-green-500 transition">¿Quiénes somos?</a></li>
                </ul>
            </div>

            {{-- COLUMNA 3: AYUDA --}}
            <div>
                <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Ayuda</h5>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="{{ route('faq') }}" class="hover:text-green-500 transition">Preguntas Frecuentes</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Atención Inmediata</a></li>
                    <li><a href="{{ route('suggestions.index') }}" class="hover:text-green-500 transition">Enviar Sugerencia</a></li>
                </ul>
            </div>

            {{-- COLUMNA 4: LEGAL --}}
            <div>
                <h5 class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white mb-6">Legal</h5>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="{{ route('terms.show') }}" class="hover:text-green-500 transition">Términos y condiciones</a></li>
                    <li><a href="{{ route('policy.show') }}" class="hover:text-green-500 transition">Política de privacidad</a></li>
                </ul>
            </div>
        </div>

        {{-- ZONA INFERIOR: COPYRIGHT MEJORADO --}}
        <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center text-xs opacity-60">
            &copy; {{ date('Y') }} <span class="font-bold">PichangaYa</span>. Todos los derechos reservados.
        </div>
    </div>
</footer>