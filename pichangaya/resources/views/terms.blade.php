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
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Términos y Condiciones</h1>
                    </div>

                    <div class="prose prose-green dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 space-y-8 text-lg leading-relaxed">
                        
                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">1. Uso de la Cuenta y Seguridad</h2>
                            <p>Para usar PichangaYa, debes registrarte y mantener una contraseña segura. Eres el único responsable de la actividad en tu cuenta. En caso de detectar un uso no autorizado, debes notificarnos inmediatamente.</p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">2. Pagos, Estados y Cancelaciones</h2>
                            <p>El usuario debe cumplir con el pago establecido por el establecimiento. <strong>Si el usuario no realiza su pago (cambio de estado)</strong>, la reserva pasará automáticamente a <strong>cancelación</strong>.</p>
                            <div class="bg-amber-50 dark:bg-amber-900/10 p-6 rounded-2xl border-l-4 border-amber-500 my-4">
                                <h3 class="font-bold text-amber-800 dark:text-amber-500 mb-2 italic">⏰ Política de Llegadas Tarde y Ausencia</h3>
                                <p>Recomendamos llegar <strong>5 minutos antes</strong>. Si superás los <strong>30 minutos de retraso</strong>, el establecimiento puede cancelar tu reserva sin derecho a devolución de adelantos.</p>
                                <p class="mt-2"><strong>Si el usuario no llega, perderá el adelanto o dinero abonado inmediatamente al cumplirse un lapso de 30 minutos sin ninguna evidencia de asistencia al establecimiento.</strong></p>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">3. Clima y Fuerza Mayor</h2>
                            <p>En caso de condiciones climáticas extremas (lluvias torrenciales, tormentas eléctricas) o eventos de fuerza mayor que impidan el uso de la cancha, la reprogramación dependerá exclusivamente de las políticas del establecimiento deportivo. PichangaYa facilitará la comunicación pero no se hace responsable por decisiones unilaterales del club.</p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">4. Conducta en las Instalaciones</h2>
                            <p>El Cliente se compromete a mantener un comportamiento respetuoso, no consumir sustancias prohibidas y usar el calzado adecuado (ej. zapatillas de grass sintético). El incumplimiento puede causar la expulsión inmediata sin reembolso.</p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">5. Propiedad Intelectual</h2>
                            <p>Todo el contenido de PichangaYa (logos, software, diseño) es propiedad exclusiva de la empresa. Queda prohibida su reproducción o uso para fines comerciales externos sin autorización escrita.</p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>