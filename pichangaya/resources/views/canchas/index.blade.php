<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Canchas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de Éxito --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="flex justify-end mb-6">
                <a href="{{ route('owner.canchas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                    + Agregar Cancha
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($canchas as $cancha)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 flex flex-col h-full">
                        {{-- Imagen --}}
                        <div class="h-56 bg-gray-200 relative">
                             @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="Cancha" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500 text-lg">📷 Sin Foto</div>
                            @endif
                            <div class="absolute top-0 right-0 bg-indigo-600 text-white px-3 py-1 rounded-bl-lg font-bold shadow-md">
                                S/ {{ $cancha->price_per_hour }}
                            </div>
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-xl mb-2 text-gray-800">{{ $cancha->name }}</h3>
                                <p class="text-gray-500 text-sm mb-4 flex items-center">
                                    📍 {{ Str::limit($cancha->address, 30) }}
                                </p>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $cancha->description }}
                                </p>
                            </div>
                            
                            {{-- Botones de Acción --}}
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <a href="{{ route('owner.canchas.edit', $cancha) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                                    ✏️ Editar
                                </a>

                                {{-- Botón ELIMINAR (Agregado) --}}
                                <form action="{{ route('owner.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cancha? Se borrarán todas sus reservas.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold flex items-center gap-1">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($canchas->isEmpty())
                <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                    <p class="text-gray-500 text-lg mb-4">No tienes canchas registradas aún.</p>
                    <a href="{{ route('owner.canchas.create') }}" class="text-indigo-600 font-bold hover:underline">¡Crea la primera aquí!</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>