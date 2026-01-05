<nav x-data="{ open: false, darkMode: localStorage.getItem('dark-mode') === 'true' }" class="bg-gray-900/90 backdrop-blur-md border-b border-gray-700 shadow-lg sticky top-0 z-50">
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation-menu.blade.php --}}
    <nav x-data="{ open: false, darkMode: localStorage.getItem('dark-mode') === 'true' }" 
     class="bg-gray-900/90 backdrop-blur-md border-b border-gray-700 shadow-lg sticky top-0 z-50 
            /* 🟢 Efecto de brillo verde en los costados */
            shadow-[inset_60px_0_50px_-50px_rgba(34,197,94,0.3),_inset_-60px_0_50px_-50px_rgba(34,197,94,0.3)]">
    {{-- 🟢 LÓGICA PHP INYECTADA --}}
    @php
        $displayName = '';
        $alertEmail = false;
        $alertPhone = false;
        $bellReserva = null; 
        
        if (Auth::check()) {
            $user = Auth::user();
            $displayName = $user->name; 
            if ($user->role !== 'admin' && $user->role !== 'owner') {
                $displayName = strtok($user->name, ' '); 
            }

            // Lógica de Alertas de Seguridad
            $alertEmail = !$user->hasVerifiedEmail(); // Falta correo
            $alertPhone = is_null($user->phone_verified_at); // Falta celular
            
            // Tu lógica opcional de reserva reciente
            $bellReserva = \App\Models\Reserva::where('user_id', auth()->id())
                ->where('created_at', '>', now()->subMinutes(12)) 
                ->with('cancha')
                ->latest()
                ->first();
        }
    @endphp

    <style>
        /* Animación y Fuentes */
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
        .font-digital {
            font-family: 'Courier New', Courier, monospace;
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }
       /* ⚪ BLANCO ATÓMICO (Máxima Intensidad) */
    .logo-glow {
        filter: 
            drop-shadow(0 0 5px #ffffff)    /* Núcleo: Brillo sólido en los bordes */
            drop-shadow(0 0 15px #ffffff)   /* Medio: Resplandor intermedio */
            drop-shadow(0 0 40px rgba(255, 255, 255, 0.8)); /* Aura: Efecto de luz ambiental */
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        /* Opcional: Hace que el logo mismo sea un poco más brillante */
        brightness: 1.1; 
    }

    .logo-glow:hover {
        filter: 
            drop-shadow(0 0 8px #ffffff) 
            drop-shadow(0 0 25px #ffffff) 
            drop-shadow(0 0 60px #ffffff); /* Expansión máxima al pasar el mouse */
        transform: scale(1.1) translateY(-2px); /* Pequeño salto hacia arriba */
    }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- LOGO Y LINKS IZQUIERDA --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" id="tour-logo" class="flex items-center"> 
                        {{-- 🟢 Aplicamos la clase logo-glow aquí --}}
                        <img src="{{ asset('images/Pichanga-_1_.webp') }}" 
                             alt="PichangaYa Logo" 
                             class="block h-16 w-auto object-contain transition hover:scale-105 logo-glow">
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
                            <a href="{{ route('reservas.user.index') }}" id="tour-mis-reservas" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('reservas.user.*') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                                {{ __('Mis Reservas') }}
                            </a>
                        </div>

                        {{-- BOTONES ADMIN / OWNER (Iconos SVG) --}}
                        <div class="hidden lg:flex items-center gap-2 ms-4">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                    <x-slot name="trigger">
                                        <button id="tour-admin" type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition shadow-sm gap-2">
                                            {{-- Icono Shield --}}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            {{ __('Admin') }}
                                            <svg class="ms-1 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
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

                                <a href="{{ route('admin.owners.index') }}" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none transition shadow-sm gap-2">
                                    {{-- Icono Users --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ __('Gestión Dueños') }}
                                </a>
                            @endif

                            @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                    <x-slot name="trigger">
                                        <button id="tour-proveedor" type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition shadow-sm gap-2">
                                            {{-- Icono Ball --}}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ __('Proveedor') }}
                                            <svg class="ms-1 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
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
                        <button @click="open = ! open" id="tour-notificaciones" class="relative p-1 rounded-full text-gray-400 hover:text-white focus:outline-none transition-colors">
                            <span class="sr-only">Notificaciones</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            
                            {{-- PUNTO ROJO DE ALERTA --}}
                            @if(auth()->user()->unreadNotifications->count() > 0 || $alertEmail || $alertPhone)
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
                                
                                {{-- 🟢 ALERTAS FIJAS DE VERIFICACIÓN (SVG Icons) --}}
                                @if($alertEmail)
                                    <a href="{{ route('profile.show') }}#verification-section" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 border-l-4 border-red-500 transition border-b border-gray-100 dark:border-gray-700">
                                        <div class="font-bold text-red-600 dark:text-red-400 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Verifica tu Correo
                                        </div>
                                        <p class="text-xs mt-1 opacity-80">Es necesario para asegurar tus reservas.</p>
                                    </a>
                                @endif

                                @if($alertPhone)
                                    <a href="{{ route('profile.show') }}#verification-section" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-orange-900/20 border-l-4 border-orange-500 transition border-b border-gray-100 dark:border-gray-700">
                                        <div class="font-bold text-orange-600 dark:text-orange-400 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            Verifica tu Celular
                                        </div>
                                        <p class="text-xs mt-1 opacity-80">Valida tu número para confirmar partidos.</p>
                                    </a>
                                @endif

                                {{-- LOOP DE NOTIFICACIONES --}}
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                                        
                                        {{-- CASO 1: TEMPORIZADOR ACTIVO --}}
                                        @if(isset($notification->data['expiry_ts']))
                                            <div class="p-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 border-l-4 border-green-500">
                                                <div class="flex flex-col gap-2">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <div class="flex items-center gap-2">
                                                            <div class="bg-green-100 dark:bg-green-900 p-1.5 rounded-md shadow-sm">
                                                                {{-- Icono Money/Cash --}}
                                                                <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
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
                                                        <span class="flex items-center gap-1 text-[10px] bg-gray-200 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded border border-gray-300 dark:border-gray-600" title="Esta notificación no se puede borrar hasta completar la acción">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                            Activa
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

                                        {{-- CASO 2: CANCELACIÓN --}}
                                        @elseif(($notification->data['icono'] ?? '') == 'cancel')
                                            <div class="p-4 bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 text-red-500 pt-1">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
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

                                        {{-- CASO 3: DISEÑO ESTÁNDAR (Reemplazo de Emojis por SVG) --}}
                                        @else
                                            <div class="px-4 py-3 flex items-start">
                                                <div class="flex-shrink-0 pt-0.5">
                                                    @if(($notification->data['icono'] ?? '') == 'currency_exchange')
                                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @elseif(($notification->data['icono'] ?? '') == 'check_circle')
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @elseif(($notification->data['icono'] ?? '') == 'mail')
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    @elseif(($notification->data['icono'] ?? '') == 'lightbulb')
                                                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                                    @elseif(($notification->data['icono'] ?? '') == 'warning')
                                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
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
                                    @if(!$alertEmail && !$alertPhone)
                                        <div class="px-4 py-12 text-center flex flex-col items-center justify-center opacity-60">
                                            <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full mb-3 text-gray-400 dark:text-gray-300">
                                                {{-- Icono Sleep/Zzz --}}
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">Todo está tranquilo</p>
                                            <p class="text-gray-400 text-xs">No hay nuevas notificaciones</p>
                                        </div>
                                    @endif
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
                                            {{-- Icono Luna --}}
                                            <span x-show="!darkMode" class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                                {{ __('Modo Oscuro') }}
                                            </span>
                                            {{-- Icono Sol --}}
                                            <span x-show="darkMode" class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                {{ __('Modo Claro') }}
                                            </span>
                                    </button>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
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
                    <x-responsive-nav-link @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode); document.documentElement.classList.toggle('dark')" class="cursor-pointer text-gray-300 hover:text-white font-bold flex items-center gap-2">
                        <span x-show="!darkMode" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            Modo Oscuro
                        </span>
                        <span x-show="darkMode" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Modo Claro
                        </span>
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

    {{-- SCRIPT DE TEMPORIZADOR INTACTO --}}
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
                        el.classList.remove('text-green-400', 'animate-pulse', 'drop-shadow-[0_0_8px_rgba(74,222,128,0.6)]');
                        el.classList.add('text-red-600', 'drop-shadow-[0_0_8px_rgba(220,38,38,0.6)]'); 
                        return;
                    }
                    
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    const formattedMin = minutes < 10 ? '0' + minutes : minutes;
                    const formattedSec = seconds < 10 ? '0' + seconds : seconds;
                    
                    el.innerHTML = `${formattedMin}:${formattedSec}`;

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