{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\about.blade.php --}}
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
                {{-- RAPIDEZ --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-3xl border border-transparent hover:border-green-500/30 transition-all group">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-2xl bg-yellow-100 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bolt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Rapidez</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Reserva tu cancha favorita en menos de un minuto.</p>
                </div>

                {{-- CONFIANZA --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-3xl border border-transparent hover:border-green-500/30 transition-all group">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-2xl bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confianza</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Trabajamos con los complejos más serios y seguros de la ciudad.</p>
                </div>

                {{-- LOCAL --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-3xl border border-transparent hover:border-green-500/30 transition-all group">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-6 rounded-2xl bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-current-location"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M4 12a8 8 0 1 0 16 0a8 8 0 1 0 -16 0" /><path d="M12 2l0 2" /><path d="M12 20l0 2" /><path d="M20 12l2 0" /><path d="M2 12l2 0" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Local</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Conocemos Cusco y entendemos las necesidades de nuestros deportistas.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>