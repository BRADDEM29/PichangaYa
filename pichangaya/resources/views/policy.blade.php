<x-guest-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-12 transition-colors duration-300">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Botón Volver --}}
            <div class="mb-8">
                <a href="/" class="text-sm font-bold text-green-600 hover:underline flex items-center gap-2">
                    &larr; Volver al inicio
                </a>
            </div>

            <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800">
                <div class="p-8 sm:p-12">
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-6">
                        {{-- Cambia esto a "Política de Privacidad" en el otro archivo --}}
                        Términos y Condiciones Generales
                    </h1>

                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{-- Aquí es donde Jetstream inyecta el texto legal --}}
                        {!! $terms !!} 
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-gray-500">
                Última actualización: {{ date('d/m/Y') }}
            </div>
        </div>
    </div>
</x-guest-layout>