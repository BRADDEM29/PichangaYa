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
                    
                    {{-- OJO: enctype es obligatorio para subir archivos --}}
                    <form action="{{ route('owner.canchas.update', $cancha) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT') {{-- Método para la actualización --}}

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nombre de la Cancha</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $cancha->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Estadio Monumental" required>
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
                                        <option value="{{ $district->id }}" {{ old('district_id', $cancha->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
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
                                        <option value="{{ $sport->id }}" {{ old('sport_id', $cancha->sport_id) == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                                @error('sport_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Precio --}}
                        <div class="mb-4">
                            <label for="price_per_hour" class="block text-sm font-semibold text-gray-700">Precio por Hora (S/)</label>
                            <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', $cancha->price_per_hour) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00" required>
                            @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Dirección --}}
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-semibold text-gray-700">Dirección</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $cancha->address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Av. Siempre Viva 123" required>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Campo Oculto para IDs de imágenes a eliminar --}}
                        <input type="hidden" name="images_to_delete" id="images-to-delete" value="">

                        {{-- SECCIÓN DE IMÁGENES EXISTENTES --}}
                        @php
                            $existingMedia = $cancha->getMedia('canchas');
                        @endphp
                        <h4 class="text-md font-semibold mt-6 mb-2 text-gray-700">Imágenes Actuales ({{ $existingMedia->count() }} / 10)</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" id="current-images-container">
                            @forelse($existingMedia as $media)
                                <div id="media-{{ $media->id }}" class="relative group">
                                    <img src="{{ $media->getUrl() }}" alt="Cancha Imagen" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                    <button type="button" 
                                            data-media-id="{{ $media->id }}" 
                                            class="delete-media-btn absolute top-1 right-1 bg-red-600 hover:bg-red-800 text-white p-1 rounded-full text-xs opacity-0 group-hover:opacity-100 transition duration-300"
                                            title="Eliminar imagen">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @empty
                                <div class="col-span-4 text-gray-500">No hay imágenes actuales.</div>
                            @endforelse
                        </div>
                        <p class="text-sm text-red-500 mb-4">Click en (X) para marcar una imagen para ser eliminada al guardar. Debe quedar un mínimo de 1 foto.</p>
                        @error('images') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        {{-- FIN SECCIÓN DE IMÁGENES EXISTENTES --}}


                        {{-- CAMPO MÚLTIPLE IMAGEN (NUEVAS) --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-semibold text-gray-700">Subir más Fotos</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Máximo 10 imágenes en total (Existentes + Nuevas).</p>
                            
                            {{-- Contenedor para previsualización de nuevas imágenes --}}
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                        </div>
                        {{-- FIN CAMPO MÚLTIPLE IMAGEN --}}


                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-semibold text-gray-700">Descripción (Opcional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $cancha->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('owner.canchas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Actualizar Cancha
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Script para manejar la lista de imágenes a eliminar (Edit view)
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-media-btn');
        const imagesToDeleteInput = document.getElementById('images-to-delete');
        let idsToDelete = [];

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const mediaId = this.dataset.mediaId;
                const imageWrapper = document.getElementById(`media-${mediaId}`);
                
                if (imageWrapper.classList.contains('opacity-50')) {
                    // Revertir (quitar de la lista de eliminación)
                    imageWrapper.classList.remove('opacity-50', 'grayscale', 'border-red-500');
                    imageWrapper.classList.add('group');
                    this.classList.replace('bg-green-600', 'bg-red-600');
                    
                    idsToDelete = idsToDelete.filter(id => id != mediaId);
                } else {
                    // Marcar para eliminación
                    imageWrapper.classList.add('opacity-50', 'grayscale', 'border-red-500');
                    imageWrapper.classList.remove('group');
                    this.classList.replace('bg-red-600', 'bg-green-600'); // Cambia el color para indicar que está marcado
                    
                    idsToDelete.push(mediaId);
                }
                
                imagesToDeleteInput.value = idsToDelete.join(',');
                console.log('IDs a eliminar:', imagesToDeleteInput.value);
            });
        });

        // Script para previsualizar NUEVAS imágenes (igual que en create)
        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('image-preview');
            previewContainer.innerHTML = ''; 

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
    });
</script>