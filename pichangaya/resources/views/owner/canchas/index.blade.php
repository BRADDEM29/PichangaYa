<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\owner\canchas\index.blade.php --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-indigo-600 p-2 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Mis Canchas') }}
                </h2>
            </div>
            
            <a href="{{ route('owner.canchas.create') }}" class="group bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-500/30 text-sm transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Cancha
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold">¡Excelente!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($canchas as $cancha)
                    {{-- TARJETA INTELIGENTE --}}
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 relative group flex flex-col h-full">
                        
                        {{-- 1. ENLACE MAESTRO (INVISIBLE) --}}
                        <a href="{{ route('owner.canchas.history', $cancha) }}" class="absolute inset-0 z-0 rounded-2xl focus:ring-2 focus:ring-indigo-500" title="Ver Reporte de Ingresos"></a>

                        {{-- Imagen --}}
                        <div class="h-52 w-full bg-gray-100 relative overflow-hidden rounded-t-2xl">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas', 'thumb') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-300 bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs font-medium">Sin imagen</span>
                                </div>
                            @endif
                            
                            {{-- Badge de Precio --}}
                            <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg shadow-sm font-bold text-indigo-700 text-sm z-0 border border-gray-100 flex items-center gap-1">
                                <span class="text-xs text-gray-500 font-normal">S/</span>
                                {{ number_format($cancha->price_per_hour, 2) }}
                            </div>
                        </div>
                        
                        {{-- Info --}}
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1 leading-tight group-hover:text-indigo-600 transition-colors">
                                        {{ $cancha->name }}
                                    </h3>
                                </div>
                                
                                {{-- Distrito --}}
                                <p class="text-sm text-gray-500 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $cancha->district->name }}
                                </p>

                                {{-- Iconos de Deportes --}}
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($cancha->sports->take(3) as $sport)
                                        <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{-- Renderizamos el icono desde la config o usamos uno por defecto --}}
                                            <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! config('icons.sports.' . $sport->icon, config('icons.sports.default')) !!}
                                            </svg>
                                            {{ $sport->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Pie de tarjeta: Horario y Reporte --}}
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $cancha->open_time }} - {{ $cancha->close_time }}
                                </div>
                                
                                <span class="flex items-center text-xs font-bold text-indigo-600 group-hover:underline decoration-indigo-300 underline-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                    </svg>
                                    Ver Reporte
                                </span>
                            </div>
                        </div>

                        {{-- 2. BOTONES DE ACCIÓN (SUPERIORES) --}}
                        <div class="bg-gray-50 px-6 py-3 flex justify-between items-center relative z-10 border-t border-gray-100 rounded-b-2xl">
                            
                            {{-- Enlace 'Ver como cliente' --}}
                            <a href="{{ route('canchas.show', $cancha) }}" target="_blank" class="text-gray-500 hover:text-indigo-600 text-sm font-medium transition flex items-center gap-1.5" title="Ver publicación pública">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span class="hidden sm:inline">Vista Pública</span>
                            </a>

                            <div class="flex gap-2">
                                {{-- Botón Editar --}}
                                <a href="{{ route('owner.canchas.edit', $cancha) }}" class="text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Editar Información">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                {{-- Botón Eliminar --}}
                                <form action="{{ route('owner.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cancha permanentemente? Se perderá todo el historial.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Eliminar Cancha">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    {{-- ESTADO VACÍO (EMPTY STATE) --}}
                    <div class="col-span-full py-16 bg-white rounded-3xl shadow-sm text-center border-2 border-dashed border-gray-300 flex flex-col items-center justify-center">
                        <div class="bg-gray-50 p-6 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Aún no tienes canchas registradas</h3>
                        <p class="text-gray-500 mt-2 max-w-sm mx-auto">Comienza registrando tu primer local deportivo para empezar a recibir reservas.</p>
                        <a href="{{ route('owner.canchas.create') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Crear mi primera cancha
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>