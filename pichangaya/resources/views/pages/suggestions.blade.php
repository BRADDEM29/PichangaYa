<x-app-layout>
    <div class="py-16 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white">Ayúdanos a Mejorar</h1>
            </div>

            {{-- ESTA LÍNEA ES LA QUE LLAMA AL FORMULARIO --}}
            @livewire('suggestion-form')
            
        </div>
    </div>
</x-app-layout>