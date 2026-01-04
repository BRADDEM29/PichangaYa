<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 p-2 rounded-lg text-white shadow-md">
                {{-- Icono Edit (SVG) --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Editar Cancha: ') }} <span class="text-indigo-600">{{ $cancha->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                {{-- Cabecera visual --}}
                <div class="bg-indigo-50/50 px-6 py-4 border-b border-indigo-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-indigo-900">Datos de la Cancha</h3>
                        <p class="text-sm text-indigo-600/70">Actualiza la información necesaria.</p>
                    </div>
                    {{-- Botón para volver --}}
                    <a href="{{ route('owner.canchas.index') }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Volver al listado
                    </a>
                </div>

                <div class="p-8">
                    
                    <form action="{{ route('owner.canchas.update', $cancha) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT')

                        {{-- SECCIÓN 1: DATOS PRINCIPALES --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            {{-- 1. Nombre --}}
                            <div class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nombre de la Cancha</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $cancha->name) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 transition-all" required>
                                @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            {{-- 2. Distrito --}}
                            <div>
                                <label for="district_id" class="block text-sm font-bold text-gray-700 mb-1">Distrito</label>
                                <select name="district_id" id="district_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 bg-white" required>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id', $cancha->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            {{-- 5. Precio --}}
                            <div>
                                <label for="price_per_hour" class="block text-sm font-bold text-gray-700 mb-1">Precio por Hora</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-bold">S/</span>
                                    </div>
                                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', $cancha->price_per_hour) }}" class="pl-8 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 font-semibold text-gray-700" required>
                                </div>
                                @error('price_per_hour') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100">

                        {{-- SECCIÓN 2: DEPORTES (Lógica checked + Config Icons) --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-1">Deportes Disponibles</label>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-md">Máximo 2</span>
                                <p class="text-sm text-gray-500">Modifica los deportes si es necesario.</p>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($sports as $sport)
                                    <label class="cursor-pointer group relative">
                                        {{-- 
                                            LOGICA CHECKED:
                                            Comprobamos si el ID del deporte está en el array de IDs de la cancha ($cancha->sports)
                                        --}}
                                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                            class="peer sr-only sport-checkbox"
                                            @if(in_array($sport->id, old('sports', $cancha->sports->pluck('id')->toArray()))) checked @endif
                                        >
                                        
                                        <div class="h-full p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-indigo-300 hover:shadow-lg transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 flex flex-col items-center justify-center gap-3 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:grayscale">
                                            
                                            <div class="p-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 peer-checked:bg-indigo-200 text-gray-500 peer-checked:text-indigo-700 transition-colors">
                                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    {{-- ICONO DINÁMICO --}}
                                                    {!! config('icons.sports.' . $sport->icon, config('icons.sports.default', '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" />')) !!}
                                                </svg>
                                            </div>
                                            
                                            <span class="font-bold text-sm text-center leading-tight select-none">{{ $sport->name }}</span>
                                            
                                            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-indigo-600 transition-opacity">
                                                <svg class="w-5 h-5 bg-white rounded-full" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sports') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- SECCIÓN 3: SERVICIOS --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-3">Servicios Adicionales</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @foreach($services as $service)
                                    <label class="cursor-pointer group relative">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                            class="peer sr-only"
                                            @if(in_array($service->id, old('services', $cancha->services->pluck('id')->toArray()))) checked @endif
                                        >
                                        <div class="px-4 py-3 bg-white border border-gray-200 rounded-lg hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 flex items-center gap-3 shadow-sm peer-checked:shadow-md">
                                            <div class="text-gray-400 peer-checked:text-emerald-600">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    {{-- ICONO DINÁMICO --}}
                                                    {!! config('icons.services.' . $service->icon, config('icons.services.default', '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />')) !!}
                                                </svg>
                                            </div>
                                            <span class="font-medium text-sm select-none">{{ $service->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100">

                        {{-- SECCIÓN 4: HORARIOS Y CONTACTO --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', \Carbon\Carbon::parse($cancha->open_time)->format('H:i')) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', \Carbon\Carbon::parse($cancha->close_time)->format('H:i')) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </div>
                            
                            {{-- Teléfono --}}
                            <div>
                                <label for="contact_phone" class="block text-sm font-bold text-gray-700 mb-1">Teléfono (WhatsApp)</label>
                                <select name="contact_phone" id="contact_phone" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                                    @foreach($phones as $phone)
                                        <option value="{{ $phone['number'] }}" {{ old('contact_phone', $cancha->contact_phone) == $phone['number'] ? 'selected' : '' }}>
                                            {{ $phone['number'] }} - {{ $phone['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- SECCIÓN 5: UBICACIÓN --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-2">Ubicación</label>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dirección Escrita</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $cancha->address) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </div>

                            <div class="relative w-full h-96 rounded-2xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-gray-200">
                                <div id="map" class="w-full h-full"></div>
                                {{-- SVG Pin --}}
                                <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-2 rounded-lg shadow-md border border-gray-100 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs font-bold text-gray-700">Arrastra para corregir</span>
                                </div>
                            </div>
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat', $cancha->lat) }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng', $cancha->lng) }}">
                        </div>

                        {{-- SECCIÓN 6: GESTIÓN DE IMÁGENES --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-900 mb-4">Galería de Fotos</label>
                            
                            {{-- 1. Imágenes Existentes --}}
                            @if($cancha->getMedia('canchas')->count() > 0)
                                <div class="mb-4">
                                    <p class="text-xs font-bold text-gray-500 uppercase mb-2">Imágenes Actuales (Marca para eliminar)</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                        @foreach($cancha->getMedia('canchas') as $media)
                                            <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-200">
                                                <img src="{{ $media->getUrl() }}" class="w-full h-32 object-cover">
                                                
                                                {{-- Checkbox de eliminar (Overlay) --}}
                                                <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                                    <input type="checkbox" name="delete_images[]" value="{{ $media->id }}" class="peer sr-only">
                                                    <div class="bg-white text-red-600 rounded-full p-2 shadow-lg mb-2 peer-checked:bg-red-600 peer-checked:text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </div>
                                                    <span class="text-white text-xs font-bold px-2 py-1 bg-red-600 rounded hidden peer-checked:block">Se eliminará</span>
                                                </label>
                                                
                                                {{-- Indicador visual si está seleccionado --}}
                                                <div class="absolute top-2 right-2 hidden peer-checked:block">
                                                    <span class="w-3 h-3 bg-red-500 rounded-full block"></span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 2. Subir Nuevas Imágenes --}}
                            <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                                <p class="text-xs font-bold text-indigo-500 uppercase mb-2">Agregar Nuevas Fotos</p>
                                <div class="flex items-center justify-center w-full">
                                    <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-indigo-50 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <p class="mb-2 text-sm text-indigo-600"><span class="font-bold">Click para subir</span></p>
                                        </div>
                                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" />
                                    </label>
                                </div>
                                <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $cancha->description) }}</textarea>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('owner.canchas.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                Actualizar Cancha
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

<script>
    // --- LÓGICA DE MÁXIMO 2 DEPORTES ---
    const sportCheckboxes = document.querySelectorAll('.sport-checkbox');
    const maxSports = 2;

    function checkSportsLimit() {
        const selected = document.querySelectorAll('.sport-checkbox:checked');
        if (selected.length >= maxSports) {
            sportCheckboxes.forEach(cb => {
                if (!cb.checked) {
                    cb.disabled = true;
                    cb.closest('label').classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        } else {
            sportCheckboxes.forEach(cb => {
                cb.disabled = false;
                cb.closest('label').classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }
    }

    // Ejecutar al cargar la página (para bloquear si ya hay 2 guardados)
    document.addEventListener('DOMContentLoaded', checkSportsLimit);

    // Ejecutar al cambiar un checkbox
    sportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', checkSportsLimit);
    });

    // --- VISTA PREVIA DE IMÁGENES NUEVAS ---
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; 
        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'relative aspect-square rounded-xl overflow-hidden shadow-md border border-gray-200 group';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    
                    imgContainer.appendChild(img);
                    previewContainer.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // --- MAPA (Carga ubicación guardada) ---
    let map, marker;
    function initMap() {
        // Obtenemos lat/lng guardadas o valores por defecto si fallara algo
        const savedLat = parseFloat("{{ $cancha->lat }}") || -13.5167;
        const savedLng = parseFloat("{{ $cancha->lng }}") || -71.9788;
        const startPos = { lat: savedLat, lng: savedLng };

        map = new google.maps.Map(document.getElementById("map"), { 
            center: startPos, 
            zoom: 16,
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
    }
</script>