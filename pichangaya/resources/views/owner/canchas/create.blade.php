<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Cancha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('owner.canchas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf 

                        {{-- 1. Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nombre de la Cancha</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Estadio Monumental" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- 2. Selectores de Distrito y Deporte --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="district_id" class="block text-sm font-semibold text-gray-700">Distrito</label>
                                <select name="district_id" id="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="sport_id" class="block text-sm font-semibold text-gray-700">Deporte</label>
                                <select name="sport_id" id="sport_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($sports as $sport)
                                        <option value="{{ $sport->id }}" {{ old('sport_id') == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                                @error('sport_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- 3. Precio --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-semibold text-gray-700">Precio por Hora (S/)</label>
                            <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00" required>
                            @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- 4. NUEVO: Horarios de Atención --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', '08:00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('open_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', '23:00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('close_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- 5. Dirección Texto --}}
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Dirección Escrita</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Av. Siempre Viva 123" required>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- 6. MAPA INTERACTIVO --}}
                        <div class="mt-6 mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ubicación en el Mapa (Arrastra el marcador rojo)</label>
                            
                            <div id="map" class="w-full h-96 rounded-lg shadow-md border border-gray-300"></div>
                            
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">
                            
                            <p class="text-xs text-gray-500 mt-2">
                                * Permite el acceso a tu ubicación para centrar el mapa, o arrastra el marcador manualmente.
                            </p>
                        </div>

                        {{-- 7. Imágenes (Múltiples y Obligatorias) --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-semibold text-gray-700">Fotos de la Cancha (Mínimo 1)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                            
                            @error('images') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- 8. Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Descripción (Opcional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <a href="{{ route('owner.canchas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Guardar Cancha</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- SCRIPTS --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

<script>
    // 1. Script de Imágenes
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; 
        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-md shadow inline-block mr-2 mb-2';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // 2. Script del Mapa (Con Persistencia)
    let map;
    let marker;

    function initMap() {
        const defaultCusco = { lat: -13.5167, lng: -71.9788 };
        
        // Recuperamos valores antiguos si falló la validación
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
                    const userPos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
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