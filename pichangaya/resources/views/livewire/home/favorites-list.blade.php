<aside class="w-full"> {{-- Contenedor Raíz Semántico --}}
    @if($favorites && $favorites->isNotEmpty())
        <section class="py-10 bg-green-50 dark:bg-green-900/10 border-b border-green-100 dark:border-green-900/30">
            <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <header class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">Tus Canchas Favoritas</h2>
                </header>
                
                <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 list-none p-0 m-0">
                    @foreach($favorites as $favCancha)
                        <li wire:key="fav-item-{{ $favCancha->id }}">
                            <article class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden relative">
                                
                                {{-- Botón eliminar --}}
                                <button 
                                    wire:click="removeFavorite({{ $favCancha->id }})"
                                    class="absolute top-3 right-3 z-20 bg-white/90 dark:bg-gray-900/90 p-2 rounded-full shadow-md text-gray-400 hover:text-red-500 transition-colors"
                                    title="Quitar de favoritos"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <a href="{{ route('canchas.show', $favCancha) }}" class="flex-grow flex flex-col no-underline">
                                    <figure class="h-32 bg-gray-200 dark:bg-gray-700 relative overflow-hidden m-0">
                                        @if($favCancha->getFirstMediaUrl('canchas'))
                                            <img src="{{ $favCancha->getFirstMediaUrl('canchas') }}" alt="{{ $favCancha->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @else
                                            <span class="flex items-center justify-center h-full text-gray-400 text-xs">Sin imagen</span>
                                        @endif
                                    </figure>
                                    
                                    <section class="p-4">
                                        <h4 class="font-bold text-gray-800 dark:text-white group-hover:text-green-600 transition-colors line-clamp-1 m-0">{{ $favCancha->name }}</h4>
                                        <address class="text-xs text-gray-500 mt-1 not-italic">
                                            {{ $favCancha->district->name ?? 'Cusco' }}
                                        </address>
                                    </section>
                                </a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </article>
        </section>
    @endif
</aside>