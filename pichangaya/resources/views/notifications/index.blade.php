<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Notificaciones') }}
        </h2>
    </x-slot>

    <section class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <article class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Contenedor Principal --}}
            <section class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6 border dark:border-gray-800">
                
                @if($notifications->count() > 0)
                    <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($notifications as $notification)
                            {{-- Lógica de colores según estado de lectura --}}
                            <li class="py-4 flex items-start {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 dark:bg-blue-900/20 -mx-6 px-6' }}">
                                
                                {{-- 1. ICONO --}}
                                <figure class="flex-shrink-0 mr-4 mt-1">
                                    @php
                                        $iconName = $notification->data['icono'] ?? 'info';
                                        $colorClass = $notification->data['color'] ?? 'text-gray-500';
                                    @endphp
                                    
                                    <span class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center shadow-sm">
                                        @if($iconName === 'check_circle' || $iconName === 'check')
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @elseif($iconName === 'currency_exchange')
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @elseif($iconName === 'clock')
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @elseif($iconName === 'fire' || $iconName === 'warning')
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" /></svg>
                                        @elseif($iconName === 'cancel' || $iconName === 'x')
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        @else
                                            <svg class="h-6 w-6 {{ $colorClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @endif
                                    </span>
                                </figure>

                                {{-- 2. CONTENIDO --}}
                                <section class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ $notification->data['titulo'] ?? 'Notificación' }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 break-words">
                                        {{ $notification->data['mensaje'] ?? '' }}
                                    </p>
                                    <time class="mt-2 block text-xs text-gray-400 dark:text-gray-500 font-mono">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </time>
                                </section>

                                {{-- 3. ACCIONES --}}
                                <aside class="ml-4 flex-shrink-0 self-center">
                                    <a href="{{ route('notifications.read', $notification->id) }}" 
                                       class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 text-sm hover:underline transition">
                                        {{ isset($notification->data['url']) ? 'Ver Detalles' : 'Marcar Leído' }}
                                    </a>
                                </aside>
                            </li>
                        @endforeach
                    </ul>

                    {{-- PAGINACIÓN --}}
                    <footer class="mt-6">
                        {{ $notifications->links() }}
                    </footer>
                @else
                    {{-- ESTADO VACÍO --}}
                    <section class="text-center py-12">
                        <figure class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600 mb-4">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </figure>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Todo al día</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No tienes notificaciones pendientes.</p>
                    </section>
                @endif

            </section>
        </article>
    </section>
</x-app-layout>