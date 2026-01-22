<x-app-layout>
    <x-slot name="header">
        <hgroup class="flex items-center gap-3">
            <figure class="bg-indigo-600 p-2 rounded-lg text-white shadow-md">
                {{-- Ícono Header (SVG) --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </figure>
            <h1 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Registrar Nueva Cancha') }}
            </h1>
        </hgroup>
    </x-slot>

    <main class="py-12 bg-gray-50 min-h-screen">
        <section class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                {{-- Cabecera visual del formulario --}}
                <header class="bg-indigo-50/50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-medium text-indigo-900">Información General</h3>
                    <p class="text-sm text-indigo-600/70">Completa los datos para publicar tu cancha.</p>
                </header>

                <form action="{{ route('owner.canchas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 

                    {{-- SECCIÓN 1: DATOS PRINCIPALES --}}
                    <fieldset class="p-8 border-b border-gray-100">
                        <legend class="sr-only">Datos Básicos</legend>
                        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- 1. Nombre --}}
                            <p class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nombre de la Cancha</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all" placeholder="Ej: Estadio Monumental - Campo 1" required>
                                @error('name') <span class="block text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                            </p>

                            {{-- 2. Distrito --}}
                            <p>
                                <label for="district_id" class="block text-sm font-bold text-gray-700 mb-1">Distrito</label>
                                <select name="district_id" id="district_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 bg-white" required>
                                    <option value="">Seleccione ubicación...</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <span class="block text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                            </p>

                            {{-- 5. Precio --}}
                            <p>
                                <label for="price_per_hour" class="block text-sm font-bold text-gray-700 mb-1">Precio por Hora</label>
                                <span class="relative block">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 font-bold">S/</span>
                                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" class="pl-8 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 font-semibold text-gray-700" placeholder="0.00" required>
                                </span>
                                @error('price_per_hour') <span class="block text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                            </p>
                        </section>
                    </fieldset>

                    {{-- SECCIÓN 2: DEPORTES --}}
                    <fieldset class="p-8 border-b border-gray-100">
                        <header class="mb-4">
                            <legend class="block text-lg font-bold text-gray-800 mb-1">Deportes Disponibles</legend>
                            <section class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-md">Máximo 2</span>
                                <span class="text-sm text-gray-500">Selecciona los deportes principales.</span>
                            </section>
                        </header>
                        
                        <section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($sports as $sport)
                                <label class="cursor-pointer group relative block">
                                    <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                        class="peer sr-only sport-checkbox"
                                        @if(is_array(old('sports')) && in_array($sport->id, old('sports'))) checked @endif
                                    >
                                    {{-- TARJETA DE DEPORTE --}}
                                    <figure class="h-full p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-indigo-300 hover:shadow-lg transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 flex flex-col items-center justify-center gap-3 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:grayscale">
                                        
                                        {{-- ÍCONO DINÁMICO --}}
                                        <span class="p-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 peer-checked:bg-indigo-200 text-gray-500 peer-checked:text-indigo-700 transition-colors block">
                                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                {!! config('icons.sports.' . $sport->icon, config('icons.sports.default', '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" />')) !!}
                                            </svg>
                                        </span>
                                        
                                        <figcaption class="font-bold text-sm text-center leading-tight select-none">{{ $sport->name }}</figcaption>
                                        
                                        {{-- Check badge (SVG) --}}
                                        <span class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-indigo-600 transition-opacity block">
                                            <svg class="w-5 h-5 bg-white rounded-full" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </span>
                                    </figure>
                                </label>
                            @endforeach
                        </section>
                        @error('sports') <span class="block text-red-500 text-xs mt-2 font-bold">{{ $message }}</span> @enderror
                    </fieldset>

                    {{-- SECCIÓN 3: SERVICIOS --}}
                    <fieldset class="p-8 border-b border-gray-100">
                        <legend class="block text-lg font-bold text-gray-800 mb-3">Servicios Adicionales</legend>
                        <section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($services as $service)
                                <label class="cursor-pointer group relative block">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                        class="peer sr-only"
                                        @if(is_array(old('services')) && in_array($service->id, old('services'))) checked @endif
                                    >
                                    {{-- TARJETA DE SERVICIO --}}
                                    <figure class="px-4 py-3 bg-white border border-gray-200 rounded-lg hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 flex items-center gap-3 shadow-sm peer-checked:shadow-md">
                                        <span class="text-gray-400 peer-checked:text-emerald-600 block">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                {!! config('icons.services.' . $service->icon, config('icons.services.default', '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />')) !!}
                                            </svg>
                                        </span>
                                        <figcaption class="font-medium text-sm select-none">{{ $service->name }}</figcaption>
                                    </figure>
                                </label>
                            @endforeach
                        </section>
                    </fieldset>

                    {{-- SECCIÓN 4: HORARIOS Y CONTACTO --}}
                    <fieldset class="p-8 border-b border-gray-100">
                        <legend class="sr-only">Horarios y Contacto</legend>
                        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <p>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', '08:00') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </p>
                            <p>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', '23:00') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </p>
                            
                            {{-- 8. Teléfono --}}
                            <p>
                                <label for="contact_phone" class="block text-sm font-bold text-gray-700 mb-1">Teléfono (WhatsApp)</label>
                                <select name="contact_phone" id="contact_phone" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                                    @foreach($phones as $phone)
                                        <option value="{{ $phone['number'] }}" {{ old('contact_phone') == $phone['number'] ? 'selected' : '' }}>
                                            {{ $phone['number'] }} - {{ $phone['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </p>
                        </section>
                    </fieldset>

                    {{-- SECCIÓN 5: UBICACIÓN --}}
                    <fieldset class="p-8 border-b border-gray-100">
                        <legend class="block text-lg font-bold text-gray-800 mb-2">Ubicación</legend>
                        
                        {{-- 7. Dirección --}}
                        <p class="mb-4">
                            <label for="address" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dirección Escrita</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" placeholder="Av. Siempre Viva 123" required>
                        </p>

                        {{-- 9. Mapa --}}
                        <figure class="relative w-full h-96 rounded-2xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-gray-200">
                            {{-- Contenedor del mapa --}}
                            <section id="map" class="w-full h-full block"></section>
                            
                            <figcaption class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-2 rounded-lg shadow-md border border-gray-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-xs font-bold text-gray-700">Arrastra el marcador</span>
                            </figcaption>
                        </figure>
                        <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">
                        @error('lat') 
                            <p class="text-red-500 text-sm mt-2 font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> 
                                Debes marcar la ubicación en el mapa.
                            </p> 
                        @enderror
                    </fieldset>

                    {{-- SECCIÓN 6: IMÁGENES --}}
                    <fieldset class="p-8 border-b border-gray-100">
                        <section class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                            <legend class="block text-lg font-bold text-indigo-900 mb-2">Galería de Fotos</legend>
                            
                            <section class="flex items-center justify-center w-full">
                                <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-indigo-50 transition-colors">
                                    <figure class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <figcaption class="mb-2 text-sm text-indigo-600"><span class="font-bold">Click para subir</span> o arrastra tus fotos</figcaption>
                                        <p class="text-xs text-indigo-400">JPG, PNG, WebP (Máx 10MB)</p>
                                    </figure>
                                    <input type="file" name="images[]" id="images" multiple accept=".jpg,.jpeg,.png,.webp" class="hidden" required />
                                </label>
                            </section>
                            
                            <output id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 block"></output>
                            @error('images') <span class="block text-red-500 text-xs mt-2 font-bold">{{ $message }}</span> @enderror
                        </section>
                    </fieldset>

                    {{-- 11. Descripción --}}
                    <fieldset class="p-8">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Descripción (Opcional)</label>
                        <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Detalles extra como tipo de césped, reglas, etc...">{{ old('description') }}</textarea>
                    </fieldset>

                    {{-- Footer: Botones --}}
                    <footer class="flex items-center justify-end gap-4 p-8 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                        <a href="{{ route('owner.canchas.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors">Cancelar</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                            Guardar Cancha
                        </button>
                    </footer>
                </form>
            </article>
        </section>
    </main>

    {{-- Footer general de la App --}}
    <footer class="relative z-10 mt-6">
        <x-footer />
    </footer>
</x-app-layout>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

<script>
    // --- LÓGICA DE MÁXIMO 2 DEPORTES ---
    const sportCheckboxes = document.querySelectorAll('.sport-checkbox');
    const maxSports = 2;

    sportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selected = document.querySelectorAll('.sport-checkbox:checked');
            if (selected.length >= maxSports) {
                sportCheckboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.disabled = true;
                        // Estilo visual para deshabilitado
                        cb.closest('label').classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
            } else {
                sportCheckboxes.forEach(cb => {
                    cb.disabled = false;
                    cb.closest('label').classList.remove('opacity-50', 'cursor-not-allowed');
                });
            }
        });
    });

    // --- VISTA PREVIA DE IMÁGENES (Actualizada para usar <figure>) ---
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; 
        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    // Crear contenedor FIGURE cuadrado
                    const imgContainer = document.createElement('figure');
                    imgContainer.className = 'relative aspect-square rounded-xl overflow-hidden shadow-md border border-gray-200 group block';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500';
                    
                    imgContainer.appendChild(img);
                    previewContainer.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // --- MAPA ---
    let map, marker;
    function initMap() {
        const defaultCusco = { lat: -13.5167, lng: -71.9788 };
        const oldLat = "{{ old('lat') }}";
        const oldLng = "{{ old('lng') }}";
        let startPos = (oldLat && oldLng) ? { lat: parseFloat(oldLat), lng: parseFloat(oldLng) } : defaultCusco;

        // Estilos limpios para el mapa
        map = new google.maps.Map(document.getElementById("map"), { 
            center: startPos, 
            zoom: 15,
            mapTypeControl: false,
            streetViewControl: false
        });
        
        marker = new google.maps.Marker({ 
            position: startPos, 
            map: map, 
            draggable: true, 
            animation: google.maps.Animation.DROP 
        });

        marker.addListener("dragend", () => {
            const position = marker.getPosition();
            document.getElementById('lat').value = position.lat();
            document.getElementById('lng').value = position.lng();
        });

        if (!oldLat && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const userPos = { lat: position.coords.latitude, lng: position.coords.longitude };
                map.setCenter(userPos); 
                marker.setPosition(userPos);
                document.getElementById('lat').value = userPos.lat;
                document.getElementById('lng').value = userPos.lng;
            });
        }
    }
</script>