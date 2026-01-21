{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\canchas\show.blade.php --}}
{{-- 🟢 SEO DINÁMICO PARA ESTA CANCHA --}}
@section('title', $cancha->name . ' - PichangaYa')

@push('meta')
    <meta name="description" content="Reserva en {{ $cancha->name }}. {{ Str::limit($cancha->description ?? 'Cancha deportiva en Cusco', 100) }}. Ubicación: {{ $cancha->district->name }}. Precio: S/ {{ $cancha->price_per_hour }}">
    <meta name="keywords" content="{{ $cancha->name }}, futbol cusco, {{ $cancha->district->name }}, alquiler canchas, pichanga">
    
    {{-- Open Graph / WhatsApp --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('canchas.show', $cancha) }}">
    <meta property="og:title" content="{{ $cancha->name }} | Reserva ahora en PichangaYa">
    <meta property="og:description" content="Juega en {{ $cancha->name }} ({{ $cancha->district->name }}). Disponible desde S/ {{ $cancha->price_per_hour }}.">
    @if($cancha->getFirstMediaUrl('canchas'))
        <meta property="og:image" content="{{ $cancha->getFirstMediaUrl('canchas') }}">
    @endif
@endpush

<x-app-layout>
    
    {{-- ARTICLE: Contenedor Semántico Principal --}}
    <article class="relative min-h-screen">

        {{-- 🟢 FONDO DECORATIVO (Se mantiene igual, es puramente visual) --}}
        <div class="fixed inset-0 z-0 pointer-events-none" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-200 dark:bg-gray-950 transition-colors duration-300"></div>
            <div class="absolute inset-0 opacity-30 dark:opacity-20 bg-cover bg-center bg-no-repeat transition-opacity duration-500" 
                 style="background-image: url('{{ asset('images/fondo.webp') }}');">
            </div>
        </div>

        {{-- 1. HEADER PRINCIPAL (Título, Precio, Nav) --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm relative z-20 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        {{-- NAV: Breadcrumbs --}}
                        <nav aria-label="Breadcrumb" class="text-xs text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                            <a href="{{ route('home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Clubes en Cusco</a>
                            <span aria-hidden="true">/</span>
                            <span class="text-gray-900 dark:text-gray-200">{{ $cancha->district->name }}</span>
                        </nav>
                        
                        {{-- Título y Dirección --}}
                        <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-2 transition-colors duration-300">
                            {{ $cancha->name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-300 flex items-center text-sm md:text-base transition-colors duration-300">
                            <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            {{ $cancha->address }} - {{ $cancha->district->name }}
                            <a href="#donde-estamos" class="ml-2 text-indigo-600 dark:text-indigo-400 hover:underline text-xs font-bold">(ver en mapa)</a>
                        </p>
                    </div>

                    {{-- Precio Badge --}}
                    <div class="flex items-center bg-white dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm transition-colors duration-300">
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Precio desde</p>
                            <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">S/ {{ number_format($cancha->price_per_hour, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="py-8 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- 2. FIGURE: CARRUSEL DE FOTOS --}}
                <figure class="mb-8 rounded-2xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 bg-gray-900 h-[300px] md:h-[450px] relative group" aria-label="Galería de imágenes">
                    @php
                        $photos = $cancha->getMedia('canchas');
                        $photoUrls = $photos->map(fn($media) => $media->getUrl())->toArray();
                        $totalPhotos = count($photoUrls);
                    @endphp

                    @if($totalPhotos > 0)
                        <div x-data="{ 
                                active: 0, 
                                total: {{ $totalPhotos }},
                                photos: {{ Js::from($photoUrls) }},
                                autoplay: null,
                                lightboxOpen: false,
                                init() { this.startAutoplay(); },
                                startAutoplay() { if (this.total > 1) { this.autoplay = setInterval(() => { this.next() }, 5000); } },
                                stopAutoplay() { clearInterval(this.autoplay); },
                                next() { this.active = (this.active === this.total - 1) ? 0 : this.active + 1; },
                                prev() { this.active = (this.active === 0) ? this.total - 1 : this.active - 1; },
                                openLightbox() { this.lightboxOpen = true; this.stopAutoplay(); },
                                closeLightbox() { this.lightboxOpen = false; this.startAutoplay(); }
                             }"
                             @mouseenter="stopAutoplay()" 
                             @mouseleave="if(!lightboxOpen) startAutoplay()"
                             @keydown.escape.window="closeLightbox()"
                             class="relative w-full h-full cursor-pointer"
                             @click="openLightbox()">
                            
                            <template x-for="(photo, index) in photos" :key="index">
                                <div x-show="active === index"
                                     x-transition:enter="transition ease-out duration-700"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-500"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="absolute inset-0 w-full h-full">
                                    <img :src="photo" class="w-full h-full object-cover" alt="Foto de {{ $cancha->name }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                                </div>
                            </template>

                            @if($totalPhotos > 1)
                                <button @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white p-2 rounded-full transition backdrop-blur-sm opacity-0 group-hover:opacity-100" aria-label="Anterior">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                <button @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white p-2 rounded-full transition backdrop-blur-sm opacity-0 group-hover:opacity-100" aria-label="Siguiente">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                                <div class="absolute bottom-4 right-4 bg-black/50 text-white text-xs px-3 py-1 rounded-full backdrop-blur-sm">
                                    <span x-text="active + 1"></span> / <span x-text="total"></span>
                                </div>
                            @endif

                            <template x-teleport="body">
                                <div x-show="lightboxOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-md" style="display: none;">
                                    <button @click.stop="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white z-50" aria-label="Cerrar"><svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                                    <div class="relative w-full h-full flex items-center justify-center p-4">
                                        <img :src="photos[active]" class="max-w-full max-h-full object-contain rounded shadow-2xl">
                                        @if($totalPhotos > 1)
                                            <button @click.stop="prev()" class="absolute left-4 text-white/50 hover:text-white p-4"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                                            <button @click.stop="next()" class="absolute right-4 text-white/50 hover:text-white p-4"><svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
                                        @endif
                                    </div>
                                </div>
                            </template>
                        </div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">Sin Imágenes Disponibles</span>
                        </div>
                    @endif
                </figure>

                {{-- 3. GRID PRINCIPAL --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- COLUMNA PRINCIPAL (Izquierda) --}}
                    <div class="lg:col-span-8 space-y-8">
                        
                        {{-- ALERTAS (Se mantienen divs porque son elementos de UI transitorios) --}}
                        @if ($errors->any() || session('error'))
                            <div class="space-y-4" role="alert">
                                @if ($errors->any())
                                    <div class="bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative shadow">
                                        <strong class="font-bold">¡Atención!</strong>
                                        <span class="block sm:inline">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </span>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="bg-yellow-100 dark:bg-yellow-900/50 border border-yellow-400 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded relative shadow">
                                        <strong class="font-bold">¡Ups!</strong>
                                        <span class="block sm:inline">{{ session('error') }}</span>
                                        <div class="mt-2">
                                            <a href="{{ route('reservas.user.index') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded transition shadow-sm">
                                                Ver mis reservas pendientes →
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- SECTION: TARJETA DE RESERVA --}}
                        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300" aria-label="Sistema de Reservas">
                            <header class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-between transition-colors duration-300">
                                <div class="flex items-center gap-2">
                                    <div class="bg-indigo-100 dark:bg-indigo-900/50 p-2 rounded-full">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Elige tu turno</h3>
                                </div>
                            </header>
                            
                            {{-- LEYENDA --}}
                            <div class="px-6 pt-4 flex flex-wrap gap-4 text-xs font-bold" aria-hidden="true">
                                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-white border border-gray-300 rounded shadow-sm"></div><span class="text-gray-600 dark:text-gray-400">Libre</span></div>
                                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-yellow-100 border border-yellow-400 rounded shadow-sm"></div><span class="text-yellow-700 dark:text-yellow-500">Por confirmar</span></div>
                                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-gray-200 border border-gray-400 rounded shadow-sm"></div><span class="text-gray-500 dark:text-gray-400">Ocupado</span></div>
                                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-indigo-600 border border-indigo-600 rounded shadow-sm"></div><span class="text-indigo-600 dark:text-indigo-400">Tu Juego</span></div>
                            </div>

                            <div class="p-6">
                                @livewire('cancha-reserva-form', ['cancha' => $cancha])
                            </div>
                        </section>

                        {{-- SECTION: Descripción --}}
                        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300" aria-labelledby="desc-heading">
                            <h3 id="desc-heading" class="text-lg font-bold text-gray-900 dark:text-white mb-3">Información del Lugar</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed transition-colors duration-300">
                                {{ $cancha->description ?? 'El dueño no ha proporcionado una descripción detallada.' }}
                            </p>
                        </section>

                    </div>

                    {{-- ASIDE: COLUMNA LATERAL (Derecha) --}}
                    <aside class="lg:col-span-4 space-y-6">

                        {{-- DETALLES DE LA CANCHA --}}
                        <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300" aria-labelledby="details-heading">
                            <h3 id="details-heading" class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Detalles de la Cancha
                            </h3>

                            <div class="mb-5">
                                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2">Deportes Disponibles</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($cancha->sports as $sport)
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800 gap-1">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                            </svg>
                                            {{ $sport->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="border-gray-100 dark:border-gray-700 my-4">

                            <div class="mb-5">
                                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-2">Horarios de Atención</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Lunes a Domingo</span>
                                    {{-- TIME: Etiqueta semántica --}}
                                    <time class="font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                        {{ \Carbon\Carbon::parse($cancha->open_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($cancha->close_time)->format('h:i A') }}
                                    </time>
                                </div>
                            </div>

                            <hr class="border-gray-100 dark:border-gray-700 my-4">

                            {{-- SERVICIOS --}}
                            <div>
                                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-3">Servicios Adicionales</p>
                                @if($cancha->services->count() > 0)
                                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($cancha->services as $service)
                                            <li class="flex items-center gap-2 p-2 rounded-lg bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700">
                                                <span class="text-indigo-500 dark:text-indigo-400">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        {!! config('icons.services.' . $service->icon, config('icons.services.default')) !!}
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $service->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">No hay servicios especificados.</p>
                                @endif
                            </div>
                        </section>

                        {{-- UBICACIÓN --}}
                        <section id="donde-estamos" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300" aria-labelledby="location-heading">
                            <h3 id="location-heading" class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ubicación
                            </h3>
                            
                            {{-- ADDRESS: Semántica para direcciones --}}
                            <address class="not-italic text-sm text-gray-600 dark:text-gray-400 mb-3 border-l-2 border-indigo-400 pl-3">
                                {{ $cancha->address }}
                            </address>
                            
                            @if($cancha->lat && $cancha->lng)
                                {{-- FIGURE: Contenedor del Mapa --}}
                                <figure>
                                    <div id="map-show" class="w-full h-48 rounded-lg bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 mb-4 transition-colors duration-300"></div>
                                    <figcaption class="sr-only">Mapa mostrando la ubicación exacta de {{ $cancha->name }}</figcaption>
                                </figure>
                                
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <a href="http://maps.google.com/maps?q={{ $cancha->lat }},{{ $cancha->lng }}" target="_blank" class="flex items-center justify-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 py-2.5 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200 transition border border-gray-200 dark:border-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7" /><path d="M9 4v13" /><path d="M15 7v5" /><path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879" /><path d="M19 18v.01" /></svg>
                                        Google Maps
                                    </a>
                                    <a href="https://waze.com/ul?ll={{ $cancha->lat }},{{ $cancha->lng }}&navigate=yes" target="_blank" class="flex items-center justify-center gap-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 py-2.5 rounded-lg text-xs font-bold text-blue-700 dark:text-blue-300 transition border border-blue-100 dark:border-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-waze"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.66 17.52a7 7 0 0 1 -3.66 -4.52c2 0 3 -1 3 -2.51c0 -3.92 2.25 -7.49 7.38 -7.49c4.62 0 7.62 3.51 7.62 8a8.08 8.08 0 0 1 -3.39 6.62" /><path d="M10 18.69a17.29 17.29 0 0 0 3.33 .3h.54" /><path d="M14 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M6 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M16 9h.01" /><path d="M11 9h.01" /></svg>
                                        Waze
                                    </a>
                                </div>
                            @else
                                <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-gray-400 text-xs mb-4">Mapa no disponible</div>
                            @endif

                            @php
                                $telefono = $cancha->contact_phone ?? $cancha->user->phone ?? '';
                                $cleanPhone = preg_replace('/[^0-9]/', '', $telefono);
                                if (strlen($cleanPhone) == 9) { $cleanPhone = '51' . $cleanPhone; }
                                $linkWa = "https://wa.me/" . $cleanPhone . "?text=" . urlencode("Hola, vi su cancha " . $cancha->name . " en PichangaYa y me gustaría hacer una consulta.");
                            @endphp
                            
                            <a href="{{ $linkWa }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-bold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Contactar al Dueño
                            </a>
                        </section>

                    </aside>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <footer class="relative z-10">
            <x-footer />
        </footer>
    </article>
</x-app-layout>

{{-- SCRIPTS DE MAPA --}}
@if($cancha->lat && $cancha->lng)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initShowMap" async defer></script>
<script>
    function initShowMap() {
        const location = { lat: {{ $cancha->lat }}, lng: {{ $cancha->lng }} };
        const map = new google.maps.Map(document.getElementById("map-show"), {
            center: location,
            zoom: 15,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                {
                    "featureType": "poi",
                    "stylers": [{ "visibility": "off" }]
                }
            ]
        });
        new google.maps.Marker({ 
            position: location, 
            map: map,
            animation: google.maps.Animation.DROP 
        });
    }
</script>
@endif