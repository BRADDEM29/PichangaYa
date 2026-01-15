<div> {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation-menu.blade.php --}}
@php
    // 1. DEFINIMOS LA VARIABLE ANTES DE USARLA EN EL HTML
    $forceLightMode = request()->routeIs([
        'login', 'register', 'password.*', 'admin.*', 'owner.*'
    ]) || request()->is(['admin/*', 'panel-dueno/*']);
@endphp

<nav x-data="{ 
        open: false, 
        {{-- INICIO LÓGICA MODO OSCURO CONDICIONAL --}}
        darkMode: {{ $forceLightMode ? 'false' : "localStorage.getItem('dark-mode') === 'true'" }},
        toggleTheme() {
            // Si está forzado (Admin/Dueño), la función no hace NADA
            if ({{ $forceLightMode ? 'true' : 'false' }}) return;

            this.darkMode = !this.darkMode;
            localStorage.setItem('dark-mode', this.darkMode);
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        {{-- FIN LÓGICA MODO OSCURO --}}
    }" 
    {{-- Quitamos el watch si está forzado para ahorrar recursos y evitar conflictos --}}
    @if(!$forceLightMode)
        x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); 
                if(darkMode) document.documentElement.classList.add('dark');"
    @endif
    class="fixed w-full top-0 z-50 transition-all duration-300
           bg-[#0f172a]/90 backdrop-blur-xl border-b border-white/10
           shadow-[0_4px_30px_rgba(0,0,0,0.5)]">

    @php
        // 2. VARIABLES DE USUARIO
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
            $isStaff = in_array($user->role, ['admin', 'owner']);

            $alertEmail = !$isStaff && !$user->hasVerifiedEmail();
            $alertPhone = !$isStaff && is_null($user->phone_verified_at);
            
            $bellReserva = \App\Models\Reserva::where('user_id', auth()->id())
                ->where('created_at', '>', now()->subMinutes(12)) 
                ->with('cancha')
                ->latest()
                ->first();
        }
    @endphp

    <style>
        /* --- ESTILOS VISUALES MEJORADOS "SUPER GLOW" --- */
        
        /* SOLUCIÓN "SUPER NOVA" PARA LOGO OSCURO */
        .logo-super-glow {
            filter: brightness(1.3) contrast(1.1)
                    drop-shadow(0 0 1px rgba(255,255,255,1))
                    drop-shadow(0 0 4px rgba(255,255,255,0.8))
                    drop-shadow(0 0 12px rgba(255,255,255,0.5));
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        }

        .logo-super-glow:hover {
            transform: scale(1.1);
            filter: brightness(1.5) contrast(1.2)
                    drop-shadow(0 0 2px rgba(255,255,255,1))
                    drop-shadow(0 0 15px rgba(255,255,255,0.9))
                    drop-shadow(0 0 30px rgba(74, 222, 128, 0.7))
                    drop-shadow(0 0 60px rgba(255,255,255,0.4));
        }

        /* Enlaces de Navegación con efecto Neon */
        .nav-neon-link {
            position: relative;
            font-weight: 700;
            letter-spacing: 0.025em;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Espacio entre icono y texto */
        }
        .nav-neon-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 50%;
            background-color: #4ade80; /* Green 400 */
            box-shadow: 0 0 10px #4ade80;
            transition: width 0.3s ease, left 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-neon-link:hover::after,
        .nav-neon-link.active::after {
            width: 100%;
        }

        /* Botones 3D (Pills) */
        .btn-pill-3d {
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        .btn-pill-3d:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.4), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        .btn-pill-3d:active {
            transform: translateY(1px);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.3);
        }

        /* Animación Swing para iconos */
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
        <div class="flex justify-between h-20"> 
            
            {{-- SECCIÓN IZQUIERDA: LOGO Y NAVEGACIÓN --}}
            <div class="flex items-center gap-8"> {{-- 🟢 AUMENTADO GAP DE 6 A 8 --}}
                {{-- LOGO MEJORADO CON SUPER GLOW --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" id="tour-logo" class="flex items-center group"> 
                        <img src="{{ asset('images/Pichanga-_1_.webp') }}" 
                             alt="PichangaYa Logo" 
                             class="block h-14 w-auto object-contain logo-super-glow">
                    </a>
                </div>

                {{-- LINKS PRINCIPALES (ESCRITORIO) --}}
                <div class="hidden sm:flex items-center gap-8"> {{-- 🟢 AUMENTADO GAP DE 4 A 8 PARA QUE NO SE VEA APRETADO --}}

                    <a href="{{ route('home') }}" 
                       class="nav-neon-link px-1 py-1 text-sm {{ request()->routeIs('home') ? 'text-green-400 active' : 'text-gray-300 hover:text-white' }}">
                        {{-- Icono Home --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                        {{ __('INICIO') }}
                    </a>

                    {{-- 🟢 2. CAMPEONATOS (AHORA PRIMERO) --}}
                    <a href="{{ route('arena.index') }}"
                       class="nav-neon-link px-1 py-1 text-sm {{ request()->routeIs('arena.*') ? 'text-green-400 active' : 'text-gray-300 hover:text-white' }}">
                        {{-- 🟢 ICONO SVG PERSONALIZADO (Copa Rellena) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trophy w-4 h-4">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2.17a3 3 0 1 1 0 5.659v.171a6.002 6.002 0 0 1 -5 5.917v2.083h3a1 1 0 0 1 .117 1.993l-.117 .007h-8a1 1 0 0 1 -.117 -1.993l.117 -.007h3v-2.083a6.002 6.002 0 0 1 -4.996 -5.692l-.004 -.225v-.171a3 3 0 0 1 -3.996 -2.653l-.003 -.176l.005 -.176a3 3 0 0 1 3.995 -2.654l-.001 -2.17a1 1 0 0 1 1 -1h10zm-12 5a1 1 0 1 0 0 2a1 1 0 0 0 0 -2m14 0a1 1 0 1 0 0 2a1 1 0 0 0 0 -2" />
                        </svg>
                        {{ __('CAMPEONATOS') }}
                    </a>

                    {{-- 🟢 3. MAPA (MOVIDO A LA DERECHA DE CAMPEONATOS) --}}
                    <a href="{{ route('mapa.index') }}"
                       class="nav-neon-link px-1 py-1 text-sm {{ request()->routeIs('mapa.index') ? 'text-green-400 active' : 'text-gray-300 hover:text-white' }}">
                        {{-- Icono Mapa --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                        </svg>
                        {{ __('MAPA') }}
                    </a>

                    @auth
                        <a href="{{ route('reservas.user.index') }}" id="tour-mis-reservas" 
                           class="nav-neon-link px-1 py-1 text-sm {{ request()->routeIs('reservas.user.*') ? 'text-green-400 active' : 'text-gray-300 hover:text-white' }}">
                           {{-- Icono Ticket --}}
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                            </svg>
                            {{ __('MIS RESERVAS') }}
                        </a>

                        {{-- SEPARADOR VERTICAL --}}
                        <div class="h-6 w-px bg-white/10 mx-2"></div>

                        {{-- 🔴 BOTONES DE ADMINISTRACIÓN Y DUEÑOS --}}
                        <div class="flex items-center gap-3">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-700 shadow-xl">
                                    <x-slot name="trigger">
                                        {{-- Botón Admin Rojo 3D --}}
                                        <button id="tour-admin" type="button" class="btn-pill-3d flex items-center gap-2 px-4 py-1.5 bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-full border border-red-500/30">
                                            {{-- Icono Escudo SVG --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                            </svg>
                                            Admin
                                        </button>
                                    </x-slot>
                                    
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">{{ __('Gestión del Sistema') }}</div>
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

                                {{-- Botón Gestión Dueños Púrpura 3D --}}
                                <a href="{{ route('admin.owners.index') }}" class="btn-pill-3d flex items-center gap-2 px-4 py-1.5 bg-gradient-to-br from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white text-xs font-bold uppercase tracking-wider rounded-full border border-purple-500/30">
                                    {{-- Icono Maletín SVG --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                    </svg>
                                    Gestión Dueños
                                </a>
                            @endif

                            @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-700 shadow-xl">
                                    <x-slot name="trigger">
                                        {{-- Botón Proveedor Verde 3D --}}
                                        <button id="tour-proveedor" type="button" class="btn-pill-3d flex items-center gap-2 px-4 py-1.5 bg-gradient-to-br from-green-600 to-green-700 hover:from-green-500 hover:to-green-600 text-white text-xs font-bold uppercase tracking-wider rounded-full border border-green-500/30">
                                            {{-- Icono Tienda SVG --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            Proveedor
                                        </button>
                                    </x-slot>
                                    
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">{{ __('Gestión de Canchas') }}</div>
                                        <x-dropdown-link href="{{ route('owner.canchas.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Mis Canchas') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('owner.reservas.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Gestionar Reservas') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>

            {{-- SECCIÓN DERECHA: NOTIFICACIONES Y PERFIL --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    {{-- Contenedor de iconos con estilo vidrio --}}
                    <div class="flex items-center gap-3 bg-white/5 px-4 py-2 rounded-full border border-white/5 backdrop-blur-sm">
                        @include('navigation.notifications')

                        {{-- PERFIL --}}
                        <div class="relative ms-1">
                            <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-600 shadow-2xl">
                                <x-slot name="trigger">
                                    <button id="tour-perfil" class="flex items-center gap-3 text-sm focus:outline-none transition group">
                                        <div class="text-right hidden md:block leading-tight">
                                            <div class="font-bold text-gray-200 group-hover:text-white text-xs uppercase tracking-wide">{{ $displayName }}</div>
                                            {{-- 🔴 SE ELIMINÓ EL ESTADO "CONECTADO" AQUÍ --}}
                                        </div>
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <div class="relative">
                                                <img class="h-9 w-9 rounded-full object-cover border-2 border-gray-600 group-hover:border-green-400 transition shadow-[0_0_10px_rgba(0,0,0,0.5)]" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                                <div class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-500 rounded-full border-2 border-gray-900 animate-pulse"></div>
                                            </div>
                                        @endif
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500 uppercase font-bold bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">{{ __('Administrar Cuenta') }}</div>
                                    <x-dropdown-link href="{{ route('profile.show') }}" class="dark:text-white dark:hover:bg-gray-700 font-bold">{{ __('Perfil') }}</x-dropdown-link>
                                    
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                    {{-- BOTÓN MODO OSCURO --}}
                                    <button @click="toggleTheme()" 
                                            type="button" 
                                            class="flex w-full px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition items-center font-bold">
                                        <span x-show="!darkMode" class="flex items-center gap-2">
                                            {{-- Icono Luna --}}
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                            {{ __('Modo Oscuro') }}
                                        </span>
                                        <span x-show="darkMode" class="flex items-center gap-2">
                                            {{-- Icono Sol --}}
                                            <svg class="w-4 h-4 text-yellow-400 drop-shadow-[0_0_5px_rgba(250,204,21,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            {{ __('Modo Claro') }}
                                        </span>
                                    </button>

                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 font-bold flex items-center gap-2">
                                            {{-- Icono Salir --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                            </svg>
                                            {{ __('Cerrar Sesión') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                @else
                    {{-- LOGIN / REGISTER BUTTONS --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-white transition uppercase tracking-wider hover:underline decoration-green-400 decoration-2 underline-offset-4">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-pill-3d relative inline-flex items-center justify-center px-5 py-2 overflow-hidden font-bold text-white transition duration-300 ease-out border border-green-500 rounded-full shadow-md group bg-gradient-to-br from-green-600 to-teal-600">
                            <span class="relative flex items-center gap-1 text-sm">
                                Registrarse 
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </span>
                        </a>
                    </div>
                @endauth
            </div>

            {{-- HAMBURGER BUTTON (MÓVIL) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none transition border border-transparent focus:border-green-500/50">
                    <svg class="size-7 drop-shadow-md" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MÓVIL (CORREGIDO Y RESPONSIVE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#0f172a] border-t border-white/10 shadow-2xl">
        <div class="pt-2 pb-3 space-y-1 px-4">
            
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-gray-200 hover:bg-white/10 rounded-lg border-l-4 border-transparent hover:border-green-400 transition-all flex items-center gap-3 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                {{ __('Inicio') }}
            </x-responsive-nav-link>

            {{-- CAMPEONATOS MÓVIL (MOVIDO ANTES DEL MAPA) --}}
            <x-responsive-nav-link href="{{ route('arena.index') }}" :active="request()->routeIs('arena.index')" class="text-gray-200 hover:bg-white/10 rounded-lg border-l-4 border-transparent hover:border-green-400 transition-all flex items-center gap-3 py-3">
                {{-- ICONO SVG PERSONALIZADO PARA MÓVIL --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trophy w-5 h-5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2.17a3 3 0 1 1 0 5.659v.171a6.002 6.002 0 0 1 -5 5.917v2.083h3a1 1 0 0 1 .117 1.993l-.117 .007h-8a1 1 0 0 1 -.117 -1.993l.117 -.007h3v-2.083a6.002 6.002 0 0 1 -4.996 -5.692l-.004 -.225v-.171a3 3 0 0 1 -3.996 -2.653l-.003 -.176l.005 -.176a3 3 0 0 1 3.995 -2.654l-.001 -2.17a1 1 0 0 1 1 -1h10zm-12 5a1 1 0 1 0 0 2a1 1 0 0 0 0 -2m14 0a1 1 0 1 0 0 2a1 1 0 0 0 0 -2" />
                </svg>
                {{ __('Campeonatos') }}
            </x-responsive-nav-link>

            {{-- 🟢 BOTÓN MAPA MÓVIL --}}
            <x-responsive-nav-link href="{{ route('mapa.index') }}" :active="request()->routeIs('mapa.index')" class="text-gray-200 hover:bg-white/10 rounded-lg border-l-4 border-transparent hover:border-green-400 transition-all flex items-center gap-3 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                {{ __('MAPA GENERAL') }}
            </x-responsive-nav-link>
            
            {{-- MODO OSCURO MÓVIL --}}
            @auth
                 <div class="px-4 py-3 text-gray-300 hover:text-white hover:bg-white/10 cursor-pointer rounded-lg flex items-center gap-3 transition border-l-4 border-transparent hover:border-yellow-400" @click="toggleTheme()">
                    <span x-show="!darkMode" class="flex items-center gap-3 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        Modo Oscuro
                    </span>
                    <span x-show="darkMode" class="flex items-center gap-3 font-medium text-yellow-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Modo Claro
                    </span>
                 </div>
            @endauth

            @auth
                <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" class="text-white hover:bg-white/10 rounded-lg border-l-4 border-transparent hover:border-green-400 transition-all flex items-center gap-3 py-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('Mis Reservas') }}
                </x-responsive-nav-link>
                
                {{-- ENLACES ADMIN MÓVIL --}}
                @if (Auth::user()->role === 'admin')
                    <div class="mt-4 pt-2 border-t border-white/10 bg-red-900/20 rounded-md">
                        <div class="px-4 text-xs font-bold text-red-400 uppercase mb-2 mt-2">Administración</div>
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Ver Resumen') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.contacts.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Consultas') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.suggestions.received') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Sugerencias') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.users.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Usuarios') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.districts.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Distritos') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.sports.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Deportes') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.services.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Servicios') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.owners.index') }}" class="text-purple-400 hover:text-purple-300 font-bold pl-8">{{ __('Gestión Dueños') }}</x-responsive-nav-link>
                    </div>
                @endif
                
                {{-- ENLACES PROVEEDOR MÓVIL --}}
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                     <div class="mt-2 pt-2 border-t border-white/10 bg-green-900/20 rounded-md">
                        <div class="px-4 text-xs font-bold text-green-400 uppercase mb-2 mt-2">Proveedor</div>
                        <x-responsive-nav-link href="{{ route('owner.canchas.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Mis Canchas') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('owner.reservas.index') }}" class="text-gray-300 hover:text-white pl-8">{{ __('Gestionar Reservas') }}</x-responsive-nav-link>
                     </div>
                @endif

                <div class="pt-4 pb-1 border-t border-white/10 mt-4 bg-black/30 rounded-lg">
                    <div class="flex items-center px-4 py-2">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover border-2 border-green-500 shadow-lg" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-base text-white">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-xs text-gray-400">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1 px-2">
                        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="text-gray-300 hover:text-white hover:bg-white/10 rounded-md font-bold">
                            {{ __('Perfil') }}
                        </x-responsive-nav-link>
                        
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-responsive-nav-link href="#" @click.prevent="$root.submit();" class="text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-md font-bold">
                                {{ __('Cerrar Sesión') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
<div class="h-20 w-full bg-transparent"></div>
</div>