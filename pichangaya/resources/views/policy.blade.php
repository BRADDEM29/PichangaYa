<x-guest-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-12 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('home') }}" class="text-sm font-bold text-green-600 hover:text-green-500 flex items-center gap-2 transition-all">
                    &larr; Volver al inicio
                </a>
            </div>

            <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-800">
                <div class="p-8 sm:p-16">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl">
                            <span class="text-3xl">🛡️</span>
                        </div>
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                            Política de Privacidad
                        </h1>
                    </div>

                    <div class="prose prose-blue dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 space-y-8 text-lg leading-relaxed">
                        
                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">1. Información que Recopilamos</h2>
                            <p>En PichangaYa, recopilamos información básica para procesar tus reservas:</p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Nombre y apellidos.</li>
                                <li>Correo electrónico.</li>
                                <li>Número de teléfono (para coordinación de reservas).</li>
                            </ul>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">2. Uso de la Información</h2>
                            <p>Tus datos se utilizan exclusivamente para:</p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Gestionar tus reservas de canchas.</li>
                                <li>Enviarte notificaciones sobre el estado de tu reserva.</li>
                                <li>Mejorar la experiencia de usuario en nuestra aplicación.</li>
                            </ul>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">3. Protección de Datos</h2>
                            <p>Implementamos medidas de seguridad técnica para proteger tu información contra acceso no autorizado. No vendemos ni compartimos tus datos con terceros con fines publicitarios.</p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">4. Tus Derechos</h2>
                            <p>Puedes solicitar la eliminación de tu cuenta y datos en cualquier momento a través de nuestra sección de contacto o enviando un correo a <strong>pichangayacusco@gmail.com</strong>.</p>
                        </section>

                    </div>

                    <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800 text-center">
                        <p class="text-sm text-gray-500">Última actualización: 19 de Diciembre, 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>