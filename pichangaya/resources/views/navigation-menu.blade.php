{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation-menu.blade.php --}}
<nav x-data="{ open: false, darkMode: localStorage.getItem('dark-mode') === 'true' }" 
     class="bg-gray-900/90 backdrop-blur-md border-b border-gray-700 shadow-lg sticky top-0 z-50 
            shadow-[inset_60px_0_50px_-50px_rgba(34,197,94,0.3),_inset_-60px_0_50px_-50px_rgba(34,197,94,0.3)]">
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

            // Lógica de Alertas de Seguridad (Ignorar si es Admin o Owner)
            // Si el rol es admin u owner, forzamos las alertas a false.
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
        .logo-glow {
            filter: drop-shadow(0 0 5px #ffffff) drop-shadow(0 0 15px #ffffff) drop-shadow(0 0 40px rgba(255, 255, 255, 0.8));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            brightness: 1.1; 
        }
        .logo-glow:hover {
            filter: drop-shadow(0 0 8px #ffffff) drop-shadow(0 0 25px #ffffff) drop-shadow(0 0 60px #ffffff);
            transform: scale(1.1) translateY(-2px);
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- LOGO Y LINKS IZQUIERDA --}}
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" id="tour-logo" class="flex items-center"> 
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

                        {{-- BOTONES ADMIN / OWNER --}}
                        <div class="hidden lg:flex items-center gap-2 ms-4">
                            @if (Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                    <x-slot name="trigger">
                                        <button id="tour-admin" type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition shadow-sm gap-2">
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
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ __('Gestión Dueños') }}
                                </a>
                            @endif

                            @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                                <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                    <x-slot name="trigger">
                                        <button id="tour-proveedor" type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition shadow-sm gap-2">
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
                    {{-- 🟢 AQUÍ ESTÁ EL CAMBIO PRINCIPAL: INCLUIMOS EL NUEVO ARCHIVO 🟢 --}}
                    @include('navigation.notifications')

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
                                            <span x-show="!darkMode" class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                                {{ __('Modo Oscuro') }}
                                            </span>
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
</nav>