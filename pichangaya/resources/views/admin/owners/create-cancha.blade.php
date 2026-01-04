<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\owners\create-cancha.blade.php --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 p-2 rounded-lg text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Nueva Cancha para: ') }} <span class="text-indigo-600">{{ $owner->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                {{-- Encabezado del Formulario --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Instalación</h3>
                    <p class="text-sm text-gray-500">Completa la información para publicar la cancha.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.owners.canchas.store', $owner) }}" method="POST" enctype="multipart/form-data">
                        @csrf 

                        {{-- SECCIÓN 1: INFORMACIÓN BÁSICA --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            {{-- Nombre --}}
                            <div class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nombre de la Cancha</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all py-3" placeholder="Ej: Estadio Monumental - Campo A" required>
                                @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            {{-- Distrito --}}
                            <div>
                                <label for="district_id" class="block text-sm font-bold text-gray-700 mb-1">Distrito</label>
                                <select name="district_id" id="district_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3" required>
                                    <option value="">Seleccione ubicación...</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            {{-- Precio --}}
                            <div>
                                <label for="price_per_hour" class="block text-sm font-bold text-gray-700 mb-1">Precio por Hora (S/)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">S/</span>
                                    </div>
                                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" class="pl-8 w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 font-semibold text-gray-700" placeholder="0.00" required>
                                </div>
                                @error('price_per_hour') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100">

                        {{-- SECCIÓN 2: DEPORTES (VISUALMENTE MEJORADO) --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-1">Deportes Disponibles</label>
                            <p class="text-sm text-gray-500 mb-4">Selecciona los deportes que se pueden practicar (Máx 2).</p>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($sports as $sport)
                                    <label class="cursor-pointer group relative">
                                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                            class="peer sr-only sport-checkbox"
                                            @if(is_array(old('sports')) && in_array($sport->id, old('sports'))) checked @endif
                                        >
                                        {{-- TARJETA DE DEPORTE --}}
                                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-indigo-300 hover:shadow-lg transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 flex flex-col items-center justify-center gap-3 h-full peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">
                                            
                                            {{-- ÍCONO CON FONDO --}}
                                            <div class="p-3 rounded-full bg-gray-100 text-gray-500 group-hover:scale-110 group-hover:bg-indigo-100 group-hover:text-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white transition-all duration-300">
                                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                                </svg>
                                            </div>
                                            
                                            <span class="font-bold text-sm text-center select-none">{{ $sport->name }}</span>
                                            
                                            {{-- Checkmark Badge (aparece solo al seleccionar) --}}
                                            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-indigo-600 transition-opacity">
                                                <svg class="w-5 h-5 bg-white rounded-full" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sports') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- SECCIÓN 3: SERVICIOS (VISUALMENTE MEJORADO) --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-1">Servicios Adicionales</label>
                            <p class="text-sm text-gray-500 mb-4">¿Qué comodidades ofrece esta cancha?</p>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @isset($services)
                                    @foreach($services as $service)
                                        <label class="cursor-pointer group relative">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                                class="peer sr-only"
                                                @if(is_array(old('services')) && in_array($service->id, old('services'))) checked @endif
                                            >
                                            {{-- TARJETA DE SERVICIO --}}
                                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-lg hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 flex items-center gap-3 shadow-sm peer-checked:shadow-md">
                                                <div class="text-gray-400 peer-checked:text-emerald-600">
                                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        {!! config('icons.services.' . $service->icon, config('icons.services.default')) !!}
                                                    </svg>
                                                </div>
                                                <span class="font-medium text-sm select-none">{{ $service->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                @endisset
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100">

                        {{-- SECCIÓN 4: HORARIOS Y CONTACTO --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', '08:00') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', '23:00') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">WhatsApp de Contacto</label>
                                <input type="text" name="contact_phone" value="{{ old('contact_phone', $owner->phone) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                            </div>
                        </div>

                        {{-- SECCIÓN 5: UBICACIÓN Y MAPA --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-2">Ubicación Exacta</label>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dirección Escrita</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" placeholder="Av. Principal 123, Referencia..." required>
                            </div>

                            <div class="relative w-full h-96 rounded-2xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-gray-200">
                                <div id="map" class="w-full h-full"></div>
                                {{-- AQUI ESTABA EL EMOJI, REEMPLAZADO POR SVG --}}
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-md shadow text-xs font-bold text-gray-600 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    Arrastra el marcador
                                </div>
                            </div>
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">
                            @error('lat') <p class="text-red-500 text-sm mt-2 font-bold flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Debes seleccionar la ubicación en el mapa.</p> @enderror
                        </div>

                        {{-- SECCIÓN 6: IMÁGENES --}}
                        <div class="mb-8 bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                            <label class="block text-lg font-bold text-indigo-900 mb-2">Galería de Fotos</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-indigo-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-indigo-50 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-indigo-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-indigo-600"><span class="font-bold">Click para subir</span> o arrastra las imágenes</p>
                                        <p class="text-xs text-indigo-400">PNG, JPG (Mínimo 1)</p>
                                    </div>
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" required />
                                </label>
                            </div>
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>
                            @error('images') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Descripción Adicional</label>
                            <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Información extra para el cliente...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.owners.courts', $owner) }}" class="text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Registrar Cancha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- SCRIPTS (Mismos scripts funcionales, solo estilo visual en JS si fuera necesario, aquí no lo es) --}}
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
                        // Estilos visuales para deshabilitado (opacidad al contenedor padre)
                        cb.closest('label').classList.add('opacity-40', 'cursor-not-allowed'); 
                    }
                });
            } else {
                sportCheckboxes.forEach(cb => {
                    cb.disabled = false;
                    cb.closest('label').classList.remove('opacity-40', 'cursor-not-allowed');
                });
            }
        });
    });

    // --- SCRIPT DE IMÁGENES (CON MEJOR PREVISUALIZACIÓN) ---
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; 
        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'relative aspect-square rounded-lg overflow-hidden shadow-sm border border-gray-200 group';
                    
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

    // --- SCRIPT MAPA ---
    let map;
    let marker;

    function initMap() {
        const defaultCusco = { lat: -13.5167, lng: -71.9788 }; 
        const oldLat = "{{ old('lat') }}";
        const oldLng = "{{ old('lng') }}";

        let startPos = defaultCusco;
        let useGps = true; 

        if (oldLat && oldLng) {
            startPos = { lat: parseFloat(oldLat), lng: parseFloat(oldLng) };
            useGps = false; 
        }

        map = new google.maps.Map(document.getElementById("map"), {
            center: startPos,
            zoom: 15,
            styles: [ // Estilo opcional para limpiar el mapa y hacerlo más moderno
                { featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }
            ]
        });

        marker = new google.maps.Marker({
            position: startPos,
            map: map,
            draggable: true,
            title: "Ubicación de la cancha",
            animation: google.maps.Animation.DROP
        });

        marker.addListener("dragend", () => {
            const position = marker.getPosition();
            document.getElementById('lat').value = position.lat();
            document.getElementById('lng').value = position.lng();
        });

        if (useGps && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const userPos = { lat: position.coords.latitude, lng: position.coords.longitude };
                    map.setCenter(userPos);
                    marker.setPosition(userPos);
                    document.getElementById('lat').value = userPos.lat;
                    document.getElementById('lng').value = userPos.lng;
                },
                () => {
                    document.getElementById('lat').value = startPos.lat;
                    document.getElementById('lng').value = startPos.lng;
                }
            );
        } else {
            document.getElementById('lat').value = startPos.lat;
            document.getElementById('lng').value = startPos.lng;
        }
    }
</script>