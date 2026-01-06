<div>
    @if($favorites->isNotEmpty())
        <div class="py-10 bg-green-50 dark:bg-green-900/10 border-b border-green-100 dark:border-green-900/30 transition-all duration-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- TÍTULO --}}
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Tus Canchas Favoritas
                    </h2>
                </div>
                
                {{-- LISTA DE FAVORITOS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($favorites as $favCancha)
                        <div wire:key="fav-{{ $favCancha->id }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col overflow-hidden relative">
                            
                            {{-- BOTÓN QUITAR (X) --}}
                            <button 
                                wire:click.prevent="removeFavorite({{ $favCancha->id }})"
                                class="absolute top-3 right-3 z-20 bg-white/90 dark:bg-gray-900/90 p-2 rounded-full shadow-md hover:scale-110 active:scale-95 transition-all duration-200 text-gray-400 hover:text-red-500"
                                title="Quitar de favoritos"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <a href="{{ route('canchas.show', $favCancha) }}" class="flex-grow">
                                <div class="h-32 bg-gray-200 dark:bg-gray-700 relative overflow-hidden">
                                    @if($favCancha->getFirstMediaUrl('canchas'))
                                        <img src="{{ $favCancha->getFirstMediaUrl('canchas') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-800 dark:text-white group-hover:text-green-600 transition-colors line-clamp-1">{{ $favCancha->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $favCancha->district->name ?? 'Cusco' }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>