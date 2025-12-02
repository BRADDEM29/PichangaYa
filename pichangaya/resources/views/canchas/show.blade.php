<x-app-layout>
    {{-- Header con Breadcrumb simple --}}
    <x-slot name="header">
        <div class="flex items-center text-sm text-gray-600 leading-tight">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Explorar</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            <span class="font-semibold text-gray-800">{{ $cancha->name }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Grid Principal: 2 Columnas (izquierda detalles, derecha reserva) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- COLUMNA IZQUIERDA: Carrusel y Detalles (Ocupa 2/3) --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 🖼️ 1. CARRUSEL ROBUSTO CON ALPINE.JS --}}
                    @php $images = $cancha->getMedia('canchas'); @endphp
                    
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden" x-data="{ activeSlide: 0, slides: {{ $images->count() }} }">
                        {{-- Contenedor de Aspect Ratio (Fuerza 16:9 para que sea dinámico pero constante) --}}
                        <div class="relative aspect-video bg-gray-900">
                            @forelse ($images as $index => $media)
                                {{-- Imagen individual controlada por Alpine --}}
                                <div x-show="activeSlide === {{ $index }}"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 transform scale-105"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="absolute inset-0 w-full h-full">
                                    <img src="{{ $media->getUrl() }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover">
                                </div>
                            @empty
                                {{-- Placeholder si no hay imágenes --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 opacity-50 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xl font-semibold">Sin imágenes disponibles</span>
                                </div>
                            @endforelse

                            {{-- Botones de Navegación (Solo si hay más de 1 imagen) --}}
                            @if ($images->count() > 1)
                                {{-- Botón Anterior --}}
                                <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" 
                                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition backdrop-blur-sm focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                {{-- Botón Siguiente --}}
                                <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" 
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition backdrop-blur-sm focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                {{-- Indicadores (Puntitos abajo) --}}
                                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                                    @foreach($images as $index => $media)
                                        <button @click="activeSlide = {{ $index }}" 
                                                :class="{ 'bg-white w-6': activeSlide === {{ $index }}, 'bg-white/50 w-2': activeSlide !== {{ $index }} }"
                                                class="h-2 rounded-full transition-all duration-300 focus:outline-none"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 📝 2. DETALLES DE LA CANCHA --}}
                    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                        <div class="flex flex-wrap items-start justify-between mb-4 gap-4">
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">{{ $cancha->name }}</h1>
                            {{-- Precio destacado --}}
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 text-center shadow-sm">
                                <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider">Precio por Hora</p>
                                <p class="text-2xl font-extrabold text-indigo-900">S/ {{ number_format($cancha->price_per_hour, 2) }}</p>
                            </div>
                        </div>

                        {{-- Etiquetas Informativas --}}
                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                ⚽ {{ $cancha->sport->name }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-700/10">
                                📍 {{ $cancha->district->name }}
                            </span>
                        </div>

                        {{-- Dirección --}}
                        <div class="flex items-start text-gray-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="text-lg">{{ $cancha->address }}</span>
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Acerca de esta cancha</h3>
                            <div class="prose prose-indigo text-gray-700 max-w-none whitespace-pre-line leading-relaxed bg-gray-50 p-5 rounded-xl border border-gray-100">
                                {{ $cancha->description }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Formulario de Reserva (Sticky, ocupa 1/3) --}}
                <div class="lg:col-span-1">
                    {{-- Usamos 'sticky' para que el formulario siga al usuario al hacer scroll --}}
                    <div class="sticky top-8 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-10">
                        <div class="bg-indigo-600 p-4 sm:p-6">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Haz tu Reserva
                            </h3>
                            <p class="text-indigo-200 text-sm mt-1">Asegura tu turno ahora mismo.</p>
                        </div>
                        <div class="p-4 sm:p-6">
                            {{-- INCRUSTACIÓN DEL COMPONENTE LIVEWIRE --}}
                            @livewire('cancha-reserva-form', ['cancha' => $cancha])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- ¡IMPORTANTE! YA NO NECESITAMOS EL SCRIPT DE JAVASCRIPT AL FINAL --}}
{{-- Alpine.js se encarga de todo el carrusel automáticamente --}}