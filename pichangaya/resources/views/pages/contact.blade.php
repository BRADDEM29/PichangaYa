<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\pages\contact.blade.php --}}
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
                    
                    {{-- 🟢 NUEVO BOTÓN DE WHATSAPP (Reemplazando la tarjeta antigua) --}}
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 text-center shadow-sm">
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4">¿Necesitas ayuda inmediata?</p>
                        
                        <a href="https://wa.me/51940766968" target="_blank" class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white font-black py-4 rounded-xl transition shadow-lg flex justify-center items-center gap-3 transform hover:scale-[1.02]">
                            {{-- Logo WhatsApp SVG --}}
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp Soporte
                        </a>
                    </div>

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