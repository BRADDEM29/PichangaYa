<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Cancha para: ') . $owner->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- 🟢 CORRECCIÓN: AGREGADO enctype="multipart/form-data" --}}
                    <form action="{{ route('admin.owners.canchas.store', $owner) }}" method="POST" enctype="multipart/form-data">
                        @csrf 

                        {{-- 1. Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nombre de la Cancha</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Estadio Monumental" required>
                            @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 2. Distrito --}}
                        <div class="mb-4">
                            <label for="district_id" class="block text-sm font-semibold text-gray-700">Distrito</label>
                            <select name="district_id" id="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccione...</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 3. DEPORTES (MÁXIMO 2) --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Deportes Disponibles</label>
                            <p class="text-xs text-gray-500 mb-3">Selecciona máximo 2 deportes.</p>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($sports as $sport)
                                    <label class="cursor-pointer relative">
                                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                            class="peer sr-only sport-checkbox"
                                            @if(is_array(old('sports')) && in_array($sport->id, old('sports'))) checked @endif
                                        >
                                        <div class="p-3 bg-white border rounded-lg hover:bg-gray-50 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 transition-all flex items-center justify-center gap-2 text-sm font-medium text-gray-600 shadow-sm peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">
                                            <span class="text-xl">{{ $sport->icon ?? '⚽' }}</span> {{ $sport->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sports') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 4. SERVICIOS (SIN LÍMITE) --}}
                        <div class="mb-6 border-t border-gray-100 pt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Servicios Adicionales</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @isset($services)
                                    @foreach($services as $service)
                                        <label class="cursor-pointer relative">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                                class="peer sr-only"
                                                @if(is_array(old('services')) && in_array($service->id, old('services'))) checked @endif
                                            >
                                            <div class="p-3 bg-white border rounded-lg hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all flex items-center justify-center gap-2 text-sm font-medium text-gray-600 shadow-sm">
                                                <span class="text-xl">{{ $service->icon ?? '✅' }}</span> {{ $service->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                @endisset
                            </div>
                        </div>

                        {{-- 5. Precio --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-semibold text-gray-700">Precio por Hora (S/)</label>
                            <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00" required>
                            @error('price_per_hour') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 6. Horarios --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', '08:00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('open_time') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', '23:00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('close_time') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- 7. Dirección --}}
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Dirección Escrita</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Av. Siempre Viva 123" required>
                            @error('address') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 8. Teléfono de Contacto --}}
                        <div class="mb-4">
                            <label for="contact_phone" class="block text-sm font-semibold text-gray-700">Teléfono de Contacto (WhatsApp)</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $owner->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('contact_phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 9. Mapa --}}
                        <div class="mt-6 mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ubicación en el Mapa</label>
                            <div id="map" class="w-full h-96 rounded-lg shadow-md border border-gray-300"></div>
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">
                            <p class="text-xs text-gray-500 mt-2">* Arrastra el marcador rojo para fijar la ubicación exacta.</p>
                            @error('lat') <p class="text-red-500 text-xs mt-1 font-bold">Selecciona una ubicación en el mapa.</p> @enderror
                        </div>

                        {{-- 10. Imágenes --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-semibold text-gray-700">Fotos de la Cancha (Mínimo 1)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                            @error('images') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 11. Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Descripción (Opcional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            {{-- Botón Cancelar redirige al Admin --}}
                            <a href="{{ route('admin.owners.courts', $owner) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Guardar Cancha</button>
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

    sportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selected = document.querySelectorAll('.sport-checkbox:checked');
            if (selected.length >= maxSports) {
                sportCheckboxes.forEach(cb => {
                    if (!cb.checked) {
                        cb.disabled = true;
                        cb.parentElement.querySelector('div').classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
            } else {
                sportCheckboxes.forEach(cb => {
                    cb.disabled = false;
                    cb.parentElement.querySelector('div').classList.remove('opacity-50', 'cursor-not-allowed');
                });
            }
        });
    });

    // --- SCRIPT DE IMÁGENES ---
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; 
        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-md shadow inline-block mr-2 mb-2 border-2 border-indigo-200';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // --- SCRIPT MAPA ---
    let map;
    let marker;

    function initMap() {
        const defaultCusco = { lat: -13.5167, lng: -71.9788 }; // Plaza de Armas
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