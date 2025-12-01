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
                    
                    {{-- 🔴 OBLIGATORIO: enctype para subir archivos --}}
                    <form action="{{ route('owner.canchas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf 

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nombre de la Cancha</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Estadio Monumental" required>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Contenedor Responsivo para Distritos y Deportes --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             {{-- Distrito --}}
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

                            {{-- Deporte --}}
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

                        {{-- Precio --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-semibold text-gray-700">Precio por Hora (S/)</label>
                            <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00" required>
                            @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Dirección --}}
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Dirección</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Av. Siempre Viva 123" required>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- CAMPO MÚLTIPLE IMAGEN --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-semibold text-gray-700">Fotos de la Cancha (Mínimo 1, Máximo 10)</label>
                            {{-- 🔴 Atributo "multiple" agregado y name="images[]" para array --}}
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Puedes seleccionar varias imágenes a la vez.</p>
                            @error('images') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @error('images.*') <p class="text-red-500 text-xs mt-1">Error en la subida de una o más imágenes.</p> @enderror
                            
                            {{-- Contenedor para previsualización --}}
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                        </div>
                        {{-- FIN CAMPO MÚLTIPLE IMAGEN --}}

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Descripción (Opcional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('owner.canchas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Cancha
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Script básico para previsualizar múltiples imágenes
    document.getElementById('images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Limpiar previsualizaciones anteriores

        for (const file of event.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.className = 'relative aspect-w-1 aspect-h-1';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview';
                    img.className = 'w-full h-full object-cover rounded-md shadow';
                    
                    imgWrapper.appendChild(img);
                    previewContainer.appendChild(imgWrapper);
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>