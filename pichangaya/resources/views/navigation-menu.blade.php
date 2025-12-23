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
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- LOGO Y LINKS IZQUIERDA --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
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
                            <a href="{{ route('reservas.user.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('reservas.user.*') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                                {{ __('Mis Reservas') }}
                            </a>
                        </div>

                        {{-- BOTONES ADMIN / OWNER --}}
                        <div class="hidden lg:flex items-center gap-2 ms-4">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
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

            {{-- MENU DERECHA (Notificaciones + Perfil) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    {{-- 🔔 CAMPANITA DE NOTIFICACIONES --}}
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
                    
                        <div x-show="open" @click.away="open = false" style="display: none;"
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
                                                {{-- Iconos dinámicos --}}
                                                @if(($notification->data['icono'] ?? '') == 'currency_exchange') <span class="text-yellow-600 text-xl">💲</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'check_circle') <span class="text-green-600 text-xl">✓</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'cancel') <span class="text-red-600 text-xl">✕</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'hourglass_empty') <span class="text-orange-500 text-xl">⏳</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'mail') <span class="text-blue-500 text-xl">📩</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'lightbulb') <span class="text-yellow-500 text-xl">💡</span>
                                                @elseif(($notification->data['icono'] ?? '') == 'warning') <span class="text-red-600 text-xl">⚠️</span>
                                                @else <span class="text-blue-500 text-xl">ℹ</span> @endif
                                            </div>
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $notification->data['titulo'] ?? 'Notificación' }}</p>
                                                
                                                {{-- 🟢 TEMPORIZADOR MEJORADO PARA EL CLIENTE Y ADMIN --}}
                                                @if(isset($notification->data['expiry_ts']))
                                                    <div class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-between group-timer">
                                                        <div class="flex items-center gap-2">
                                                            <span class="animate-pulse text-xs">⏳</span>
                                                            <span class="text-xs font-bold text-gray-500 dark:text-gray-300">Expira en:</span>
                                                        </div>
                                                        <span class="font-digital text-sm font-bold text-orange-600 dark:text-orange-400 notif-timer tracking-widest" 
                                                              data-expiry="{{ $notification->data['expiry_ts'] }}">
                                                            Calculando...
                                                        </span>
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($notification->data['mensaje'] ?? '', 50) }}</p>
                                                @endif
                                                
                                                <p class="mt-1 text-[10px] text-gray-400 text-right">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-4 text-center text-gray-500 dark:text-gray-400 text-sm">No hay notificaciones.</div>
                                @endforelse
                            </div>
                            <div class="block bg-gray-50 dark:bg-gray-700 text-center px-4 py-2 border-t border-gray-100 dark:border-gray-600 rounded-b-md">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 w-full block">Ver historial completo →</a>
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
                                
                                {{-- 🔴 CONDICIONAL: MODO OSCURO SOLO PARA CLIENTES --}}
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
                                
                                {{-- 🔴 CORRECCIÓN CRÍTICA LOGOUT ESCRITORIO --}}
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    {{-- EL HREF DEBE SER #, NO LA RUTA. SI PONES LA RUTA, LARAVEL INTENTA UN GET Y DA ERROR 419 --}}
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
                        
                        {{-- 🔴 CORRECCIÓN CRÍTICA LOGOUT MÓVIL --}}
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            {{-- IGUAL AQUI: href="#" PARA QUE JS MANEJE EL ENVIO --}}
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
                        // Cambiar estilos al expirar
                        el.classList.remove('text-orange-600', 'dark:text-orange-400');
                        el.classList.add('text-red-600', 'dark:text-red-500'); 
                        el.parentElement.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-200');
                        return;
                    }
                    
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    // Formato Digital 00:00
                    const formattedMin = minutes < 10 ? '0' + minutes : minutes;
                    const formattedSec = seconds < 10 ? '0' + seconds : seconds;
                    
                    el.innerHTML = `${formattedMin}:${formattedSec}`;

                    // Alerta visual si queda menos de 2 minutos
                    if(minutes < 2) {
                        el.classList.add('text-red-500', 'animate-pulse');
                    }
                });
            }

            setInterval(updateMenuTimers, 1000);
            updateMenuTimers(); 
        });
    </script>
</nav>