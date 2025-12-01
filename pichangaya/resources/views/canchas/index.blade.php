<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Canchas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-end mb-4">
                <a href="{{ route('canchas.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Agregar Cancha
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($canchas as $cancha)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="h-48 bg-gray-200">
                             @if($cancha->getFirstMediaUrl('canchas'))
                                <img src="{{ $cancha->getFirstMediaUrl('canchas') }}" alt="Cancha" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500">Sin Foto</div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-xl mb-2">{{ $cancha->name }}</h3>
                            <p class="text-gray-700 text-base mb-2">
                                {{ Str::limit($cancha->description, 50) }}
                            </p>
                            <p class="text-blue-600 font-bold">S/ {{ $cancha->price_per_hour }} / hora</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>