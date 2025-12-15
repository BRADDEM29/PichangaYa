<nav x-data="{ open: false }" class="bg-gray-900/90 backdrop-blur-md border-b border-gray-700 shadow-lg sticky top-0 z-50">
    
    @php
        $displayName = '';
        if (Auth::check()) {
            $user = Auth::user();
            $displayName = $user->name; 
            if ($user->role !== 'admin' && $user->role !== 'owner') {
                $displayName = strtok($user->name, ' '); 
            }
        }
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- SECCIÓN IZQUIERDA --}}
            <div class="flex items-center">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    {{-- SIEMPRE lleva al home, nunca al dashboard --}}
                    <a href="{{ route('home') }}" id="tour-logo" class="flex items-center"> 
                        <x-application-mark class="block h-12 w-auto text-white fill-current transition hover:scale-105" />
                    </a>
                </div>

                {{-- Links Escritorio --}}
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    {{-- INICIO --}}
                    <div class="relative group">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                            {{ __('Inicio') }}
                        </a>
                    </div>

                    @auth
                        <div class="relative group">
                            <a href="{{ route('reservas.user.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('reservas.user.*') ? 'text-green-400 border-b-2 border-green-400' : 'text-white hover:text-green-300' }}">
                                {{ __('📅 Mis Reservas') }}
                            </a>
                        </div>

                        {{-- PANEL DE GESTIÓN (ADMIN / DUEÑO / PROVEEDOR) --}}
                        <div class="hidden lg:flex items-center gap-2 ms-4">
                            
                            @if (Auth::user()->role === 'admin')
                                {{-- BOTÓN ADMIN --}}
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition shadow-sm">
                                            {{ __('🛡️ Admin') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Gestión del Sistema') }}</div>
                                        <x-dropdown-link href="{{ route('admin.dashboard') }}">{{ __('Ver Resumen') }}</x-dropdown-link>
                                        <div class="border-t border-gray-100"></div>
                                        <x-dropdown-link href="{{ route('admin.users.index') }}">{{ __('Usuarios') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.districts.index') }}">{{ __('Distritos') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.sports.index') }}">{{ __('Deportes') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('admin.services.index') }}">{{ __('Servicios') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>

                                {{-- BOTÓN GESTIÓN DUEÑOS --}}
                                <a href="{{ route('admin.owners.index') }}" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none transition shadow-sm">
                                    {{ __('👥 Gestión Dueños') }}
                                </a>
                            @endif

                            @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                                {{-- BOTÓN PROVEEDOR --}}
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button" class="w-44 justify-center inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition shadow-sm">
                                            {{ __('⚽ Proveedor') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Gestión de Canchas') }}</div>
                                        <x-dropdown-link href="{{ route('owner.canchas.index') }}">{{ __('Mis Canchas') }}</x-dropdown-link>
                                        <x-dropdown-link href="{{ route('owner.reservas.index') }}">{{ __('Gestionar Reservas') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
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
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-200 bg-gray-800 hover:text-white focus:outline-none focus:bg-gray-700 active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ Auth::user()->currentTeam->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="w-60">
                                        <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Administrar Equipo') }}</div>
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">{{ __('Configuración del Equipo') }}</x-dropdown-link>
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">{{ __('Crear Nuevo Equipo') }}</x-dropdown-link>
                                        @endcan
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-100"></div>
                                            <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Cambiar de Equipo') }}</div>
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
                                    <button id="tour-perfil" class="flex items-center gap-2 text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-500 transition hover:bg-gray-800 pl-2 pr-1 py-1">
                                        <span class="font-bold text-gray-200 hidden md:inline-block">
                                            {{ $displayName }}
                                        </span>
                                        <img class="h-8 w-8 rounded-full object-cover border border-gray-600" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button id="tour-perfil" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-200 bg-gray-800 hover:text-white focus:outline-none focus:bg-gray-700 active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ $displayName }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-500">{{ __('Administrar Cuenta') }}</div>
                                <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Perfil') }}</x-dropdown-link>
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">{{ __('Tokens API') }}</x-dropdown-link>
                                @endif
                                <div class="border-t border-gray-100"></div>
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

    {{-- MENU RESPONSIVE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900/95 border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-white hover:text-green-400 hover:bg-gray-800">{{ __('Inicio') }}</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" class="text-white hover:text-green-400 hover:bg-gray-800">{{ __('📅 Mis Reservas') }}</x-responsive-nav-link>
                @if (Auth::user()->role === 'admin')
                    <div class="border-t border-gray-700 mt-2 pt-2 bg-red-900/20">
                        <div class="block px-4 py-2 text-xs text-red-400 font-bold uppercase">{{ __('🛡️ Panel Admin') }}</div>
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white">{{ __('Ver Resumen') }}</x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.owners.index') }}" class="text-purple-400 font-bold">{{ __('👥 Gestión Dueños') }}</x-responsive-nav-link>
                    </div>
                @endif
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div class="border-t border-gray-700 mt-2 pt-2 bg-green-900/20">
                        <div class="block px-4 py-2 text-xs text-green-400 font-bold uppercase">{{ __('⚽ Panel Proveedor') }}</div>
                        <x-responsive-nav-link href="{{ route('owner.canchas.index') }}" class="text-gray-300 hover:text-white">{{ __('Mis Canchas') }}</x-responsive-nav-link>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</nav>