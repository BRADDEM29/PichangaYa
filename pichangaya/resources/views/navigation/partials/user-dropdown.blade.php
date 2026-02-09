{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation\partials\user-dropdown.blade.php --}}

@auth
    {{-- Contenedor del Usuario Logueado --}}
    <div class="flex items-center gap-3 bg-white/10 px-3 py-1.5 rounded-full border border-white/10 backdrop-blur-md shadow-lg transition-all hover:bg-white/15">
        
        {{-- Campanita de Notificaciones --}}
        @include('navigation.notifications')

        {{-- Dropdown Component --}}
        <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl ring-1 ring-black ring-opacity-5">
            
            {{-- TRIGGER: Avatar y Nombre --}}
            <x-slot name="trigger">
                <button class="flex items-center gap-3 text-sm focus:outline-none transition group">
                    <div class="text-right hidden xl:block leading-tight">
                        <div class="font-bold text-gray-100 group-hover:text-white text-xs uppercase tracking-wide transition-colors">
                            {{ strtok(Auth::user()->name, ' ') }}
                        </div>
                    </div>
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="relative">
                            <img class="h-9 w-9 rounded-full object-cover border-2 border-white/20 group-hover:border-indigo-400 transition-colors shadow-sm" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            <div class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-500 rounded-full border-2 border-gray-900"></div>
                        </div>
                    @else
                        <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold border-2 border-white/20 shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>
            </x-slot>

            {{-- CONTENT: Opciones del Menú --}}
            <x-slot name="content">
                {{-- Encabezado --}}
                <div class="px-4 py-2.5 text-[10px] text-gray-400 uppercase font-black tracking-widest border-b border-gray-100 dark:border-gray-700">
                    Mi Cuenta
                </div>

                {{-- Perfil --}}
                <x-dropdown-link href="{{ route('profile.show') }}" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                    Perfil
                </x-dropdown-link>
                
                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                {{-- Toggle Tema (Dark/Light) --}}
                <button @click="toggleTheme()" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                    {{-- Icono Luna (Mostrar si NO es dark mode para cambiar a oscuro) --}}
                    <div x-show="!darkMode" class="flex items-center gap-2 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                        </svg>
                        <span>Modo Oscuro</span>
                    </div>

                    {{-- Icono Sol (Mostrar si ES dark mode para cambiar a claro) --}}
                    <div x-show="darkMode" class="flex items-center gap-2 w-full" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                        </svg>
                        <span>Modo Claro</span>
                    </div>
                </button>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                {{-- Cerrar Sesión --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 font-bold flex items-center gap-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M9 12h12l-3 -3" />
                            <path d="M18 15l3 -3" />
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
@else
    {{-- Botones para Visitantes (Login/Register) --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-200 hover:text-white transition-colors uppercase tracking-wide">
            Iniciar Sesión
        </a>
        <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-full shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 border border-indigo-500">
             Registro
        </a>
    </div>
@endauth