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

            // Lógica para la campanita de reservas recientes
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
        /* Fuente digital para el reloj */
        .font-digital {
            font-family: 'Courier New', Courier, monospace;
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- LOGO Y LINKS IZQUIERDA --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    {{-- ID: tour-logo mantenido --}}
                    <a href="{{ route('home') }}" id="tour-logo" class="flex items-center"> 
                        <x-application-mark class="block h-12 w-auto text-white fill-current transition hover:scale-105" />
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    <div class="relative group">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                            {{ __('Inicio') }}
                        </a>
                    </div>

                    @auth
                        <div class="relative group">
                            {{-- ✅ CAMBIO IMPORTANTE: ID actualizado a 'tour-mis-reservas' para coincidir con app.js --}}
                            <a href="{{ route('reservas.user.index') }}" id="tour-mis-reservas" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('reservas.user.*') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                                {{ __('Mis Reservas') }}
                            </a>
                        </div>

                        {{-- BOTONES ADMIN / OWNER --}}
                        <div class="hidden lg:flex items-center gap-2 ms-4">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                    <x-slot name="trigger">
                                        <button id="tour-admin" type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition shadow-sm">
                                            {{ __('🛡️ Admin') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase">{{ __('Gestión del Sistema') }}</div>
                                        <x-dropdown-link href="{{ route('admin.dashboard') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Ver Resumen') }}</x-dropdown-link>
                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                        
                                        <x-dropdown-link href="{{ route('admin.contacts.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Consultas') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.suggestions.received') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Sugerencias') }}</x-dropdown-link>

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
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                    <x-slot name="trigger">
                                        <button id="tour-proveedor" type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition shadow-sm">
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

            {{-- MENU DERECHA (Notificaciones + Perfil) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    {{-- 🔔 CAMPANITA DE NOTIFICACIONES --}}
                    <div class="ml-3 relative" x-data="{ open: false }">
                        {{-- ID: tour-notificaciones mantenido --}}
                        <button @click="open = ! open" id="tour-notificaciones" class="relative p-1 rounded-full text-gray-400 hover:text-white focus:outline-none transition-colors">
                            <span class="sr-only">Notificaciones</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-gray-900 bg-red-500 animate-pulse"></span>
                            @endif
                        </button>
                    
                        <div x-show="open" @click.away="open = false" style="display: none;"
                             class="origin-top-right absolute right-0 mt-2 w-80 sm:w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            
                            {{-- CABECERA DE NOTIFICACIONES --}}
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-t-md">
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-gray-800 dark:text-gray-100">ALERTAS</span>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full border border-red-200 font-bold">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- 🟢 BOTÓN MARCAR TODO COMO LEÍDO --}}
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition underline decoration-dotted">
                                            Marcar todo leído
                                        </button>
                                    </form>
                                @endif
                            </div>
                    
                            <div class="max-h-[25rem] overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                                        
                                        {{-- 🟢 CASO 1: TEMPORIZADOR ACTIVO --}}
                                        @if(isset($notification->data['expiry_ts']))
                                            <div class="p-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 border-l-4 border-green-500">
                                                <div class="flex flex-col gap-2">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <div class="flex items-center gap-2">
                                                            <div class="bg-green-100 dark:bg-green-900 p-1.5 rounded-md shadow-sm">
                                                                <span class="text-lg">💸</span>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-black text-gray-800 dark:text-gray-100 leading-none">
                                                                    {{ $notification->data['titulo'] ?? 'Reserva Pendiente' }}
                                                                </p>
                                                                <p class="text-[10px] text-green-600 dark:text-green-400 font-bold uppercase tracking-wider mt-0.5">
                                                                    Requiere Atención
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <span class="text-[10px] bg-gray-200 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded border border-gray-300 dark:border-gray-600" title="Esta notificación no se puede borrar hasta completar la acción">
                                                            🔒 Activa
                                                        </span>
                                                    </div>

                                                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed pl-1">
                                                        {{ Str::limit($notification->data['mensaje'] ?? 'Gestione esta solicitud antes de que expire el tiempo.', 80) }}
                                                    </p>

                                                    <div class="mt-2 bg-black rounded-lg p-2.5 border border-gray-700 shadow-inner flex items-center justify-between relative overflow-hidden group-timer">
                                                        <div class="absolute bottom-0 left-0 h-0.5 bg-green-500 animate-[pulse_2s_infinite] w-full opacity-70"></div>
                                                        <div class="flex flex-col z-10 pl-1">
                                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tiempo Restante</span>
                                                            <span class="text-[9px] text-gray-500">Auto-cancelación</span>
                                                        </div>
                                                        <div class="z-10 flex items-center gap-2">
                                                            <span class="animate-pulse text-green-500 text-xs">●</span>
                                                            <span class="font-digital text-xl font-bold text-green-400 notif-timer tracking-widest drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]" 
                                                                  data-expiry="{{ $notification->data['expiry_ts'] }}">
                                                                --:--
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        {{-- 🟢 CASO 2: CANCELACIÓN --}}
                                        @elseif(($notification->data['icono'] ?? '') == 'cancel')
                                            <div class="p-4 bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 text-2xl pt-1">🚫</div>
                                                    <div class="w-full">
                                                        <p class="text-sm font-black text-red-700 dark:text-red-400 leading-none">
                                                            ¡Reserva Cancelada!
                                                        </p>
                                                        <p class="text-xs text-red-600 dark:text-red-300 mt-1 font-medium leading-tight">
                                                            {{ $notification->data['mensaje'] ?? 'La reserva ha sido anulada.' }}
                                                        </p>
                                                        <p class="mt-2 text-[10px] text-red-400 uppercase font-bold text-right">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        {{-- CASO 3: DISEÑO ESTÁNDAR --}}
                                        @else
                                            <div class="px-4 py-3 flex items-start">
                                                <div class="flex-shrink-0 pt-0.5">
                                                    @if(($notification->data['icono'] ?? '') == 'currency_exchange') <span class="text-yellow-600 text-xl">💲</span>
                                                    @elseif(($notification->data['icono'] ?? '') == 'check_circle') <span class="text-green-600 text-xl">✓</span>
                                                    @elseif(($notification->data['icono'] ?? '') == 'mail') <span class="text-blue-500 text-xl">📩</span>
                                                    @elseif(($notification->data['icono'] ?? '') == 'lightbulb') <span class="text-yellow-500 text-xl">💡</span>
                                                    @elseif(($notification->data['icono'] ?? '') == 'warning') <span class="text-red-600 text-xl">⚠️</span>
                                                    @else <span class="text-blue-500 text-xl">ℹ</span> @endif
                                                </div>
                                                <div class="ml-3 w-0 flex-1">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $notification->data['titulo'] ?? 'Notificación' }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($notification->data['mensaje'] ?? '', 60) }}</p>
                                                    <p class="mt-1 text-[10px] text-gray-400 text-right">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-12 text-center flex flex-col items-center justify-center opacity-60">
                                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full mb-3">
                                            <span class="text-2xl">💤</span>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">Todo está tranquilo</p>
                                        <p class="text-gray-400 text-xs">No hay nuevas notificaciones</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="block bg-gray-50 dark:bg-gray-700 text-center px-4 py-2 border-t border-gray-100 dark:border-gray-600 rounded-b-md">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 w-full block uppercase tracking-wide">Ver historial completo</a>
                            </div>
                        </div>
                    </div>

                    {{-- PERFIL --}}
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
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
                                <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-300 uppercase font-bold">{{ __('Administrar Cuenta') }}</div>
                                <x-dropdown-link href="{{ route('profile.show') }}" class="dark:text-white dark:hover:bg-gray-700 font-bold">{{ __('Perfil') }}</x-dropdown-link>
                                
                                @if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'owner')
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <button @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode); document.documentElement.classList.toggle('dark')" 
                                            type="button" 
                                            class="flex w-full px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out items-center font-bold">
                                            <span x-show="!darkMode" class="flex items-center">🌙 {{ __('Modo Oscuro') }}</span>
                                            <span x-show="darkMode" class="flex items-center">☀️ {{ __('Modo Claro') }}</span>
                                    </button>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="#" @click.prevent="$root.submit();" class="dark:text-white dark:hover:bg-gray-700 font-bold text-red-500">
                                        {{ __('Cerrar Sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    {{-- LOGIN / REGISTER --}}
                    <div class="space-x-4 flex items-center">
                        <a href="{{ route('login') }}" class="text-sm text-white font-bold hover:text-green-400 transition">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white text-sm font-bold py-2 px-4 rounded hover:bg-green-700 transition shadow-md border border-green-700">Registrarse</a>
                    </div>
                @endauth
            </div>

            {{-- HAMBURGER --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MÓVIL --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900/95 border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-white hover:text-green-400 hover:bg-gray-800">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
            
            @auth
                @if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'owner')
                    <x-responsive-nav-link @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode); document.documentElement.classList.toggle('dark')" class="cursor-pointer text-gray-300 hover:text-white font-bold">
                        <span x-text="darkMode ? '☀️ Modo Claro' : '🌙 Modo Oscuro'"></span>
                    </x-responsive-nav-link>
                @endif
            @endauth

            @auth
                <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" class="text-white hover:text-green-400 hover:bg-gray-800">
                    {{ __('📅 Mis Reservas') }}
                </x-responsive-nav-link>
                
                @if (Auth::user()->role === 'admin')
                    <div class="border-t border-gray-700 mt-2 pt-2 bg-red-900/20">
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white">{{ __('Ver Resumen') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.contacts.index') }}" class="text-gray-300 hover:text-white">{{ __('Consultas') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.suggestions.received') }}" class="text-gray-300 hover:text-white">{{ __('Sugerencias') }}</x-responsive-nav-link>
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
                            <x-responsive-nav-link href="#" @click.prevent="$root.submit();" class="text-red-400 hover:text-red-300 font-bold">
                                {{ __('Cerrar Sesión') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    {{-- 🟢 SCRIPT DE TEMPORIZADOR MEJORADO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timerElements = document.querySelectorAll('.notif-timer');
            
            if(timerElements.length === 0) return;

            function updateMenuTimers() {
                const now = new Date().getTime();
                
                timerElements.forEach(el => {
                    const expiry = parseInt(el.getAttribute('data-expiry'));
                    const distance = expiry - now;
                    
                    if (distance < 0) {
                        el.innerHTML = "EXPIRADO";
                        // Estilos de expiración
                        el.classList.remove('text-green-400', 'animate-pulse', 'drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]');
                        el.classList.add('text-red-600', 'drop-shadow-[0_0_8px_rgba(220,38,38,0.6)]'); 
                        return;
                    }
                    
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    const formattedMin = minutes < 10 ? '0' + minutes : minutes;
                    const formattedSec = seconds < 10 ? '0' + seconds : seconds;
                    
                    el.innerHTML = `${formattedMin}:${formattedSec}`;

                    // Alerta: menos de 2 minutos
                    if(minutes < 2) {
                        el.classList.remove('text-green-400', 'drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]');
                        el.classList.add('text-red-500', 'animate-pulse', 'drop-shadow-[0_0_8px_rgba(239,68,68,0.6)]');
                    }
                });
            }

            setInterval(updateMenuTimers, 1000);
            updateMenuTimers(); 
        });
    </script>
</nav>