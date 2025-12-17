<x-app-layout>
    <div class="py-16 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-4">Atención Inmediata</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    ¿Tienes un problema con una reserva de hoy? Nuestro equipo te ayudará a resolverlo en tiempo récord.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                {{-- Info de contacto rápida --}}
                <div class="md:col-span-1 space-y-4">
                    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">WhatsApp</p>
                        <p class="text-lg font-black text-green-600">+51 9XX XXX XXX</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Horario</p>
                        <p class="text-gray-900 dark:text-white font-medium text-sm">Lun - Dom / 7:00 AM - 11:00 PM</p>
                    </div>
                </div>

                {{-- El Formulario Livewire --}}
                <div class="md:col-span-2">
                    @livewire('contact-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>