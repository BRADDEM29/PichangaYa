<x-app-layout>
    <div class="py-16 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-4">¿Tienes dudas?</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">Aquí resolvemos las preguntas más comunes de nuestra comunidad.</p>
            </div>

            <div class="space-y-4" x-data="{ active: 1 }">
                
                {{-- Pregunta 1 --}}
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-5 text-left flex justify-between items-center transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">¿Cómo realizo una reserva?</span>
                        <svg class="w-5 h-5 text-green-500 transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 pb-5 text-gray-600 dark:text-gray-400 leading-relaxed">
                        Es muy simple: busca el complejo deportivo que prefieras, selecciona la fecha y la hora disponible, completa tus datos y ¡listo! Recibirás una confirmación en tu correo y en tu panel de usuario.
                    </div>
                </div>

                {{-- Pregunta 2 --}}
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-5 text-left flex justify-between items-center transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">¿Cómo se realiza el pago?</span>
                        <svg class="w-5 h-5 text-green-500 transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 pb-5 text-gray-600 dark:text-gray-400 leading-relaxed">
                        Actualmente aceptamos adelantos vía <strong>Yape o Plin</strong> para asegurar tu turno. El saldo restante se cancela directamente en la recepción del complejo al momento de tu partido.
                    </div>
                </div>

                {{-- Pregunta 3 --}}
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-5 text-left flex justify-between items-center transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">¿Puedo cancelar o reprogramar?</span>
                        <svg class="w-5 h-5 text-green-500 transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-6 pb-5 text-gray-600 dark:text-gray-400 leading-relaxed">
                        Sí, puedes solicitar una cancelación o reprogramación hasta 4 horas antes de tu reserva. Debes contactarnos vía el formulario de <strong>Atención Inmediata</strong> para gestionar el cambio con el administrador de la cancha.
                    </div>
                </div>

                {{-- Pregunta 4 --}}
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-5 text-left flex justify-between items-center transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">¿Tienen balones para alquilar?</span>
                        <svg class="w-5 h-5 text-green-500 transition-transform" :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 4" x-collapse class="px-6 pb-5 text-gray-600 dark:text-gray-400 leading-relaxed">
                        La mayoría de nuestros complejos asociados ofrecen el servicio de préstamo o alquiler de balones y chalecos. Puedes ver este detalle en la sección de "Servicios" de cada cancha antes de reservar.
                    </div>
                </div>

            </div>

            {{-- CTA --}}
            <div class="mt-16 bg-green-600 rounded-2xl p-8 text-center text-white">
                <h3 class="text-2xl font-bold mb-2">¿Aún tienes dudas?</h3>
                <p class="mb-6 opacity-90">Escríbenos directamente y te ayudaremos en minutos.</p>
                <a href="{{ route('contact.index') }}" class="inline-block bg-white text-green-600 px-8 py-3 rounded-full font-black hover:bg-gray-100 transition shadow-lg">
                    Contactar Soporte
                </a>
            </div>

        </div>
    </div>
</x-app-layout>