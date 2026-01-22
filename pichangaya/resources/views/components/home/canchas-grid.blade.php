{{-- resources/views/components/home/canchas-grid.blade.php --}}
@props(['canchas'])

<section id="canchas-grid" class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Encabezado de la sección --}}
    <hgroup class="mb-10 border-l-4 border-green-600 pl-4">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
            Explora Todas las Canchas
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Encuentra el lugar ideal para tu próximo partido</p>
    </hgroup>

    @if($canchas->isEmpty())
        <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 sm:p-16 text-center border border-gray-100 dark:border-gray-700">
            <span class="flex justify-center mb-4 text-gray-300" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <p class="text-lg text-gray-500 dark:text-gray-400 mb-6">No encontramos canchas con esos filtros actualmente.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-colors shadow-lg shadow-green-200 dark:shadow-none">
                Restablecer búsqueda
            </a>
        </section>
    @else
        {{-- Grid Responsivo: 1 col en móvil, 2 en tablet, 3 en desktop --}}
        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 list-none p-0 m-0">
            @foreach($canchas as $cancha)
                <li wire:key="cancha-{{ $cancha->id }}" class="h-full">
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full group">
                        
                        {{-- Área Visual: Imagen + Precio + Favoritos --}}
                        <figure class="relative m-0 aspect-video sm:h-56 w-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas', 'thumb') }}" 
                                    alt="Complejo deportivo {{ $cancha->name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <span class="flex items-center justify-center h-full text-gray-400" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            @endif
                            
                            {{-- Precio flotante --}}
                            <data value="{{ $cancha->price_per_hour }}" class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm text-sm font-black text-green-700 dark:text-green-400 border border-white/20">
                                <small class="text-[10px] uppercase mr-0.5">S/</small>{{ number_format($cancha->price_per_hour, 2) }}
                            </data>

                            {{-- Botón Favoritos (Escondido en móvil, visible en hover o siempre si es táctil) --}}
                            @auth
                                <aside class="absolute top-4 left-4 z-20"
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
                                             .then(res => { if(res.ok) this.isFaved = !this.isFaved; })
                                             .finally(() => this.isLoading = false);
                                         }
                                     }"
                                     @favorite-removed.window="if ($event.detail.id === {{ $cancha->id }}) { isFaved = false; }">
                                    
                                    <button @click.prevent="toggleFav()" :disabled="isLoading" 
                                            class="bg-white/90 dark:bg-gray-900/90 p-2.5 rounded-full shadow-md hover:scale-110 active:scale-90 transition-all focus:outline-none"
                                            :aria-label="isFaved ? 'Quitar de favoritos' : 'Añadir a favoritos'">
                                        <svg x-show="isFaved" x-cloak class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        <svg x-show="!isFaved" x-cloak class="w-5 h-5 text-gray-400 group-hover:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    </button>
                                </aside>
                            @endauth
                        </figure>

                        {{-- Información Detallada --}}
                        <section class="p-5 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 transition-colors">
                                {{ $cancha->name }}
                            </h3>
                            
                            {{-- Ubicación --}}
                            <address class="not-italic mb-4">
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-medium flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $cancha->district->name ?? 'Cusco' }}
                                </span>
                            </address>
                            
                            {{-- Deportes (Categorías) --}}
                            <nav class="flex flex-wrap gap-2 mb-4" aria-label="Deportes disponibles">
                                @foreach($cancha->sports as $sport)
                                    <strong class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg text-[10px] font-black uppercase tracking-wider border border-green-200/50 dark:border-green-800/50">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            {!! config('icons.sports.' . $sport->icon, 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z') !!}
                                        </svg>
                                        {{ $sport->name }}
                                    </strong>
                                @endforeach
                            </nav>

                            {{-- Servicios (Features) --}}
                            <footer class="flex flex-wrap gap-2 mb-5 border-t border-gray-50 dark:border-gray-700/50 pt-4">
                                @foreach ($cancha->services as $service)
                                    <small class="flex items-center gap-1 text-gray-500 dark:text-gray-400 font-semibold text-[10px]">
                                        <svg class="w-3 h-3 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            {!! config('icons.services.' . $service->icon, 'M5 13l4 4L19 7') !!}
                                        </svg>
                                        {{ $service->name }}
                                    </small>
                                @endforeach
                            </footer>
                            
                            {{-- Botón de Acción --}}
                            <nav class="mt-auto">
                                <a href="{{ route('canchas.show', $cancha) }}" 
                                   class="flex items-center justify-center w-full py-3 bg-gray-900 dark:bg-green-600 text-white rounded-xl font-bold hover:bg-green-600 dark:hover:bg-green-500 transition-all shadow-md active:scale-[0.98]">
                                    Ver Disponibilidad
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </nav>
                        </section>
                    </article>
                </li>
            @endforeach
        </ul>
    @endif
</section>