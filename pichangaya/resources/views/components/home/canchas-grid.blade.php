{{-- resources/views/components/home/canchas-grid.blade.php --}}
@props(['canchas'])

<section id="canchas-grid" class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 border-l-4 border-green-600 pl-4">
        Explora Todas las Canchas
    </h2>

    @if($canchas->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400">No encontramos canchas con esos filtros.</p>
            <a href="{{ route('home') }}" class="text-green-600 hover:underline mt-4 inline-block font-bold">Ver todas</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($canchas as $cancha)
                <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full group">
                    
                    {{-- IMAGEN + BOTON FAVORITOS + PRECIO --}}
                    <div class="relative h-56 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        @if($cancha->getFirstMediaUrl('canchas'))
                            <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" 
                                 alt="{{ $cancha->name }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Precio --}}
                        <div class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-lg shadow-sm text-sm font-bold text-green-700 flex items-center gap-1">
                            <span class="text-xs">S/</span>
                            {{ number_format($cancha->price_per_hour, 2) }}
                        </div>

                        {{-- BOTÓN DE FAVORITOS (ALPINE MODIFICADO) --}}
                        @auth
                            <div class="absolute top-4 left-4 z-20"
                                 x-data="{ 
                                     isFaved: {{ $cancha->isFavoritedBy(auth()->user()) ? 'true' : 'false' }},
                                     isLoading: false,
                                     
                                     toggleFav() {
                                         this.isLoading = true;
                                         fetch('{{ route('canchas.favorite', $cancha) }}', {
                                             method: 'POST',
                                             headers: {
                                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                 'Content-Type': 'application/json',
                                                 'Accept': 'application/json'
                                             }
                                         })
                                         .then(response => {
                                             if (response.ok) {
                                                 this.isFaved = !this.isFaved;
                                                 if (typeof Livewire !== 'undefined') {
                                                     Livewire.dispatch('refresh-favorites');
                                                 }
                                             }
                                         })
                                         .finally(() => {
                                             this.isLoading = false;
                                         });
                                     }
                                 }"
                                 {{-- AQUÍ ESTÁ LA NUEVA MAGIA: ESCUCHA AL EVENTO GLOBAL --}}
                                 @favorite-removed.window="if ($event.detail.id === {{ $cancha->id }}) { isFaved = false; }"
                                 >
                                
                                <button @click="toggleFav()" 
                                        :disabled="isLoading"
                                        class="bg-white/90 dark:bg-gray-900/90 p-2 rounded-full shadow-md hover:scale-110 active:scale-95 transition-all duration-200 group/btn focus:outline-none focus:ring-2 focus:ring-green-400">
                                    
                                    {{-- Corazón Lleno (Rojo) --}}
                                    <svg x-show="isFaved" x-cloak class="w-6 h-6 text-red-500 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    
                                    {{-- Corazón Vacío (Outline) --}}
                                    <svg x-show="!isFaved" x-cloak class="w-6 h-6 text-gray-400 group-hover/btn:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>
                        @endauth
                    </div>

                    <div class="p-5 flex-grow flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $cancha->name }}</h3>
                        
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                {{ $cancha->district->name ?? 'Cusco' }}
                            </span>
                            
                            @foreach($cancha->sports as $sport)
                                <span class="bg-green-600 text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                    <svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                    </svg>
                                    {{ $sport->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach ($cancha->services as $service)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/50 gap-1">
                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! config('icons.services.' . $service->icon, config('icons.services.default')) !!}
                                    </svg>
                                    {{ $service->name }}
                                </span>
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ $cancha->description }}
                        </p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('canchas.show', $cancha) }}" 
                               class="block w-full py-2.5 bg-green-600 text-white text-center rounded-lg font-bold hover:bg-green-700 transition">
                                Ver Disponibilidad
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>