<x-app-layout>
    <div class="py-16 bg-white dark:bg-gray-950 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Hero Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div class="order-2 lg:order-1">
                    <span class="text-green-500 font-bold uppercase tracking-widest text-sm">Para dueños de complejos</span>
                    <h1 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mt-4 mb-6 leading-tight">
                        Lleva tu cancha al <span class="text-green-500 italic">siguiente nivel</span>
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        Únete a la red más grande de Cusco y permite que miles de deportistas reserven tu complejo con un solo clic, las 24 horas del día.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-block bg-green-600 text-white px-8 py-4 rounded-xl font-black text-center hover:bg-green-500 transition shadow-xl shadow-green-500/20">
                            Empezar ahora gratis
                        </a>
                        <a href="{{ route('contact.index') }}" class="inline-block bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white px-8 py-4 rounded-xl font-bold text-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            Solicitar demo
                        </a>
                    </div>
                </div>
                <div class="order-1 lg:order-2 relative">
                    <div class="absolute -inset-4 bg-green-500/20 rounded-full blur-3xl"></div>
                    <div class="relative bg-gray-900 rounded-3xl p-4 shadow-2xl border border-gray-800">
                        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Estadio" class="rounded-2xl opacity-80">
                        <div class="absolute bottom-8 left-8 right-8 bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20">
                            <p class="text-white font-bold italic">"Desde que uso PichangaYa, mis canchas están llenas incluso los lunes por la mañana."</p>
                            <p class="text-green-400 text-sm mt-2">— Administrador Local</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Beneficios --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <div class="p-8 border border-gray-100 dark:border-gray-800 rounded-3xl bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center text-2xl mb-6">📈</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Aumenta tus ingresos</h3>
                    <p class="text-gray-600 dark:text-gray-400">Reduce las horas muertas. Tu cancha estará disponible para reservar mientras tú descansas.</p>
                </div>
                <div class="p-8 border border-gray-100 dark:border-gray-800 rounded-3xl bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center text-2xl mb-6">📱</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Adiós al cuaderno</h3>
                    <p class="text-gray-600 dark:text-gray-400">Gestiona tus horarios desde el celular. Olvida los tachones y las confusiones en el papel.</p>
                </div>
                <div class="p-8 border border-gray-100 dark:border-gray-800 rounded-3xl bg-gray-50/50 dark:bg-gray-900/50">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center text-2xl mb-6">🛡️</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Pagos Seguros</h3>
                    <p class="text-gray-600 dark:text-gray-400">Recibe adelantos por Yape/Plin y asegura que los equipos realmente asistan al partido.</p>
                </div>
            </div>

            {{-- Sección de Llamada a la acción --}}
            <div class="bg-gray-900 dark:bg-green-600 rounded-3xl p-12 text-center text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-black mb-6">¿Listo para unirte a la revolución deportiva?</h2>
                    <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">
                        El registro toma menos de 5 minutos y nuestro equipo te ayudará a configurar tus precios y fotos.
                    </p>
                    <a href="{{ route('register') }}" class="bg-white text-gray-900 px-10 py-4 rounded-xl font-black text-lg hover:bg-gray-100 transition inline-block">
                        Registrar mi complejo hoy
                    </a>
                </div>
                {{-- Decoración de fondo --}}
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-green-500/20 rounded-full blur-3xl"></div>
            </div>

        </div>
    </div>
</x-app-layout>