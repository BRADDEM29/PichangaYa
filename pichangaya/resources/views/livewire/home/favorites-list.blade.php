{{-- resources/views/livewire/favorites-section.blade.php --}}
@if($favorites->isNotEmpty())
    {{-- Contenedor principal de la sección --}}
    <section class="py-10 bg-green-50 dark:bg-green-900/10 border-b border-green-100 dark:border-green-900/30">
        
        {{-- Contenedor de centrado y ancho máximo --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Encabezado de grupo (Título + Icono) --}}
            <hgroup class="flex items-center gap-2 mb-6">
                {{-- Icono SVG: Corazón animado --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Tus Canchas Favoritas
                </h2>
            </hgroup>
            
            {{-- Lista de tarjetas --}}
            <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 list-none p-0 m-0">
                @foreach($favorites as $favCancha)
                    <li wire:key="fav-{{ $favCancha->id }}">
                        <article class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden relative">
                            
                            {{-- Botón de acción con SVG --}}
                            <button 
                                wire:click.prevent="removeFavorite({{ $favCancha->id }})"
                                class="absolute top-3 right-3 z-20 bg-white/90 dark:bg-gray-900/90 p-2 rounded-full shadow-md text-gray-400 hover:text-red-500 transition-colors"
                                aria-label="Quitar de favoritos"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <a href="{{ route('canchas.show', $favCancha) }}" class="flex-grow flex flex-col no-underline">
                                <figure class="m-0 flex flex-col h-full">
                                    {{-- Contenedor de Imagen semántico --}}
                                    <picture class="block h-32 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                                        @if($favCancha->getFirstMediaUrl('canchas'))
                                            <img src="{{ $favCancha->getFirstMediaUrl('canchas') }}" 
                                                 alt="Cancha {{ $favCancha->name }}"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @else
                                            {{-- Placeholder semántico con SVG --}}
                                            <span class="flex items-center justify-center h-full text-gray-400" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                        @endif
                                    </picture>
                                    
                                    {{-- Información de la tarjeta --}}
                                    <figcaption class="p-4">
                                        <h4 class="font-bold text-gray-800 dark:text-white group-hover:text-green-600 transition-colors line-clamp-1">
                                            {{ $favCancha->name }}
                                        </h4>
                                        
                                        <address class="text-xs text-gray-500 mt-1 flex items-center gap-1 not-italic">
                                            {{-- Icono de ubicación SVG --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $favCancha->district->name ?? 'Cusco' }}
                                        </address>
                                    </figcaption>
                                </figure>
                            </a>
                        </article>
                    </li>
                @endforeach
            </ul>
        </section>
    </section>
@endif