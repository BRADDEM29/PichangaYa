<x-app-layout>
    <div class="py-16 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Encabezado --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    Atención <span class="text-green-500">Inmediata</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    ¿Tienes un problema con una reserva o quieres registrar tu complejo? Nuestro equipo en Cusco está listo para ayudarte.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Columna de Información Lateral --}}
                <div class="space-y-4">
                    {{-- Card de WhatsApp --}}
                    <a href="https://wa.me/51940766968?text=Hola%20PichangaYa,%20necesito%20ayuda%20con%20una%20reserva" 
                       target="_blank"
                       class="block p-6 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 hover:border-green-500 dark:hover:border-green-500 transition-all group shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                                📱
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">WhatsApp Soporte</p>
                                <p class="text-xl font-black text-gray-900 dark:text-white">+51 940 766 968</p>
                            </div>
                        </div>
                    </a>

                    {{-- Card de Correo --}}
                    <a href="mailto:pichangayacusco@gmail.com"
                       class="block p-6 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 transition-all group shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                                ✉️
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Correo Oficial</p>
                                <p class="text-gray-900 dark:text-white font-bold text-sm">pichangayacusco@gmail.com</p>
                            </div>
                        </div>
                    </a>

                    {{-- Card de Horario --}}
                    <div class="p-6 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm border-l-4 border-l-green-500">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center text-2xl">
                                ⏰
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Horario de Atención</p>
                                <p class="text-gray-900 dark:text-white font-bold">Lun - Dom</p>
                                <p class="text-green-600 dark:text-green-400 font-black text-sm">7:00 AM - 11:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna del Formulario (Livewire) --}}
                <div class="lg:col-span-2">
                    @livewire('contact-form')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>