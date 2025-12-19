<x-guest-layout>
    <div class="bg-white dark:bg-gray-950 transition-colors duration-300">
        
        {{-- HERO SECTION SIMPLIFICADO --}}
        <section class="relative py-20 overflow-hidden">
            <div class="absolute inset-0 opacity-10 dark:opacity-5">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2048&auto=format&fit=crop" class="w-full h-full object-cover" alt="Background">
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center">
                    <span class="inline-block px-4 py-1.5 mb-6 text-sm font-bold tracking-widest text-green-600 uppercase bg-green-100 dark:bg-green-900/30 rounded-full">
                        Para dueños de complejos
                    </span>
                    <h1 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
                        Lleva tu cancha al <span class="text-green-500">siguiente nivel</span>
                    </h1>
                    <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-10 leading-relaxed">
                        Únete a la red más grande de Cusco. Permite que miles de deportistas encuentren y reserven tu complejo con un solo clic, las 24 horas del día.
                    </p>

                    {{-- BOTÓN ÚNICO SOLICITADO --}}
                    <div class="flex justify-center">
                        <a href="{{ route('contact.index') }}" 
                           class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-green-600 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 hover:bg-green-700 shadow-xl hover:shadow-green-500/20">
                            Registrar mi complejo hoy
                            <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- BENEFICIOS CLAVE --}}
        <section class="py-16 bg-gray-50 dark:bg-gray-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Beneficio 1 --}}
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-2xl mb-6">📈</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Aumenta tus ingresos</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Reduce las horas muertas. Tu cancha estará disponible para reservar mientras tú descansas o atiendes a otros clientes.
                        </p>
                    </div>

                    {{-- Beneficio 2 --}}
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-2xl mb-6">📱</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Adiós al cuaderno</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Gestiona tus horarios desde el celular. Olvida los tachones, las confusiones y las llamadas perdidas.
                        </p>
                    </div>

                    {{-- Beneficio 3 --}}
                    <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center text-2xl mb-6">🛡️</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Pagos Seguros</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Configura adelantos por Yape o Plin y asegura que los equipos realmente asistan a sus partidos programados.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CALL TO ACTION FINAL --}}
        <section class="py-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="bg-green-600 rounded-3xl p-10 md:p-16 shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                            ¿Listo para unirte a la revolución deportiva?
                        </h2>
                        <p class="text-green-100 text-lg mb-10 max-w-2xl mx-auto">
                            El registro toma menos de 5 minutos. Haz clic abajo y nuestro equipo te ayudará con la configuración de tus precios y fotos por WhatsApp.
                        </p>
                        <a href="{{ route('contact.index') }}" 
                           class="inline-block bg-white text-green-600 px-10 py-4 rounded-xl font-black uppercase tracking-wider hover:bg-gray-100 transition-colors shadow-lg">
                            Registrar mi complejo hoy
                        </a>
                    </div>
                    {{-- Elementos decorativos --}}
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-green-500 rounded-full opacity-50"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-green-700 rounded-full opacity-50"></div>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>