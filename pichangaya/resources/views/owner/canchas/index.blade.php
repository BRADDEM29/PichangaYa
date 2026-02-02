<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\owner\canchas\index.blade.php --}}
    
    <x-slot name="header">
        <header class="flex justify-between items-center py-2 border-b-2 border-purple-200 dark:border-purple-800 bg-white dark:bg-gray-800 px-4 sm:px-6 lg:px-8">
            <hgroup class="flex items-center gap-3">
                <figure class="bg-indigo-600 p-2.5 rounded-xl text-white shadow-md shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </figure>
                <h1 class="font-black text-2xl text-gray-900 dark:text-white leading-tight tracking-tight">
                    {{ __('Mis Canchas') }}
                </h1>
            </hgroup>
            
            <nav>
                <a href="{{ route('owner.canchas.create') }}" class="group bg-gray-900 hover:bg-indigo-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-gray-400/50 hover:shadow-indigo-500/30 text-sm transition-all transform hover:-translate-y-0.5 flex items-center gap-2 border border-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Cancha
                </a>
            </nav>
        </header>
    </x-slot>

    {{-- MAIN: Mosaico activado --}}
    {{-- 
        1. bg-repeat: Hace que la imagen se repita infinitamente.
        2. bg-fixed: El fondo se queda quieto mientras bajas.
        3. background-size: 400px: Controla el tamaño de cada "cuadradito" del mosaico. 
           (Puedes cambiar 400px por 200px si quieres que se vea más pequeño y se repita más veces).
    --}}
    <main class="py-12 min-h-screen bg-gray-100 dark:bg-gray-900 bg-fixed bg-repeat relative"
          style="background-image: url('{{ asset('images/owner_index.webp') }}'); background-size: 400px;">
        
        {{-- Capa semi-transparente para que el texto resalte sobre el mosaico --}}
        <div class="absolute inset-0 bg-gray-100/90 dark:bg-gray-900/90 backdrop-blur-[1px]"></div>

        {{-- Contenido --}}
        <section class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
            
            @if(session('success'))
                <aside class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-xl shadow-sm flex items-start gap-3" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold text-emerald-900">¡Excelente!</p>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </aside>
            @endif

            {{-- LISTA DE CANCHAS --}}
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" aria-label="Listado de mis canchas">
                @forelse ($canchas as $cancha)
                    {{-- TARJETA --}}
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-300 hover:border-gray-900 dark:border-gray-700 dark:hover:border-gray-400 relative group flex flex-col h-full overflow-hidden">
                        
                        <a href="{{ route('owner.canchas.history', $cancha) }}" class="absolute inset-0 z-0 focus:outline-none focus:ring-4 focus:ring-indigo-500 rounded-2xl" aria-label="Ver reporte de {{ $cancha->name }}"></a>

                        <figure class="h-56 w-full bg-gray-200 dark:bg-gray-700 relative overflow-hidden border-b border-gray-100 dark:border-gray-700">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas', 'thumb') }}" alt="Foto de {{ $cancha->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 bg-gray-50 dark:bg-gray-800 pattern-grid-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Sin imagen</span>
                                </div>
                            @endif
                            
                            <div class="absolute top-4 right-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3 py-1.5 rounded-lg shadow-lg font-black text-gray-900 dark:text-white text-sm z-10 flex items-center gap-1">
                                <span class="text-xs text-gray-500 font-medium mr-0.5">S/</span>
                                {{ number_format($cancha->price_per_hour, 2) }}
                            </div>
                        </figure>
                        
                        <section class="p-6 flex-1 flex flex-col justify-between">
                            <header>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2 leading-tight group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $cancha->name }}
                                    </h3>
                                </div>
                                
                                <address class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-5 flex items-center not-italic">
                                    <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $cancha->district->name }}
                                </address>

                                <nav class="flex flex-wrap gap-2 mb-4">
                                    @foreach($cancha->sports->take(3) as $sport)
                                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 group-hover:bg-indigo-50 group-hover:text-indigo-700 group-hover:border-indigo-100 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                            </svg>
                                            {{ $sport->name }}
                                        </span>
                                    @endforeach
                                </nav>
                            </header>

                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <div class="flex items-center text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 px-2.5 py-1.5 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $cancha->open_time }} - {{ $cancha->close_time }}
                                </div>
                                
                                <span class="flex items-center text-xs font-bold text-indigo-600 dark:text-indigo-400 group-hover:translate-x-1 transition-transform">
                                    Ver Reporte
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </section>

                        <footer class="bg-gray-50 dark:bg-gray-700/30 px-6 py-3.5 flex justify-between items-center relative z-20 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('canchas.show', $cancha) }}" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-bold transition flex items-center gap-1.5 hover:underline decoration-2 underline-offset-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span class="hidden sm:inline">Vista Pública</span>
                            </a>

                            <div class="flex gap-2">
                                <a href="{{ route('owner.canchas.edit', $cancha) }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-700 hover:bg-white dark:hover:bg-gray-600 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-blue-500 p-2 rounded-lg transition shadow-sm" title="Editar Información">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <form action="{{ route('owner.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cancha permanentemente? Se perderá todo el historial.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-red-600 hover:bg-white dark:hover:bg-gray-600 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-red-500 p-2 rounded-lg transition shadow-sm" title="Eliminar Cancha">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </footer>

                    </article>
                @empty
                    {{-- ESTADO VACÍO --}}
                    <article class="col-span-full py-16 bg-white dark:bg-gray-800 rounded-3xl shadow-sm text-center border-2 border-dashed border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center">
                        <figure class="bg-gray-50 dark:bg-gray-700 p-6 rounded-full mb-4 border border-gray-100 dark:border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </figure>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white">Aún no tienes canchas registradas</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm mx-auto font-medium">Comienza registrando tu primer local deportivo para empezar a recibir reservas.</p>
                        <a href="{{ route('owner.canchas.create') }}" class="mt-6 inline-flex items-center px-6 py-3 border-2 border-gray-900 dark:border-gray-100 text-base font-bold rounded-xl text-gray-900 dark:text-white bg-transparent hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-gray-900 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Crear mi primera cancha
                        </a>
                    </article>
                @endforelse
            </section>

        </section>
    </main>
</x-app-layout>