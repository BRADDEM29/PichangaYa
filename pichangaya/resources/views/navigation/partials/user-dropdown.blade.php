@auth
    {{-- Contenedor "Glass" para usuario --}}
    <div class="flex items-center gap-3 bg-white/5 px-3 py-1.5 rounded-full border border-white/10 backdrop-blur-md shadow-lg">
        
        {{-- Campanita de Notificaciones --}}
        @include('navigation.notifications')

        {{-- Dropdown Perfil --}}
        <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-600 shadow-2xl">
            <x-slot name="trigger">
                <button class="flex items-center gap-3 text-sm focus:outline-none transition group">
                    <div class="text-right hidden xl:block leading-tight">
                        <div class="font-bold text-gray-200 group-hover:text-white text-xs uppercase tracking-wide">
                            {{ strtok(Auth::user()->name, ' ') }}
                        </div>
                    </div>
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-500 group-hover:border-green-400 transition" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    @else
                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    @endif
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2 text-xs text-gray-400 uppercase font-bold border-b dark:border-gray-700">Cuenta</div>
                <x-dropdown-link href="{{ route('profile.show') }}">Perfil</x-dropdown-link>
                
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                <button @click="toggleTheme()" class="flex w-full px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 font-bold items-center gap-2">
                    <span x-show="!darkMode">🌙 Oscuro</span>
                    <span x-show="darkMode">☀️ Claro</span>
                </button>

                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Cerrar Sesión
                    </button>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
@else
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-white transition uppercase">Iniciar Sesión</a>
        <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold text-white bg-green-600 hover:bg-green-500 rounded-full shadow-lg transition">Registro</a>
    </div>
@endauth

{{-- Botón Hamburguesa (Móvil) --}}
<div class="-mr-2 flex items-center lg:hidden ml-4">
    <button @click="open = ! open" class="p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none border border-transparent hover:border-white/20">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>