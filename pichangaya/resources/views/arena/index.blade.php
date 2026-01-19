{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\arena\index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('PichangaYa Arena') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 1. EL BUSCADOR AHORA ES ESTA LÍNEA (El código HTML viejo se fue al componente MatchFinder) --}}
            @livewire('arena.match-finder')

            {{-- 2. SECCIÓN DE CAMPEONATOS (ESTO SE QUEDA IGUAL, NO LO BORRES) --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-l-4 border-indigo-500 pl-3">
                    Torneos Activos
                </h3>
                
                @if($championships->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center text-gray-500">
                        No hay campeonatos activos en este momento.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($championships as $torneo)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 border border-gray-100 dark:border-gray-700 group">
                                <div class="h-32 bg-indigo-600 relative flex items-center justify-center">
                                    <h4 class="text-3xl font-black text-white uppercase tracking-widest z-10">
                                        {{ $torneo->name }}
                                    </h4>
                                    <div class="absolute inset-0 bg-black opacity-20 group-hover:opacity-10 transition"></div>
                                </div>
                                
                                <div class="p-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                            {{ $torneo->status }}
                                        </span>
                                        <span class="text-gray-500 text-sm flex items-center gap-1">
                                            📅 {{ \Carbon\Carbon::parse($torneo->start_date)->format('d M, Y') }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                                        {{ Str::limit($torneo->description, 80) }}
                                    </p>
                                    
                                    @if($torneo->prize_description)
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded mb-4 flex items-center gap-2">
                                            🏆 <span class="text-sm font-bold text-yellow-700 dark:text-yellow-400">{{ $torneo->prize_description }}</span>
                                        </div>
                                    @endif

                                    <button class="w-full bg-gray-900 dark:bg-gray-700 text-white py-2 rounded-lg font-bold hover:bg-indigo-600 transition duration-300">
                                        VER CUADROS
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
    
    {{-- AQUI IRÁ EL COMPONENTE LIVEWIRE DEL CHAT/AMIGOS FLOTANTE LUEGO --}}
    
</x-app-layout>