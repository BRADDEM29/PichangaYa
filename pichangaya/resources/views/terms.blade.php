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
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-2xl">
                            <span class="text-3xl">📜</span>
                        </div>
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                            Términos y Condiciones
                        </h1>
                    </div>

                    <div class="prose prose-green dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 space-y-8 text-lg leading-relaxed">
                        
                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">1. Aceptación de los Términos</h2>
                            <p>Al acceder y utilizar <strong>PichangaYa</strong>, usted acepta cumplir con estos términos. Nuestra plataforma facilita la conexión entre dueños de complejos deportivos y deportistas en la ciudad de Cusco.</p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">2. Proceso de Reserva</h2>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Las reservas están sujetas a la disponibilidad confirmada por el establecimiento.</li>
                                <li>El usuario se compromete a asistir en el horario reservado.</li>
                                <li>PichangaYa es un intermediario; la calidad del campo y el servicio en el sitio son responsabilidad del dueño del local.</li>
                            </ul>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">3. Cancelaciones y Reembolsos</h2>
                            <p>Cada establecimiento tiene su propia política. Por regla general en PichangaYa:</p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Cancelaciones con más de 24 horas: Sujeto a reprogramación según disponibilidad.</li>
                                <li>Cancelaciones el mismo día: No se garantiza el reembolso del adelanto si lo hubiera.</li>
                            </ul>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">4. Responsabilidad del Usuario</h2>
                            <p>Usted es responsable de cuidar las instalaciones del complejo deportivo. Cualquier daño causado por conducta inapropiada será responsabilidad directa del usuario que realizó la reserva.</p>
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