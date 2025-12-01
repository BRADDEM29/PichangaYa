<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Canchas') }}
            </h2>
            <a href="{{ route('owner.canchas.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                + Nueva Cancha
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($canchas as $cancha)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-full hover:shadow-xl transition-shadow duration-300">
                        
                        {{-- Imagen --}}
                        <div class="h-48 w-full bg-gray-200 relative">
                            @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="{{ $cancha->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">Sin imagen</div>
                            @endif
                            
                            <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded shadow text-sm font-bold">
                                S/ {{ $cancha->price_per_hour }}
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $cancha->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $cancha->address }}</p>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                    {{ $cancha->sport->name ?? 'Deporte' }}
                                </span>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                                <a href="{{ route('owner.canchas.edit', $cancha) }}" class="text-indigo-600 font-medium hover:text-indigo-900">Editar</a>
                                {{-- Formulario para eliminar si lo deseas agregar --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 bg-white rounded-lg shadow">
                        <p class="text-gray-500">No tienes canchas registradas.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>