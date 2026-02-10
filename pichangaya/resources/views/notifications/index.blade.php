<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\notifications\index.blade.php --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
                {{-- Icono Campana --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mis Notificaciones') }}
            </h2>
        </div>
    </x-slot>

    <main class="py-12 bg-gray-50 dark:bg-[#0f172a] min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <section class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                
                {{-- CABECERA INTERNA --}}
                <header class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20 flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                        Historial Reciente
                    </span>
                    @if($notifications->count() > 0)
                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-md">
                            {{ $notifications->total() }} Total
                        </span>
                    @endif
                </header>

                <div class="p-0">
                    @if($notifications->count() > 0)
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($notifications as $notification)
                                {{-- ESTADO DE LECTURA: Fondo azulado si no leída --}}
                                <li class="group relative transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 
                                    {{ $notification->read_at ? 'bg-white dark:bg-gray-800 opacity-80' : 'bg-indigo-50/40 dark:bg-indigo-900/10' }}">
                                    
                                    <article class="p-5 flex items-start gap-4">
                                        
                                        {{-- 1. ICONO DINÁMICO --}}
                                        <figure class="flex-shrink-0 mt-1">
                                            @php
                                                $iconName = $notification->data['icono'] ?? 'info';
                                                $baseColor = $notification->read_at ? 'text-gray-400 dark:text-gray-500' : ($notification->data['color'] ?? 'text-indigo-500');
                                                $bgIcon = $notification->read_at ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-200 dark:ring-gray-600';
                                            @endphp
                                            
                                            <div class="h-10 w-10 rounded-full {{ $bgIcon }} flex items-center justify-center">
                                                @if($iconName === 'check_circle' || $iconName === 'check')
                                                    <svg class="h-5 w-5 {{ $baseColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                @elseif($iconName === 'currency_exchange')
                                                    <svg class="h-5 w-5 {{ $baseColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                @elseif($iconName === 'clock')
                                                    <svg class="h-5 w-5 {{ $baseColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                @elseif($iconName === 'fire' || $iconName === 'warning')
                                                    <svg class="h-5 w-5 {{ $baseColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3l-6.928-12c-.77-1.333-2.694-1.333-3.464 0l-6.928 12c-.77 1.333.192 3 1.732 3z" /></svg>
                                                @elseif($iconName === 'cancel' || $iconName === 'x')
                                                    <svg class="h-5 w-5 {{ $baseColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                @else
                                                    <svg class="h-5 w-5 {{ $baseColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                @endif
                                            </div>
                                        </figure>

                                        {{-- 2. CONTENIDO --}}
                                        <div class="flex-1 min-w-0">
                                            <header class="flex justify-between items-start mb-1">
                                                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                    {{ $notification->data['titulo'] ?? 'Nueva Notificación' }}
                                                </h3>
                                                
                                                {{-- Indicador de No Leído --}}
                                                @if(!$notification->read_at)
                                                    <span class="flex h-2 w-2 relative">
                                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                                    </span>
                                                @endif
                                            </header>
                                            
                                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed break-words">
                                                {{ $notification->data['mensaje'] ?? '' }}
                                            </p>
                                            
                                            <footer class="mt-3 flex items-center justify-between">
                                                <time class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 font-medium">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </time>

                                                {{-- 3. ACCIÓN PRINCIPAL --}}
                                                <a href="{{ route('notifications.read', $notification->id) }}" 
                                                   class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900/50">
                                                    {{ isset($notification->data['url']) ? 'Ver Detalles' : 'Marcar como leída' }}
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                </a>
                                            </footer>
                                        </div>
                                    </article>
                                </li>
                            @endforeach
                        </ul>

                        {{-- PAGINACIÓN --}}
                        @if($notifications->hasPages())
                            <footer class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
                                {{ $notifications->links() }}
                            </footer>
                        @endif

                    @else
                        {{-- ESTADO VACÍO --}}
                        <article class="text-center py-20 px-6">
                            <figure class="mx-auto h-20 w-20 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </figure>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Estás al día</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                                No tienes nuevas notificaciones pendientes en este momento.
                            </p>
                        </article>
                    @endif
                </div>

            </section>
        </div>
    </main>
    <footer class="relative z-10">
        <x-footer />
    </footer>
</x-app-layout>