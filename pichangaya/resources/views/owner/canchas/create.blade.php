<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\owner\canchas\create.blade.php --}}
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
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 items-stretch">
                                @foreach($sports as $sport)
                                    @php
                                        $sportIcon = match(true) {
                                            str_contains(strtolower($sport->name), 'fútbol 5') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                                            str_contains(strtolower($sport->name), 'fútbol 7') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                                            str_contains(strtolower($sport->name), 'fútbol 11') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                                            str_contains(strtolower($sport->name), 'vóley') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>',
                                            str_contains(strtolower($sport->name), 'básquet') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>',
                                            str_contains(strtolower($sport->name), 'tenis') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
                                            str_contains(strtolower($sport->name), 'futsal') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                            default => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5v.665M19 10.5l1 1.5-1.5 1.5a2.5 2.5 0 01-3.5 0L14 12" /></svg>'
                                        };
                                    @endphp
                                    <label class="cursor-pointer group h-full">
                                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                            class="peer sr-only sport-checkbox"
                                            @if(is_array(old('sports')) && in_array($sport->id, old('sports'))) checked @endif
                                        >
                                        {{-- Contenedor con altura fija mínima y flex-col para uniformidad --}}
                                        <div class="h-full min-h-[100px] p-4 bg-white border rounded-xl flex flex-col items-center justify-center gap-2 text-sm font-bold text-gray-600 shadow-sm transition-all peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 peer-disabled:opacity-40 peer-disabled:grayscale text-center whitespace-normal">
                                            <span class="shrink-0 text-gray-400 group-hover:scale-110 transition-transform peer-checked:text-indigo-600">
                                                {!! $sportIcon !!}
                                            </span> 
                                            <span class="leading-tight">{{ $sport->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sports') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            <p id="sports-error" class="text-red-500 text-xs mt-1 font-bold hidden">Solo puedes seleccionar hasta 2 deportes.</p>
                        </div>

                        {{-- 4. SERVICIOS (SIN LÍMITE) --}}
                        <div class="mb-6 border-t border-gray-100 pt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Servicios Adicionales</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 items-stretch">
                                @foreach($services as $service)
                                    @php
                                        $serviceIcon = match(true) {
                                            str_contains(strtolower($service->name), 'wi-fi') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10.5 10.5 0 0114.142 0M2.121 8.879C7.62 3.38 16.38 3.38 21.879 8.879" /></svg>',
                                            str_contains(strtolower($service->name), 'estacionamiento') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>',
                                            str_contains(strtolower($service->name), 'ducha') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
                                            str_contains(strtolower($service->name), 'iluminación') => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>',
                                            default => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
                                        };
                                    @endphp
                                    <label class="cursor-pointer group h-full">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                            class="peer sr-only"
                                            @if(is_array(old('services')) && in_array($service->id, old('services'))) checked @endif
                                        >
                                        <div class="h-full min-h-[100px] p-4 bg-white border rounded-xl flex flex-col items-center justify-center gap-2 text-sm font-bold text-gray-600 shadow-sm transition-all peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 text-center whitespace-normal">
                                            <span class="shrink-0 text-indigo-500 group-hover:scale-110 transition-transform peer-checked:text-green-600">
                                                {!! $serviceIcon !!}
                                            </span> 
                                            <span class="leading-tight">{{ $service->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
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
                            <select name="contact_phone" id="contact_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($phones as $phone)
                                    <option value="{{ $phone['number'] }}" {{ old('contact_phone') == $phone['number'] ? 'selected' : '' }}>
                                        {{ $phone['number'] }} - {{ $phone['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contact_phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- 9. Mapa --}}
                        <div class="mt-6 mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ubicación en el Mapa</label>
                            <div id="map" class="w-full h-96 rounded-lg shadow-md border border-gray-300"></div>
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">
                            @error('lat') <p class="text-red-500 text-xs mt-1 font-bold">Selecciona una ubicación en el mapa.</p> @enderror
                        </div>

                        {{-- 10. Imágenes --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-semibold text-gray-700">Fotos de la Cancha</label>
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
                            <a href="{{ route('owner.canchas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
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
    // LÓGICA DE MÁXIMO 2 DEPORTES
    const sportCheckboxes = document.querySelectorAll('.sport-checkbox');
    const maxSports = 2;

    sportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selected = document.querySelectorAll('.sport-checkbox:checked');
            if (selected.length >= maxSports) {
                sportCheckboxes.forEach(cb => {
                    if (!cb.checked) cb.disabled = true;
                });
            } else {
                sportCheckboxes.forEach(cb => cb.disabled = false);
            }
        });
    });

    // VISTA PREVIA DE IMÁGENES
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

    // MAPA
    let map, marker;
    function initMap() {
        const defaultCusco = { lat: -13.5167, lng: -71.9788 };
        const oldLat = "{{ old('lat') }}";
        const oldLng = "{{ old('lng') }}";
        let startPos = (oldLat && oldLng) ? { lat: parseFloat(oldLat), lng: parseFloat(oldLng) } : defaultCusco;

        map = new google.maps.Map(document.getElementById("map"), { center: startPos, zoom: 15 });
        marker = new google.maps.Marker({ position: startPos, map: map, draggable: true, animation: google.maps.Animation.DROP });

        marker.addListener("dragend", () => {
            const position = marker.getPosition();
            document.getElementById('lat').value = position.lat();
            document.getElementById('lng').value = position.lng();
        });

        if (!oldLat && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const userPos = { lat: position.coords.latitude, lng: position.coords.longitude };
                map.setCenter(userPos); marker.setPosition(userPos);
                document.getElementById('lat').value = userPos.lat;
                document.getElementById('lng').value = userPos.lng;
            });
        }
    }
</script>