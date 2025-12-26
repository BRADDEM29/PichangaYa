<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\owner\canchas\index.blade.php --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Canchas') }}
            </h2>
            <a href="{{ route('owner.canchas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-full shadow-md text-sm transition transform hover:-translate-y-0.5">
                + Nueva Cancha
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <p class="font-bold">¡Excelente!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($canchas as $cancha)
                    {{-- 
                        🟢 TARJETA INTELIGENTE 
                        relative: Para posicionar cosas dentro.
                        group: Para efectos hover en conjunto.
                    --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-full hover:shadow-2xl transition-all duration-300 border border-gray-100 relative group">
                        
                        {{-- 🟢 1. ENLACE MAESTRO (INVISIBLE) --}}
                        {{-- Cubre toda la tarjeta. Al hacer clic, lleva al Historial Financiero --}}
                        <a href="{{ route('owner.canchas.history', $cancha) }}" class="absolute inset-0 z-0" title="Ver Reporte de Ingresos"></a>

                        {{-- Imagen --}}
                        <div class="h-52 w-full bg-gray-200 relative overflow-hidden">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                {{-- Efecto Zoom al pasar el mouse --}}
                                <img src="{{ $cancha->getFirstMediaUrl('canchas', 'thumb') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100 text-4xl">🏟️</div>
                            @endif
                            
                            {{-- Badge de Precio --}}
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm font-bold text-indigo-700 text-sm z-0">
                                S/ {{ number_format($cancha->price_per_hour, 2) }} /h
                            </div>
                        </div>
                        
                        {{-- Info --}}
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-xl font-bold text-gray-900 mb-1 leading-tight group-hover:text-indigo-600 transition-colors">
                                        {{ $cancha->name }}
                                    </h3>
                                </div>
                                <p class="text-sm text-gray-500 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $cancha->district->name }}
                                </p>

                                {{-- Iconos de Deportes --}}
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($cancha->sports->take(3) as $sport)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $sport->icon }} {{ $sport->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Pie de tarjeta: Horario y aviso de click --}}
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                                <span>🕒 {{ $cancha->open_time }} - {{ $cancha->close_time }}</span>
                                <span class="text-indigo-500 font-bold group-hover:underline">Ver Reporte 📊</span>
                            </div>
                        </div>

                        {{-- 🟢 2. BOTONES DE ACCIÓN (SUPERIORES) --}}
                        {{-- Usamos z-10 y relative para que floten ENCIMA del enlace maestro --}}
                        <div class="bg-gray-50 px-6 py-3 flex justify-between items-center relative z-10 border-t border-gray-100">
                            
                            {{-- Enlace 'Ver como cliente' --}}
                            <a href="{{ route('canchas.show', $cancha) }}" target="_blank" class="text-gray-500 hover:text-indigo-600 text-sm font-medium transition flex items-center" title="Ver publicación pública">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver
                            </a>

                            <div class="flex gap-2">
                                {{-- Botón Editar --}}
                                <a href="{{ route('owner.canchas.edit', $cancha) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded text-sm font-bold transition">
                                    Editar
                                </a>

                                {{-- Botón Eliminar --}}
                                <form action="{{ route('owner.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cancha permanentemente? Se perderá todo el historial.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1 rounded text-sm font-bold transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-16 bg-white rounded-2xl shadow-sm text-center border-2 border-dashed border-gray-300">
                        <div class="text-6xl mb-4">🏟️</div>
                        <h3 class="text-xl font-bold text-gray-900">Aún no tienes canchas registradas</h3>
                        <p class="text-gray-500 mt-2">¡Comienza registrando tu primer local deportivo!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>