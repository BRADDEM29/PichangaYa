<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\owners\edit-cancha.blade.php --}}
    
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="bg-amber-500 p-2 rounded-lg text-white shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Editando: ') }} <span class="text-amber-600">{{ $cancha->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                {{-- Encabezado del Formulario --}}
                <div class="bg-amber-50 px-6 py-4 border-b border-amber-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-amber-900">Actualizar Información</h3>
                        <p class="text-sm text-amber-700">Modifica los detalles y fotos de tu cancha.</p>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.canchas.update', $cancha) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT')

                        {{-- SECCIÓN 1: DATOS BÁSICOS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nombre de la Cancha</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $cancha->name) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 transition-all py-3" placeholder="Ej: Cancha El Golazo" required>
                                @error('name') <p class="text-red-600 text-sm mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="district_id" class="block text-sm font-bold text-gray-700 mb-1">Distrito</label>
                                <select name="district_id" id="district_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3" required>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id', $cancha->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="price_per_hour" class="block text-sm font-bold text-gray-700 mb-1">Precio por Hora (S/)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm font-bold">S/</span>
                                    </div>
                                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', $cancha->price_per_hour) }}" class="pl-8 w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-3 font-semibold text-gray-700" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-gray-100">

                        {{-- SECCIÓN 2: DEPORTES --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-1">Deportes Disponibles</label>
                            <p class="text-sm text-gray-500 mb-4">Selecciona máximo 2 deportes.</p>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($sports as $sport)
                                    <label class="cursor-pointer group relative">
                                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                            class="peer sr-only sport-checkbox"
                                            @if(in_array($sport->id, old('sports', $cancha->sports->pluck('id')->toArray()))) checked @endif
                                        >
                                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-amber-300 hover:shadow-lg transition-all duration-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 flex flex-col items-center justify-center gap-3 h-full peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">
                                            <div class="p-3 rounded-full bg-gray-100 text-gray-500 group-hover:scale-110 group-hover:bg-amber-100 group-hover:text-amber-600 peer-checked:bg-amber-600 peer-checked:text-white transition-all duration-300">
                                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                                </svg>
                                            </div>
                                            <span class="font-bold text-sm text-center select-none">{{ $sport->name }}</span>
                                            {{-- Checkmark --}}
                                            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-amber-600 transition-opacity">
                                                <svg class="w-5 h-5 bg-white rounded-full" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sports') <p class="text-red-600 text-sm mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        {{-- SECCIÓN 3: SERVICIOS --}}
                        @if(isset($services))
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
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    {!! config('icons.services.' . $service->icon, config('icons.services.default')) !!}
                                                </svg>
                                            </div>
                                            <span class="font-medium text-sm select-none">{{ $service->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <hr class="my-8 border-gray-100">

                        {{-- SECCIÓN 4: HORARIOS Y CONTACTO --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Apertura</label>
                                <input type="time" name="open_time" value="{{ old('open_time', \Carbon\Carbon::parse($cancha->open_time)->format('H:i')) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Hora Cierre</label>
                                <input type="time" name="close_time" value="{{ old('close_time', \Carbon\Carbon::parse($cancha->close_time)->format('H:i')) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Teléfono / WhatsApp</label>
                                <input type="text" name="contact_phone" value="{{ old('contact_phone', $cancha->contact_phone) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5">
                            </div>
                        </div>

                        {{-- SECCIÓN 5: MAPA --}}
                        <div class="mb-8">
                            <label class="block text-lg font-bold text-gray-800 mb-2">Ubicación</label>
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dirección Escrita</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $cancha->address) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5" required>
                            </div>

                            <div class="relative w-full h-96 rounded-2xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-gray-200">
                                <div id="map" class="w-full h-full"></div>
                            </div>
                            <input type="hidden" name="lat" id="lat" value="{{ old('lat', $cancha->lat) }}">
                            <input type="hidden" name="lng" id="lng" value="{{ old('lng', $cancha->lng) }}">
                        </div>

                        {{-- SECCIÓN 6: GESTIÓN DE IMÁGENES --}}
                        <div class="mb-8 space-y-6">
                            {{-- Imágenes Existentes --}}
                            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                                <label class="block text-lg font-bold text-gray-800 mb-1">Galería Actual</label>
                                <p class="text-sm text-gray-500 mb-4">Selecciona "Eliminar" en las fotos que quieras borrar.</p>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                    @forelse($cancha->getMedia('canchas') as $media)
                                        {{-- Wrapper con ID único para manipulación JS --}}
                                        <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-200 image-wrapper transition-all duration-300" id="wrapper-{{ $media->id }}">
                                            <img src="{{ $media->getUrl() }}" class="w-full h-32 object-cover transition-transform duration-500 group-hover:scale-110">
                                            
                                            {{-- Overlay para eliminar --}}
                                            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                                                <label class="cursor-pointer select-none transform hover:scale-105 transition-all">
                                                    {{-- Input Checkbox --}}
                                                    <input type="checkbox" name="delete_images[]" value="{{ $media->id }}" class="hidden delete-checkbox" data-target="wrapper-{{ $media->id }}">
                                                    
                                                    {{-- Botón Visual --}}
                                                    <span class="delete-btn bg-white text-red-600 px-3 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center gap-1 border border-red-100 hover:bg-red-600 hover:text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Eliminar
                                                    </span>
                                                </label>
                                            </div>
                                            {{-- Overlay rojo de confirmación (aparece al checkear) --}}
                                            <div class="absolute inset-0 bg-red-500/80 hidden items-center justify-center text-white font-bold backdrop-blur-sm deletion-overlay pointer-events-none">
                                                <span class="flex flex-col items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Se eliminará
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full py-4 text-center">
                                            <p class="text-sm text-gray-400 italic">No hay imágenes cargadas actualmente.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Subir Nuevas --}}
                            <div class="border-2 border-dashed border-amber-300 rounded-2xl p-8 flex flex-col items-center justify-center bg-amber-50/50 hover:bg-amber-50 transition-colors">
                                <label for="images" class="cursor-pointer text-center group w-full">
                                    <div class="mx-auto bg-amber-100 p-4 rounded-full w-fit mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="block text-base font-bold text-amber-800 mb-1">Agregar Nuevas Fotos</span>
                                    <span class="block text-xs text-amber-600">Click para seleccionar (puedes elegir varias)</span>
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
                                </label>
                                <div id="image-preview" class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4 w-full"></div>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-8">
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 placeholder-gray-400" placeholder="Describe tu cancha (tipo de grass, iluminación, vestuarios, etc.)">{{ old('description', $cancha->description) }}</textarea>
                        </div>

                        {{-- Botones --}}
                        <div class="flex justify-end gap-4 border-t border-gray-100 pt-6">
                            <a href="{{ route('admin.owners.courts', $cancha->user_id) }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-colors">Cancelar</a>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-amber-500/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS MOVIDOS DENTRO DEL LAYOUT (Antes de cerrar x-app-layout) --}}
    <script>
        // --- SCRIPT MAPA ---
        let map;
        let marker;
    
        // Definimos initMap en el objeto window para asegurarnos de que la API de Google lo encuentre
        window.initMap = function() {
            const savedLat = parseFloat("{{ $cancha->lat ?? -13.5167 }}");
            const savedLng = parseFloat("{{ $cancha->lng ?? -71.9788 }}");
            const initialPosition = { lat: savedLat, lng: savedLng };
            
            map = new google.maps.Map(document.getElementById("map"), {
                center: initialPosition,
                zoom: 16,
                styles: [{ "featureType": "poi", "stylers": [{ "visibility": "off" }] }]
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
    
        sportCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkSportsLimit);
        });
    
        document.addEventListener('DOMContentLoaded', checkSportsLimit);
    
        // --- UX PARA ELIMINAR IMÁGENES ---
        const deleteCheckboxes = document.querySelectorAll('.delete-checkbox');
        deleteCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const wrapperId = this.dataset.target;
                const wrapper = document.getElementById(wrapperId);
                const overlay = wrapper.querySelector('.deletion-overlay');
                const btn = wrapper.querySelector('.delete-btn');
    
                if (this.checked) {
                    overlay.classList.remove('hidden');
                    overlay.classList.add('flex');
                    btn.classList.add('bg-red-600', 'text-white');
                    btn.classList.remove('bg-white', 'text-red-600');
                    btn.innerHTML = 'Deshacer';
                } else {
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    btn.classList.remove('bg-red-600', 'text-white');
                    btn.classList.add('bg-white', 'text-red-600');
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg> Eliminar`;
                }
            });
        });
    
        // --- PREVISUALIZACIÓN IMÁGENES ---
        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('image-preview');
            previewContainer.innerHTML = '';
            for (const file of event.target.files) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative rounded-lg overflow-hidden shadow-sm border border-amber-200 aspect-square group';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        div.appendChild(img);
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
    
    {{-- SCRIPT DE GOOGLE MAPS LLAMADO DESPUÉS --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

</x-app-layout>