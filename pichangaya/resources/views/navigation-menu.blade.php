<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    
    {{-- 🟢 CORRECCIÓN: LÓGICA DE NOMBRE PROTEGIDA --}}
    @php
        $displayName = '';
        if (Auth::check()) {
            $user = Auth::user();
            $displayName = $user->name; // Por defecto

            // Si es cliente (no admin, no dueño), abreviar nombre
            if ($user->role !== 'admin' && $user->role !== 'owner') {
                $displayName = strtok($user->name, ' '); 
            }
        }
    @endphp

    {{-- Primary Navigation Menu --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- SECCIÓN IZQUIERDA: LOGO Y ENLACES PRINCIPALES --}}
            <div class="flex">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" id="tour-logo"> 
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                {{-- Links Escritorio --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" id="tour-dashboard">
                        {{ __('Inicio') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" id="tour-mis-reservas">
                            {{ __('📅 Mis Reservas') }}
                        </x-nav-link>

                        @if (Auth::user()->role === 'admin')
                            <div class="relative ms-3 flex items-center gap-2">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none transition ease-in-out duration-150">
                                                {{ __('🛡️ Admin') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Gestión del Sistema') }}</div>
                                        <x-dropdown-link href="{{ route('admin.dashboard') }}">{{ __('Ver Resumen') }}</x-dropdown-link>
                                        <div class="border-t border-gray-100"></div>
                                        <x-dropdown-link href="{{ route('admin.users.index') }}">{{ __('Usuarios') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.districts.index') }}">{{ __('Distritos') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.sports.index') }}">{{ __('Deportes') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.services.index') }}">{{ __('Servicios') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>

                                <a href="{{ route('admin.owners.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-purple-600 bg-purple-50 hover:bg-purple-100 focus:outline-none transition">
                                    {{ __('👥 Gestión Dueños') }}
                                </a>
                            </div>
                        @endif

                        @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                            <div class="relative ms-3 flex items-center">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none transition ease-in-out duration-150">
                                                {{ __('⚽ Proveedor') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Gestión de Canchas') }}</div>
                                        <x-dropdown-link href="{{ route('owner.canchas.index') }}">{{ __('Mis Canchas') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('owner.reservas.index') }}">{{ __('Gestionar Reservas') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- SECCIÓN DERECHA: PERFIL / LOGIN --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    {{-- Equipos --}}
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="ms-3 relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->currentTeam->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="w-60">
                                        <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Administrar Equipo') }}</div>
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">{{ __('Configuración del Equipo') }}</x-dropdown-link>
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">{{ __('Crear Nuevo Equipo') }}</x-dropdown-link>
                                        @endcan
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>
                                            <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Cambiar de Equipo') }}</div>
                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    {{-- MENÚ DE USUARIO --}}
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button id="tour-perfil" class="flex items-center gap-2 text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition hover:bg-gray-50 pl-2 pr-1 py-1">
                                        <span class="font-bold text-gray-700 hidden md:inline-block">
                                            {{ $displayName }}
                                        </span>
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button id="tour-perfil" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ $displayName }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Administrar Cuenta') }}</div>
                                <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Perfil') }}</x-dropdown-link>
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">{{ __('Tokens API') }}</x-dropdown-link>
                                @endif
                                <div class="border-t border-gray-200"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">{{ __('Cerrar Sesión') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    {{-- INVITADOS --}}
                    <div class="space-x-4 flex items-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 font-bold hover:text-indigo-600 transition">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm font-bold py-2 px-4 rounded hover:bg-indigo-700 transition shadow-md">Registrarse</a>
                    </div>
                @endauth
            </div>

            {{-- BOTÓN HAMBURGUESA --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU RESPONSIVE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">{{ __('Inicio') }}</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')">{{ __('📅 Mis Reservas') }}</x-responsive-nav-link>
                @if (Auth::user()->role === 'admin')
                    <div class="border-t border-gray-200 mt-2 pt-2 bg-red-50">
                        <div class="block px-4 py-2 text-xs text-red-600 font-bold uppercase">{{ __('🛡️ Panel Admin') }}</div>
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}">{{ __('Ver Resumen') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.owners.index') }}" class="text-purple-600 font-bold">{{ __('👥 Gestión Dueños') }}</x-responsive-nav-link>
                    </div>
                @endif
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div class="border-t border-gray-200 mt-2 pt-2 bg-green-50">
                        <div class="block px-4 py-2 text-xs text-green-600 font-bold uppercase">{{ __('⚽ Panel Proveedor') }}</div>
                        <x-responsive-nav-link href="{{ route('owner.canchas.index') }}">{{ __('Mis Canchas') }}</x-responsive-nav-link>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</nav>