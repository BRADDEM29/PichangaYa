<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cancha: ') . $cancha->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('owner.canchas.update', $cancha) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT')

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nombre de la Cancha</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $cancha->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Distrito --}}
                        <div class="mb-4">
                            <label for="district_id" class="block text-sm font-semibold text-gray-700">Distrito</label>
                            <select name="district_id" id="district_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $cancha->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DEPORTES (MULTISELECCIÓN) - EDICIÓN --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Deportes Disponibles</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($sports as $sport)
                                    <label class="cursor-pointer relative">
                                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                            class="peer sr-only"
                                            {{-- Lógica Inteligente: Marca si ya lo tenía o si falló la validación anterior --}}
                                            @if(in_array($sport->id, old('sports', $cancha->sports->pluck('id')->toArray()))) checked @endif
                                        >
                                        <div class="p-3 bg-white border rounded-lg hover:bg-gray-50 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 peer-checked:text-indigo-700 transition-all flex items-center justify-center gap-2 text-sm font-medium text-gray-600 shadow-sm">
                                            <span class="text-xl">{{ $sport->icon }}</span> {{ $sport->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sports') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Precio --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-semibold text-gray-700">Precio por Hora (S/)</label>
                            <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', $cancha->price_per_hour) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        {{-- Horarios --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', \Carbon\Carbon::parse($cancha->open_time)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', \Carbon\Carbon::parse($cancha->close_time)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Dirección Escrita</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $cancha->address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        {{-- Teléfono de Contacto (Selector) --}}
                        <div class="mb-4">
                            <label for="contact_phone" class="block text-sm font-semibold text-gray-700">Teléfono de Contacto (WhatsApp)</label>
                            <select name="contact_phone" id="contact_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($phones as $phone)
                                    <option value="{{ $phone['number'] }}" 
                                        {{ (old('contact_phone', $cancha->contact_phone) == $phone['number']) ? 'selected' : '' }}>
                                        {{ $phone['number'] }} - {{ $phone['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- MAPA --}}
                        <div class="mt-6 mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ubicación en el Mapa</label>
                            <div id="map" class="w-full h-96 rounded-lg shadow-md border border-gray-300"></div>
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat', $cancha->lat) }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng', $cancha->lng) }}">
                        </div>

                        {{-- Gestión de Imágenes --}}
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Imágenes Actuales (Marca para eliminar)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @forelse($cancha->getMedia('canchas') as $media)
                                    <div class="relative group border rounded-lg bg-white p-2 shadow-sm">
                                        <img src="{{ $media->getUrl() }}" class="w-full h-24 object-cover rounded">
                                        <div class="mt-2 flex items-center justify-center bg-red-50 py-1 rounded cursor-pointer hover:bg-red-100 transition">
                                            <label class="inline-flex items-center space-x-2 cursor-pointer w-full justify-center">
                                                <input type="checkbox" name="delete_images[]" value="{{ $media->id }}" class="form-checkbox text-red-600 rounded focus:ring-red-500 border-gray-300 h-4 w-4">
                                                <span class="text-xs text-red-600 font-bold">Eliminar</span>
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay imágenes cargadas.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Subir Nuevas Imágenes --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-semibold text-gray-700">Agregar Nuevas Fotos (Opcional)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $cancha->description) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('owner.canchas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Actualizar Cancha</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

<script>
    let map;
    let marker;

    function initMap() {
        const savedLat = {{ $cancha->lat ?? -13.5167 }};
        const savedLng = {{ $cancha->lng ?? -71.9788 }};
        const initialPosition = { lat: savedLat, lng: savedLng };
        
        map = new google.maps.Map(document.getElementById("map"), {
            center: initialPosition,
            zoom: 16,
        });

        marker = new google.maps.Marker({
            position: initialPosition,
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
    }

    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = '';
        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-md shadow inline-block mr-2';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>