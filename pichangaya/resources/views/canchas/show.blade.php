<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center text-sm text-gray-600 leading-tight">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Explorar</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            <span class="font-semibold text-gray-800">{{ $cancha->name }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- COLUMNA IZQUIERDA: Detalles, Fotos y Mapa --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. FOTO PRINCIPAL Y PRECIO --}}
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden h-96 relative group">
                        @if($cancha->getFirstMediaUrl('canchas'))
                            <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-200 text-gray-400 text-2xl flex-col gap-2">
                                <span class="text-4xl">🏟️</span>
                                <span>Sin Imagen Disponible</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-xl shadow-lg border border-gray-100">
                            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Precio / Hora</span>
                            <p class="text-2xl font-bold text-indigo-600">S/ {{ number_format($cancha->price_per_hour, 2) }}</p>
                        </div>
                    </div>

                    {{-- 2. INFORMACIÓN, ETIQUETAS Y DESCRIPCIÓN --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        
                        {{-- Título y Dirección --}}
                        <div class="mb-6 border-b border-gray-100 pb-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $cancha->name }}</h1>
                            <p class="text-gray-500 flex items-center text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $cancha->address }}
                            </p>
                        </div>

                        {{-- 🟢 ETIQUETAS (TAGS) - CORREGIDO PARA MÚLTIPLES DEPORTES --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            
                            {{-- Bucle para mostrar TODOS los deportes seleccionados --}}
                            @foreach($cancha->sports as $sport)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                                    {{ $sport->icon }} {{ $sport->name }}
                                </span>
                            @endforeach
                            
                            {{-- Tag Distrito --}}
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                🏙️ {{ $cancha->district->name ?? 'Distrito' }}
                            </span>

                            {{-- Tag Ciudad --}}
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">
                                🇵🇪 Cusco
                            </span>
                        </div>

                        {{-- Descripción --}}
                        <div class="prose max-w-none text-gray-600 mb-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 border-l-4 border-indigo-500 pl-3">
                                Descripción del Lugar
                            </h3>
                            <p class="leading-relaxed">
                                {{ $cancha->description ?? 'El dueño no ha proporcionado una descripción detallada para esta cancha.' }}
                            </p>
                        </div>

                        {{-- Botón WhatsApp (Con el número específico de la cancha) --}}
                        @php
                            // Usa el número específico de la cancha, o el del dueño si no hay uno asignado
                            $telefonoDestino = $cancha->contact_phone ?? $cancha->user->phone ?? '51984000000'; 
                            $mensaje = "Hola, vi su cancha " . $cancha->name . " en PichangaYa y me gustaría reservar.";
                            $urlWa = "https://wa.me/" . $telefonoDestino . "?text=" . urlencode($mensaje);
                        @endphp
                        <div>
                            <a href="{{ $urlWa }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-xl transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Contactar al Dueño
                            </a>
                        </div>
                    </div>

                    {{-- 3. MAPA DE UBICACIÓN (Visible solo si hay coordenadas) --}}
                    @if($cancha->lat && $cancha->lng)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                📍 Ubicación Exacta
                            </h3>
                            <div id="map-view" class="w-full h-80 rounded-xl bg-gray-100 border border-gray-200"></div>
                            
                            {{-- Script del Mapa --}}
                            <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMapView" async defer></script>
                            <script>
                                function initMapView() {
                                    const location = { lat: {{ $cancha->lat }}, lng: {{ $cancha->lng }} };
                                    
                                    const map = new google.maps.Map(document.getElementById("map-view"), {
                                        center: location,
                                        zoom: 16,
                                        disableDefaultUI: false,
                                        streetViewControl: true
                                    });

                                    new google.maps.Marker({
                                        position: location,
                                        map: map,
                                        title: "{{ $cancha->name }}",
                                        animation: google.maps.Animation.DROP
                                    });
                                }
                            </script>
                        </div>
                    @else
                        {{-- Mensaje si no hay mapa --}}
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        El dueño aún no ha fijado la ubicación exacta en el mapa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- COLUMNA DERECHA: Reserva (Sticky) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-10">
                        <div class="bg-indigo-600 p-4 sm:p-6">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Haz tu Reserva
                            </h3>
                            <p class="text-indigo-200 text-sm mt-1">Selecciona fecha y hora.</p>
                        </div>
                        <div class="p-4 sm:p-6">
                            @livewire('cancha-reserva-form', ['cancha' => $cancha])
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>