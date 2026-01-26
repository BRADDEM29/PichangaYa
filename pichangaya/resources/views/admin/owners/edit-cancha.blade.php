<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\owners\edit-cancha.blade.php --}}
    
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-amber-500 p-2 rounded-xl text-white shadow-lg shadow-amber-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                    {{ __('Editar Cancha') }}
                </h2>
            </div>
            <span class="bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-sm font-bold border border-amber-200">
                ID: #{{ $cancha->id }}
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl shadow-gray-200/50 sm:rounded-3xl border border-gray-100 overflow-hidden">
                
                <div class="p-8 md:p-12">
                    <form action="{{ route('admin.canchas.update', $cancha) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                        @csrf 
                        @method('PUT')

                        {{-- SECCIÓN 1: DATOS GENERALES --}}
                        <section>
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                                <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <h3 class="text-xl font-extrabold text-gray-800">Información Principal</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                                <div class="md:col-span-4">
                                    <label for="name" class="block text-sm font-bold text-gray-600 mb-2 ml-1">Nombre Comercial</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $cancha->name) }}" 
                                        class="w-full rounded-2xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 px-5 font-medium" 
                                        placeholder="Ej: Arena Fútbol Club" required>
                                    @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="price_per_hour" class="block text-sm font-bold text-gray-600 mb-2 ml-1">Tarifa por Hora</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-400 font-bold">S/</span>
                                        </div>
                                        <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', $cancha->price_per_hour) }}" 
                                            class="pl-10 w-full rounded-2xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 font-bold text-gray-700" required>
                                    </div>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="district_id" class="block text-sm font-bold text-gray-600 mb-2 ml-1">Distrito / Zona</label>
                                    <select name="district_id" id="district_id" class="w-full rounded-2xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 py-3.5 px-5 transition-all appearance-none cursor-pointer font-medium" required>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ old('district_id', $cancha->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="contact_phone" class="block text-sm font-bold text-gray-600 mb-2 ml-1">WhatsApp de Reservas</label>
                                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $cancha->contact_phone) }}" 
                                        class="w-full rounded-2xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 py-3.5 px-5 transition-all font-medium" placeholder="987 654 321">
                                </div>
                            </div>
                        </section>

                        {{-- SECCIÓN 2: DEPORTES Y SERVICIOS --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            {{-- Deportes --}}
                            <section>
                                <div class="flex items-center gap-2 mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">Deportes (Máx. 2)</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($sports as $sport)
                                        <label class="cursor-pointer group">
                                            <input type="checkbox" name="sports[]" value="{{ $sport->id }}" 
                                                class="peer sr-only sport-checkbox"
                                                @if(in_array($sport->id, old('sports', $cancha->sports->pluck('id')->toArray()))) checked @endif>
                                            <div class="p-3 bg-white border-2 border-gray-100 rounded-2xl transition-all duration-200 peer-checked:border-amber-500 peer-checked:bg-amber-50/50 flex items-center gap-3 peer-disabled:opacity-40">
                                                <div class="p-2 rounded-xl bg-gray-50 text-gray-400 peer-checked:bg-amber-500 peer-checked:text-white transition-colors group-hover:bg-amber-100 group-hover:text-amber-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-sm text-gray-600 peer-checked:text-amber-800 select-none">{{ $sport->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </section>

                            {{-- Servicios --}}
                            @if(isset($services))
                            <section>
                                <div class="flex items-center gap-2 mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">Servicios Adicionales</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($services as $service)
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                                class="peer sr-only"
                                                @if(in_array($service->id, old('services', $cancha->services->pluck('id')->toArray()))) checked @endif>
                                            <div class="px-4 py-3 bg-white border border-gray-200 rounded-2xl hover:border-emerald-300 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 flex items-center gap-3">
                                                <span class="text-gray-400 peer-checked:text-emerald-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        {!! config('icons.services.' . $service->icon, config('icons.services.default')) !!}
                                                    </svg>
                                                </span>
                                                <span class="font-semibold text-xs text-gray-600 peer-checked:text-emerald-700 select-none">{{ $service->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </section>
                            @endif
                        </div>

                        {{-- SECCIÓN 3: HORARIOS --}}
                        <section class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Disponibilidad Horaria</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Hora de Apertura</label>
                                    <input type="time" name="open_time" value="{{ old('open_time', \Carbon\Carbon::parse($cancha->open_time)->format('H:i')) }}" 
                                        class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500/10 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Hora de Cierre</label>
                                    <input type="time" name="close_time" value="{{ old('close_time', \Carbon\Carbon::parse($cancha->close_time)->format('H:i')) }}" 
                                        class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500/10 py-3">
                                </div>
                            </div>
                        </section>

                        {{-- SECCIÓN 4: MAPA Y UBICACIÓN --}}
                        <section>
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                                <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <h3 class="text-xl font-extrabold text-gray-800">Geolocalización</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-2 ml-1">Dirección Referencial</label>
                                    <input type="text" name="address" id="address" value="{{ old('address', $cancha->address) }}" 
                                        class="w-full rounded-2xl border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 py-3.5 px-5 font-medium" placeholder="Ej: Av. Principal 123, frente al parque" required>
                                </div>

                                <div class="relative w-full h-80 rounded-3xl overflow-hidden shadow-inner border border-gray-200">
                                    <div id="map" class="w-full h-full"></div>
                                    <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur px-4 py-2 rounded-xl border border-white shadow-sm pointer-events-none">
                                        <p class="text-[10px] uppercase font-black text-gray-500">Aviso: Arrastra el marcador para ajustar la ubicación exacta</p>
                                    </div>
                                </div>
                                <input type="hidden" name="lat" id="lat" value="{{ old('lat', $cancha->lat) }}">
                                <input type="hidden" name="lng" id="lng" value="{{ old('lng', $cancha->lng) }}">
                            </div>
                        </section>

                        {{-- SECCIÓN 5: MULTIMEDIA --}}
                        <section>
                            <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
                                <span class="bg-purple-100 text-purple-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <h3 class="text-xl font-extrabold text-gray-800">Galería de Fotos</h3>
                            </div>

                            <div class="space-y-8">
                                {{-- Fotos actuales --}}
                                @if($cancha->hasMedia('canchas'))
                                <div class="bg-gray-50 p-6 rounded-3xl border border-gray-200">
                                    <p class="text-xs font-black text-gray-400 uppercase mb-4 tracking-widest">Fotos Publicadas</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                        @foreach($cancha->getMedia('canchas') as $media)
                                            <div class="relative aspect-square rounded-2xl overflow-hidden shadow-sm border border-white image-wrapper transition-all duration-300 group" id="wrapper-{{ $media->id }}">
                                                <img src="{{ $media->getUrl() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                
                                                <label class="absolute inset-0 cursor-pointer flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 backdrop-blur-[2px]">
                                                    <input type="checkbox" name="delete_images[]" value="{{ $media->id }}" class="hidden delete-checkbox" data-target="wrapper-{{ $media->id }}">
                                                    <span class="delete-btn bg-white text-red-600 px-3 py-2 rounded-xl text-xs font-bold shadow-xl flex items-center gap-1.5 hover:bg-red-600 hover:text-white transition-all">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Eliminar
                                                    </span>
                                                </label>

                                                <div class="absolute inset-0 bg-red-600/90 hidden flex-col items-center justify-center text-white font-black text-[10px] uppercase tracking-tighter deletion-overlay pointer-events-none">
                                                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Marcada para borrar
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Subida de nuevas --}}
                                <div class="relative group">
                                    <label for="images" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-amber-200 rounded-3xl bg-amber-50/30 hover:bg-amber-50 hover:border-amber-400 transition-all cursor-pointer">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div class="p-3 bg-amber-100 rounded-2xl text-amber-600 group-hover:scale-110 transition-transform mb-3">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            </div>
                                            <p class="text-sm font-bold text-amber-900 uppercase tracking-wide">Añadir nuevas fotografías</p>
                                            <p class="text-xs text-amber-600 mt-1">Formatos permitidos: JPG, PNG o WEBP</p>
                                        </div>
                                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
                                    </label>
                                    <div id="image-preview" class="mt-4 grid grid-cols-3 sm:grid-cols-6 gap-4"></div>
                                </div>
                            </div>
                        </section>

                        {{-- SECCIÓN 6: DESCRIPCIÓN --}}
                        <section>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2 ml-1">Descripción Detallada</label>
                            <textarea name="description" id="description" rows="4" 
                                class="w-full rounded-2xl border-gray-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 py-4 px-5 transition-all placeholder-gray-400 font-medium" 
                                placeholder="Escribe aquí los beneficios de tu cancha, tipo de grass, iluminación, etc...">{{ old('description', $cancha->description) }}</textarea>
                        </section>

                        {{-- FOOTER ACCIONES --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-4 border-t border-gray-100 pt-10">
                            <a href="{{ route('admin.owners.courts', $cancha->user_id) }}" 
                                class="px-8 py-4 rounded-2xl text-gray-500 font-bold hover:bg-gray-100 transition-colors text-center">
                                Descartar cambios
                            </a>
                            <button type="submit" 
                                class="bg-amber-500 hover:bg-amber-600 text-white font-extrabold py-4 px-10 rounded-2xl shadow-xl shadow-amber-500/20 hover:-translate-y-1 active:translate-y-0 transition-all flex items-center justify-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Actualizar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (Mantenidos igual en lógica, solo integrados) --}}
    <script>
        // --- GOOGLE MAPS ---
        let map, marker;
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
                animation: google.maps.Animation.DROP
            });
    
            marker.addListener("dragend", () => {
                const pos = marker.getPosition();
                document.getElementById('lat').value = pos.lat();
                document.getElementById('lng').value = pos.lng();
            });
        }
    
        // --- MÁXIMO 2 DEPORTES ---
        const sportCheckboxes = document.querySelectorAll('.sport-checkbox');
        const maxSports = 2; 
        function checkSportsLimit() {
            const selected = document.querySelectorAll('.sport-checkbox:checked');
            sportCheckboxes.forEach(cb => {
                if (!cb.checked) {
                    cb.disabled = selected.length >= maxSports;
                }
            });
        }
        sportCheckboxes.forEach(checkbox => checkbox.addEventListener('change', checkSportsLimit));
        document.addEventListener('DOMContentLoaded', checkSportsLimit);
    
        // --- UX ELIMINAR IMÁGENES ---
        document.querySelectorAll('.delete-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const wrapper = document.getElementById(this.dataset.target);
                const overlay = wrapper.querySelector('.deletion-overlay');
                const btn = wrapper.querySelector('.delete-btn');
                if (this.checked) {
                    overlay.classList.remove('hidden');
                    btn.classList.add('bg-gray-800', 'text-white');
                    btn.innerHTML = 'Deshacer';
                } else {
                    overlay.classList.add('hidden');
                    btn.classList.remove('bg-gray-800', 'text-white');
                    btn.innerHTML = 'Eliminar';
                }
            });
        });
    
        // --- PREVISUALIZACIÓN ---
        document.getElementById('images').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            Array.from(event.target.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'aspect-square rounded-xl overflow-hidden border-2 border-amber-400 shadow-md';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>

</x-app-layout>