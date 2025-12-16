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
    
    {{-- 🟢 NUEVO: FONDO DE PATRÓN DE PELOTAS GRISES --}}
    <div class="fixed inset-0 z-0 pointer-events-none bg-gray-50">
        {{-- Patrón SVG repetitivo en base64 con los emojis solicitados, en escala de grises --}}
        <div class="absolute inset-0 opacity-5 grayscale" 
             style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Ctext x='10' y='30' font-size='20'%3E⚽%3C/text%3E%3Ctext x='50' y='60' font-size='20'%3E🏀%3C/text%3E%3Ctext x='90' y='30' font-size='20'%3E🏐%3C/text%3E%3Ctext x='10' y='90' font-size='20'%3E🏈%3C/text%3E%3Ctext x='50' y='110' font-size='20'%3E⚾%3C/text%3E%3Ctext x='90' y='90' font-size='20'%3E🏉%3C/text%3E%3C/svg%3E&quot;);">
        </div>
    </div>

    {{-- 1. ENCABEZADO (Header Info) --}}
    <div class="bg-white border-b border-gray-200 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    {{-- Breadcrumbs --}}
                    <div class="text-xs text-gray-500 mb-2 flex items-center gap-2">
                        <a href="{{ route('home') }}" class="hover:text-indigo-600">Clubes en Cusco</a>
                        <span>/</span>
                        <span class="text-gray-900">{{ $cancha->district->name }}</span>
                    </div>
                    
                    {{-- Título y Dirección --}}
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-2">{{ $cancha->name }}</h1>
                    <p class="text-gray-500 flex items-center text-sm md:text-base">
                        <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        {{ $cancha->address }} - {{ $cancha->district->name }}
                        <a href="#donde-estamos" class="ml-2 text-indigo-600 hover:underline text-xs font-bold">(ver en mapa)</a>
                    </p>
                </div>

                {{-- Precio Badge --}}
                <div class="flex items-center bg-white px-4 py-3 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase font-bold">Precio desde</p>
                        <p class="text-2xl font-black text-indigo-600">S/ {{ number_format($cancha->price_per_hour, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Z-INDEX RELATIVO PARA QUE EL CONTENIDO ESTÉ SOBRE EL FONDO --}}
    <div class="py-8 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 🟢 2. CARRUSEL DE FOTOS --}}
            <div class="mb-8 rounded-2xl overflow-hidden shadow-lg border border-gray-200 bg-gray-900 h-[300px] md:h-[450px] relative group">
                @php
                    $photos = $cancha->getMedia('canchas');
                    $photoUrls = $photos->map(fn($media) => $media->getUrl('large'))->toArray();
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
                        
                        {{-- Slides --}}
                        <template x-for="(photo, index) in photos" :key="index">
                            <div x-show="active === index"
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-500"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 w-full h-full">
                                <img :src="photo" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            </div>
                        </template>

                        {{-- Botones Navegación --}}
                        @if($totalPhotos > 1)
                            <button @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white p-2 rounded-full transition backdrop-blur-sm opacity-0 group-hover:opacity-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <button @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white p-2 rounded-full transition backdrop-blur-sm opacity-0 group-hover:opacity-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                            <div class="absolute bottom-4 right-4 bg-black/50 text-white text-xs px-3 py-1 rounded-full backdrop-blur-sm">
                                <span x-text="active + 1"></span> / <span x-text="total"></span>
                            </div>
                        @endif

                        {{-- LIGHTBOX --}}
                        <template x-teleport="body">
                            <div x-show="lightboxOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-md" style="display: none;">
                                <button @click.stop="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white z-50"><svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
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
                        <span class="text-6xl mb-2">📷</span>
                        <span class="font-medium">Sin Imágenes Disponibles</span>
                    </div>
                @endif
            </div>

            {{-- 3. GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- COLUMNA PRINCIPAL (70%) --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- SECCIÓN DE RESERVA (Livewire) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                            <div class="bg-indigo-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Elige tu turno</h3>
                        </div>
                        
                        <div class="p-6">
                            @livewire('cancha-reserva-form', ['cancha' => $cancha])
                        </div>
                    </div>

                    {{-- Descripción Extendida --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Información del Lugar</h3>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $cancha->description ?? 'El dueño no ha proporcionado una descripción detallada.' }}
                        </p>
                    </div>

                </div>

                {{-- COLUMNA LATERAL (30%): INFO Y CONTACTO --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- 🟢 1. TARJETA DE INFORMACIÓN CONSOLIDADA --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Detalles de la Cancha
                        </h3>

                        {{-- Deportes (Primero) --}}
                        <div class="mb-5">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Deportes Disponibles</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($cancha->sports as $sport)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $sport->icon }} {{ $sport->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <hr class="border-gray-100 my-4">

                        {{-- Horarios (Segundo) --}}
                        <div class="mb-5">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Horarios de Atención</p>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Lunes a Domingo</span>
                                <span class="font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded">
                                    {{ \Carbon\Carbon::parse($cancha->open_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($cancha->close_time)->format('h:i A') }}
                                </span>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-4">

                        {{-- Servicios (Tercero) --}}
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Servicios de la Cancha</p>
                            @if($cancha->services->count() > 0)
                                <div class="grid grid-cols-2 gap-y-2 gap-x-1">
                                    @foreach($cancha->services as $service)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <span class="text-lg">{{ $service->icon }}</span>
                                            <span class="truncate">{{ $service->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">No hay servicios especificados.</p>
                            @endif
                        </div>
                    </div>

                    {{-- 🟢 2. TARJETA DE UBICACIÓN Y CONTACTO --}}
                    <div id="donde-estamos" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Ubicación
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $cancha->address }}</p>
                        
                        @if($cancha->lat && $cancha->lng)
                            <div id="map-show" class="w-full h-48 rounded-lg bg-gray-100 border border-gray-200 mb-4"></div>
                            
                            {{-- Botones Waze/Maps --}}
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <a href="http://maps.google.com/maps?q={{ $cancha->lat }},{{ $cancha->lng }}" target="_blank" class="flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 py-2.5 rounded-lg text-xs font-bold text-gray-700 transition border border-gray-200">
                                    🗺️ Google Maps
                                </a>
                                <a href="https://waze.com/ul?ll={{ $cancha->lat }},{{ $cancha->lng }}&navigate=yes" target="_blank" class="flex items-center justify-center gap-2 bg-blue-50 hover:bg-blue-100 py-2.5 rounded-lg text-xs font-bold text-blue-700 transition border border-blue-100">
                                    🚙 Waze
                                </a>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs mb-4">Mapa no disponible</div>
                        @endif

                        {{-- Botón WhatsApp (GRANDE Y AL FINAL) --}}
                        @php
                            $telefono = $cancha->contact_phone ?? $cancha->user->phone;
                            $linkWa = "https://wa.me/" . preg_replace('/[^0-9]/', '', $telefono) . "?text=" . urlencode("Hola, vi su cancha " . $cancha->name . " en PichangaYa y me gustaría hacer una consulta.");
                        @endphp
                        <a href="{{ $linkWa }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-bold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Contactar al Dueño
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
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
            zoomControl: true
        });
        new google.maps.Marker({ position: location, map: map });
    }
</script>
@endif