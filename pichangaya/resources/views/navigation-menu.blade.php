<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    {{-- Primary Navigation Menu --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- SECCIÓN IZQUIERDA: LOGO Y ENLACES PRINCIPALES --}}
            <div class="flex">
                {{-- Logo (Redirige al Home) --}}
                <div class="shrink-0 flex items-center">
                    {{-- 📍 TOUR: ID DEL LOGO --}}
                    <a href="{{ route('dashboard') }}" id="tour-logo"> 
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                {{-- NAV LINKS DE ESCRITORIO --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    {{-- Enlace Inicio (Visible para todos) --}}
                    {{-- 📍 TOUR: ID DEL DASHBOARD/INICIO --}}
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" id="tour-dashboard">
                        {{ __('Inicio') }}
                    </x-nav-link>

                    {{-- ENLACES SOLO PARA USUARIOS REGISTRADOS --}}
                    @auth
                        {{-- Se eliminó "Explorar" (Dashboard) para evitar redundancia con Inicio --}}

                        {{-- 🟢 BOTÓN: MIS RESERVAS --}}
                        {{-- 👉 MEJORA: AGREGADO EL ID PARA EL TOUR --}}
                        <x-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" id="tour-mis-reservas">
                            {{ __('📅 Mis Reservas') }}
                        </x-nav-link>

                        {{-- MENÚ DE ADMINISTRADOR --}}
                        @if (Auth::user()->role === 'admin')
                            <div class="relative ms-3 flex items-center">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none transition ease-in-out duration-150">
                                                {{ __('🛡️ Admin') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Gestión del Sistema') }}
                                        </div>

                                        <x-dropdown-link href="{{ route('admin.dashboard') }}">
                                            {{ __('Ver Resumen') }}
                                        </x-dropdown-link>
                                        
                                        <div class="border-t border-gray-100"></div>

                                        <x-dropdown-link href="{{ route('admin.users.index') }}">
                                            {{ __('Usuarios') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link href="{{ route('admin.districts.index') }}">
                                            {{ __('Distritos') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link href="{{ route('admin.sports.index') }}">
                                            {{ __('Deportes') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif

                        {{-- MENÚ DE PROVEEDOR (OWNER) --}}
                        @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                            <div class="relative ms-3 flex items-center">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none transition ease-in-out duration-150">
                                                {{ __('⚽ Proveedor') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Gestión de Canchas') }}
                                        </div>

                                        <x-dropdown-link href="{{ route('owner.canchas.index') }}">
                                            {{ __('Mis Canchas') }}
                                        </x-dropdown-link>
                                        
                                        <x-dropdown-link href="{{ route('owner.reservas.index') }}">
                                            {{ __('Gestionar Reservas') }}
                                        </x-dropdown-link>
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
                    {{-- Selector de Equipos (Solo si está habilitado en Jetstream) --}}
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="ms-3 relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->currentTeam->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Administrar Equipo') }}
                                        </div>
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Configuración del Equipo') }}
                                        </x-dropdown-link>
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Crear Nuevo Equipo') }}
                                            </x-dropdown-link>
                                        @endcan
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Cambiar de Equipo') }}
                                            </div>
                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    {{-- MENÚ DE USUARIO (Perfil y Sesión) --}}
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    {{-- 📍 TOUR: ID DEL PERFIL (Con foto) --}}
                                    <button id="tour-perfil" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        {{-- 📍 TOUR: ID DEL PERFIL (Sin foto) --}}
                                        <button id="tour-perfil" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Administrar Cuenta') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Perfil') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('Tokens API') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                {{-- Formulario de Logout --}}
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    {{-- BOTONES PARA INVITADOS (Escritorio) --}}
                    <div class="space-x-4 flex items-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 font-bold hover:text-indigo-600 transition">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm font-bold py-2 px-4 rounded hover:bg-indigo-700 transition shadow-md">
                            Registrarse
                        </a>
                    </div>
                @endauth
            </div>

            {{-- BOTÓN HAMBURGUESA (MÓVIL) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU RESPONSIVE (MÓVIL) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        
        {{-- ENLACES PRINCIPALES MÓVIL --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>

            @auth
                {{-- Se eliminó "Explorar" en móvil también --}}

                <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')">
                    {{ __('📅 Mis Reservas') }}
                </x-responsive-nav-link>

                {{-- ENLACES RESPONSIVE ADMIN --}}
                @if (Auth::user()->role === 'admin')
                    <div class="border-t border-gray-200 mt-2 pt-2 bg-red-50">
                        <div class="block px-4 py-2 text-xs text-red-600 font-bold uppercase">
                            {{ __('🛡️ Panel Administrador') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Ver Resumen') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                            {{ __('Gestión Usuarios') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.districts.index') }}" :active="request()->routeIs('admin.districts.*')">
                            {{ __('Distritos') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.sports.index') }}" :active="request()->routeIs('admin.sports.*')">
                            {{ __('Deportes') }}
                        </x-responsive-nav-link>
                    </div>
                @endif

                {{-- ENLACES RESPONSIVE PROVEEDOR --}}
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div class="border-t border-gray-200 mt-2 pt-2 bg-green-50">
                        <div class="block px-4 py-2 text-xs text-green-600 font-bold uppercase">
                            {{ __('⚽ Panel Proveedor') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('owner.canchas.index') }}" :active="request()->routeIs('owner.canchas.*')">
                            {{ __('Mis Canchas') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('owner.reservas.index') }}" :active="request()->routeIs('owner.reservas.*')">
                            {{ __('Gestionar Reservas') }}
                        </x-responsive-nav-link>
                    </div>
                @endif
            @endauth
        </div>

        {{-- PERFIL Y SESIÓN RESPONSIVE --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('Tokens API') }}
                        </x-responsive-nav-link>
                    @endif

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}"
                            @click.prevent="$root.submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>

                    {{-- Gestión de Equipos Responsive --}}
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t border-gray-200"></div>
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Administrar Equipo') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                            {{ __('Configuración') }}
                        </x-responsive-nav-link>
                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                {{ __('Crear Nuevo Equipo') }}
                            </x-responsive-nav-link>
                        @endcan
                        @if (Auth::user()->allTeams()->count() > 1)
                            <div class="border-t border-gray-200"></div>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Cambiar de Equipo') }}
                            </div>
                            @foreach (Auth::user()->allTeams() as $team)
                                <x-switchable-team :team="$team" component="responsive-nav-link" />
                            @endforeach
                        @endif
                    @endif
                </div>
            @else
                {{-- BOTONES PARA INVITADOS (Móvil) --}}
                <div class="mt-3 space-y-1 pb-3 px-2">
                    <x-responsive-nav-link href="{{ route('login') }}" class="font-bold">
                        {{ __('Iniciar Sesión') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('register') }}" class="bg-indigo-50 text-indigo-700 font-bold rounded-md">
                        {{ __('Registrarse') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>