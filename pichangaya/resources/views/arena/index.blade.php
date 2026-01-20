<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-800 dark:text-gray-200">
                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06l6.22-6.22H3a.75.75 0 0 1 0-1.5h16.19l-6.22-6.22a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight uppercase tracking-wide">
                {{ __('PichangaYa Arena') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. BUSCADOR --}}
            @livewire('arena.match-finder')

            {{-- 2. SECCIÓN DE CAMPEONATOS --}}
            <div>
                <div class="flex items-center gap-3 mb-6 border-l-4 border-indigo-600 pl-4">
                    <h3 class="text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">
                        Torneos Activos
                    </h3>
                </div>
                
                @if($championships->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-4 text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.25 2.25 0 11-4.5 0v3.375m7.5 0v2.25m-7.5 0v2.25" />
                        </svg>
                        <p class="text-gray-500 font-medium">No hay campeonatos activos en este momento.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($championships as $torneo)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group flex flex-col h-full transform hover:-translate-y-1">
                                
                                {{-- Header de la Tarjeta --}}
                                <div class="h-36 bg-gray-900 relative flex items-center justify-center overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-gray-900 opacity-90"></div>
                                    
                                    {{-- Patrón de fondo --}}
                                    <svg class="absolute inset-0 w-full h-full opacity-10" width="100%" height="100%">
                                        <pattern id="pattern-{{$torneo->id}}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                            <circle cx="2" cy="2" r="1" class="text-white" fill="currentColor" />
                                        </pattern>
                                        <rect width="100%" height="100%" fill="url(#pattern-{{$torneo->id}})" />
                                    </svg>

                                    <h4 class="text-2xl font-black text-white uppercase tracking-widest z-10 text-center px-6 leading-tight drop-shadow-lg">
                                        {{ $torneo->name }}
                                    </h4>
                                </div>
                                
                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-center mb-5">
                                            {{-- Estado --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-500/10 text-green-600 border border-green-500/20">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $torneo->status }}
                                            </span>

                                            {{-- Fecha --}}
                                            <span class="text-gray-400 text-xs font-bold flex items-center gap-1.5 uppercase tracking-wide">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3">
                                                    <path d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5 .75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5 .75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5 .75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5 .75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5 .75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5 .75.75 0 0 0 0 1.5Z" />
                                                    <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($torneo->start_date)->format('d M, Y') }}
                                            </span>
                                        </div>
                                        
                                        @if($torneo->prize_description)
                                            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg mb-5 flex items-center gap-3 border border-gray-100 dark:border-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                                    {{ $torneo->prize_description }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('arena.show', $torneo->id) }}" class="w-full bg-gray-900 dark:bg-black text-white py-3 rounded-lg font-black text-xs uppercase tracking-widest hover:bg-indigo-600 dark:hover:bg-indigo-600 transition-colors duration-300 flex items-center justify-center gap-2 group-hover:gap-4 shadow-lg">
                                        Ver Bracket
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 transition-all">
                                            <path fill-rule="evenodd" d="M16.72 7.72a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H3a.75.75 0 0 1 0-1.5h16.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>