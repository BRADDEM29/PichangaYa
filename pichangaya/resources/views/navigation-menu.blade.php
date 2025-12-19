<nav x-data="{ open: false, darkMode: localStorage.getItem('dark-mode') === 'true' }" class="bg-gray-900/90 backdrop-blur-md border-b border-gray-700 shadow-lg sticky top-0 z-50">
     {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation-menu.blade.php --}}
    @php
        $displayName = '';
        $bellReserva = null;
        
        if (Auth::check()) {
            $user = Auth::user();
            $displayName = $user->name; 
            if ($user->role !== 'admin' && $user->role !== 'owner') {
                $displayName = strtok($user->name, ' '); 
            }

            // Buscamos reserva reciente para sincronizar (útil para el menú móvil)
            $bellReserva = \App\Models\Reserva::where('user_id', auth()->id())
                ->where('created_at', '>', now()->subMinutes(12)) 
                ->with('cancha')
                ->latest()
                ->first();
        }
    @endphp

    <style>
        @keyframes swing {
            0%, 100% { transform: rotate(0deg); }
            20% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(5deg); }
            80% { transform: rotate(-5deg); }
        }
        .animate-swing {
            animation: swing 2s infinite ease-in-out;
            transform-origin: top center;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- SECCIÓN IZQUIERDA --}}
            <div class="flex items-center">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" id="tour-logo" class="flex items-center"> 
                        <x-application-mark class="block h-12 w-auto text-white fill-current transition hover:scale-105" />
                    </a>
                </div>

                {{-- Links Escritorio --}}
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    <div class="relative group">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                            {{ __('Inicio') }}
                        </a>
                    </div>

                    @auth
                        <div class="relative group">
                            <a href="{{ route('reservas.user.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('reservas.user.*') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                                {{ __('Mis Reservas') }}
                            </a>
                        </div>

                        {{-- PANEL DE GESTIÓN --}}
                        <div class="hidden lg:flex items-center gap-2 ms-4">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition shadow-sm">
                                            {{ __('🛡️ Admin') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase">{{ __('Gestión del Sistema') }}</div>
                                        <x-dropdown-link href="{{ route('admin.dashboard') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Ver Resumen') }}</x-dropdown-link>
                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                        
                                        {{-- MEJORA: CONSULTAS ARRIBA DE SUGERENCIAS --}}
                                        <x-dropdown-link href="{{ route('admin.contacts.index') }}" class="flex items-center gap-2 dark:text-white dark:hover:bg-gray-700">
                                            <span class="text-lg">📧</span> {{ __('Consultas') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link href="{{ route('admin.suggestions.received') }}" class="flex items-center gap-2 dark:text-white dark:hover:bg-gray-700">
                                            <span class="text-lg">💬</span> {{ __('Sugerencias') }}
                                        </x-dropdown-link>

                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                        <x-dropdown-link href="{{ route('admin.users.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Usuarios') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.districts.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Distritos') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.sports.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Deportes') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.services.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Servicios') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>

                                <a href="{{ route('admin.owners.index') }}" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none transition shadow-sm">
                                    {{ __('👥 Gestión Dueños') }}
                                </a>
                            @endif

                            @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition shadow-sm">
                                            {{ __('⚽ Proveedor') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase">{{ __('Gestión de Canchas') }}</div>
                                        <x-dropdown-link href="{{ route('owner.canchas.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Mis Canchas') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('owner.reservas.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Gestionar Reservas') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>

            {{-- SECCIÓN DERECHA: PERFIL / LOGIN / NOTIFICACIONES --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    
                    {{-- 🟢 CAMPANA DE NOTIFICACIONES --}}
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <button @click="open = ! open" class="relative p-1 rounded-full text-gray-400 hover:text-white focus:outline-none transition-colors">
                            <span class="sr-only">Notificaciones</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-red-500 animate-pulse"></span>
                            @endif
                        </button>
                    
                        {{-- Dropdown Body --}}
                        <div x-show="open" @click.away="open = false" style="display: none;"
                             x-transition:enter="transition ease-out duration-200"
                             class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-700 dark:text-gray-200 flex justify-between items-center">
                                <span>Notificaciones</span>
                                <span class="text-xs text-gray-400">{{ auth()->user()->unreadNotifications->count() }} nuevas</span>
                            </div>
                    
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 pt-0.5">
                                                {{-- 🟢 ICONOS DINÁMICOS (Incluye Sugerencias y Consultas) --}}
                                                @if(($notification->data['icono'] ?? '') == 'currency_exchange')
                                                    <span class="text-yellow-600 text-xl">💲</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'check_circle')
                                                    <span class="text-green-600 text-xl">✓</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'cancel')
                                                    <span class="text-red-600 text-xl">✕</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'hourglass_empty')
                                                    <span class="text-orange-500 text-xl">⏳</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'mail')
                                                    <span class="text-blue-500 text-xl">📩</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'lightbulb')
                                                    <span class="text-yellow-500 text-xl">💡</span>
                                                @else
                                                    <span class="text-blue-500 text-xl">ℹ</span>
                                                @endif
                                            </div>
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $notification->data['titulo'] ?? 'Notificación' }}</p>
                                                
                                                @if(isset($notification->data['expiry_ts']))
                                                    <p class="text-xs font-bold text-orange-600 notif-timer" data-expiry="{{ $notification->data['expiry_ts'] }}">
                                                       Calculando...
                                                    </p>
                                                @else
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($notification->data['mensaje'] ?? '', 50) }}</p>
                                                @endif

                                                <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-4 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        No tienes notificaciones nuevas.
                                    </div>
                                @endforelse
                            </div>
                    
                            <div class="block bg-gray-50 dark:bg-gray-700 text-center px-4 py-2 border-t border-gray-100 dark:border-gray-600 rounded-b-md">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 w-full block">
                                    Ver historial completo →
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- PERFIL --}}
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button id="tour-perfil" class="flex items-center gap-2 text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-500 transition hover:bg-gray-800 pl-2 pr-1 py-1">
                                    <span class="font-bold text-gray-200 hidden md:inline-block">{{ $displayName }}</span>
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="h-8 w-8 rounded-full object-cover border border-gray-600" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    @endif
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-300 uppercase font-bold">
                                    {{ __('Administrar Cuenta') }}
                                </div>
                                <x-dropdown-link href="{{ route('profile.show') }}" class="dark:text-white dark:hover:bg-gray-700 font-bold">
                                    {{ __('Perfil') }}
                                </x-dropdown-link>
                                
                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                <button @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode); document.documentElement.classList.toggle('dark')" 
                                        type="button" 
                                        class="flex w-full px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out items-center font-bold">
                                    <span x-show="!darkMode" class="flex items-center">🌙 {{ __('Modo Oscuro') }}</span>
                                    <span x-show="darkMode" class="flex items-center">☀️ {{ __('Modo Claro') }}</span>
                                </button>

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="dark:text-white dark:hover:bg-gray-700 font-bold text-red-500">
                                        {{ __('Cerrar Sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="space-x-4 flex items-center">
                        <a href="{{ route('login') }}" class="text-sm text-white font-bold hover:text-green-400 transition">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white text-sm font-bold py-2 px-4 rounded hover:bg-green-700 transition shadow-md border border-green-700">Registrarse</a>
                    </div>
                @endauth
            </div>

            {{-- BOTÓN HAMBURGUESA --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU RESPONSIVE (MÓVIL) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900/95 border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-white hover:text-green-400 hover:bg-gray-800">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode); document.documentElement.classList.toggle('dark')" class="cursor-pointer text-gray-300 hover:text-white font-bold">
                <span x-text="darkMode ? '☀️ Modo Claro' : '🌙 Modo Oscuro'"></span>
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" class="text-white hover:text-green-400 hover:bg-gray-800">
                    {{ __('📅 Mis Reservas') }}
                </x-responsive-nav-link>
                
                @if($bellReserva && $bellReserva->status === 'pending')
                    <div class="block px-4 py-2 text-sm font-bold text-yellow-400 bg-yellow-900/20 animate-pulse">
                        ⏳ Reserva Pendiente de Pago
                    </div>
                @elseif(auth()->user()->unreadNotifications->count() > 0)
                     <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm font-bold text-gray-400 bg-gray-800 hover:bg-gray-700 hover:text-white">
                        🔔 Tienes notificaciones nuevas
                     </a>
                @endif

                @if (Auth::user()->role === 'admin')
                    <div class="border-t border-gray-700 mt-2 pt-2 bg-red-900/20">
                        <div class="block px-4 py-2 text-xs text-red-400 font-bold uppercase">{{ __('🛡️ Panel Admin') }}</div>
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white">{{ __('Ver Resumen') }}</x-responsive-nav-link>
                        
                        {{-- MEJORA MÓVIL: CONSULTAS Y SUGERENCIAS --}}
                        <x-responsive-nav-link href="{{ route('admin.contacts.index') }}" class="text-gray-300 hover:text-white">📧 {{ __('Consultas') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.suggestions.received') }}" class="text-gray-300 hover:text-white">💬 {{ __('Sugerencias') }}</x-responsive-nav-link>

                        <x-responsive-nav-link href="{{ route('admin.owners.index') }}" class="text-purple-400 font-bold">{{ __('👥 Gestión Dueños') }}</x-responsive-nav-link>
                    </div>
                @endif

                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div class="border-t border-gray-700 mt-2 pt-2 bg-green-900/20">
                        <div class="block px-4 py-2 text-xs text-green-400 font-bold uppercase">{{ __('⚽ Panel Proveedor') }}</div>
                        <x-responsive-nav-link href="{{ route('owner.canchas.index') }}" class="text-gray-300 hover:text-white">{{ __('Mis Canchas') }}</x-responsive-nav-link>
                    </div>
                @endif

                <div class="pt-4 pb-1 border-t border-gray-700">
                    <div class="flex items-center px-4">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover border border-gray-600" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </div>
                        @endif
                        <div>
                            <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-gray-300 hover:text-white font-bold">
                            {{ __('Perfil') }}
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-red-400 hover:text-red-300 font-bold">
                                {{ __('Cerrar Sesión') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- SCRIPT PARA EL TEMPORIZADOR --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateTimers = () => {
            const now = Math.floor(Date.now() / 1000);
            
            document.querySelectorAll('.notification-timer').forEach(el => {
                const expiresAt = parseInt(el.getAttribute('data-expires'));
                const diff = expiresAt - now;

                if (diff > 0) {
                    const minutes = Math.floor(diff / 60);
                    const seconds = diff % 60;
                    el.querySelector('span').textContent = 
                        `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                } else {
                    el.textContent = '❌ Expirado';
                    el.classList.remove('text-red-600', 'animate-pulse');
                    el.classList.add('text-gray-400');
                }
            });
        };

        setInterval(updateTimers, 1000);
        updateTimers(); 
    });
</script>