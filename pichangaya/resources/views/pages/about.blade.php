<x-app-layout>
    <div class="py-16 bg-white dark:bg-gray-950 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Encabezado --}}
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Pasión por el Deporte en <span class="text-green-500 italic">Cusco</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    PichangaYa nació en el corazón de la ciudad imperial para que nunca más te quedes sin jugar por falta de una cancha.
                </p>
            </div>

            {{-- Historia y Misión --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
                <div class="space-y-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Nuestra Historia</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-lg">
                        Sabemos lo frustrante que es llamar a cinco complejos deportivos y que todos estén llenos, o peor aún, que nadie conteste el teléfono. 
                    </p>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-lg">
                        En 2025, decidimos digitalizar el deporte en Cusco. Creamos <strong>PichangaYa</strong> para conectar a los peloteros con los mejores complejos de la ciudad de forma instantánea, sin llamadas perdidas y con confirmación inmediata.
                    </p>
                </div>
                <div class="bg-green-500/10 rounded-3xl p-8 border border-green-500/20">
                    <h2 class="text-3xl font-bold text-green-600 dark:text-green-400 mb-4">Nuestra Misión</h2>
                    <p class="text-gray-800 dark:text-gray-300 text-lg italic">
                        "Facilitar el acceso al deporte a través de la tecnología, promoviendo un estilo de vida saludable y la unión de la comunidad deportiva cusqueña."
                    </p>
                </div>
            </div>

            {{-- Valores --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Rapidez</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Reserva tu cancha favorita en menos de un minuto.</p>
                </div>
                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl">
                    <div class="text-4xl mb-4">🤝</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confianza</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Trabajamos con los complejos más serios y seguros de la ciudad.</p>
                </div>
                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl">
                    <div class="text-4xl mb-4">🏔️</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Local</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Conocemos Cusco y entendemos las necesidades de nuestros deportistas.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>