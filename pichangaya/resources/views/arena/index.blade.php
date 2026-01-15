<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('PichangaYa Arena') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 1. EL BUSCADOR "TIPO DOTA 2" --}}
            <div class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6 text-white relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-900 to-purple-900 opacity-50"></div>
                
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Buscador de Partida
                    </h3>

                    <div class="flex flex-col md:flex-row gap-6 mb-6">
                        <div class="flex bg-gray-800 rounded-lg p-1">
                            <button class="px-4 py-2 rounded-md bg-green-600 text-white font-bold shadow transition">
                                Casual (Pichanga)
                            </button>
                            <button class="px-4 py-2 rounded-md text-gray-400 hover:text-white transition">
                                Competitivo (Ranked)
                            </button>
                        </div>

                        <div class="flex gap-2">
                            <select class="bg-gray-800 border-gray-700 rounded-md text-white focus:ring-green-500">
                                <option>Fútbol 7</option>
                                <option>Fútbol 6</option>
                                <option>Vóley</option>
                            </select>
                            <select class="bg-gray-800 border-gray-700 rounded-md text-white focus:ring-green-500">
                                <option>Todo Cusco</option>
                                <option>Wanchaq</option>
                                <option>San Sebastián</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-center mt-8">
                        <button class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white text-xl font-black py-4 px-12 rounded-full shadow-lg transform hover:scale-105 transition duration-200 flex items-center gap-3 border-2 border-green-300">
                            BUSCAR PARTIDA
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-20"></span>
                        </button>
                    </div>
                    
                    <p class="text-center text-gray-400 text-sm mt-3">
                        Tiempo estimado de espera: <span class="text-green-400">48h Max</span>
                    </p>
                </div>
            </div>

            {{-- 2. SECCIÓN DE CAMPEONATOS (Cards) --}}
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