@php
    $forceLightMode = request()->routeIs([
        'login', 'register', 'password.*', 'admin.*', 'owner.*'
    ]) || request()->is(['admin/*', 'panel-dueno/*']);

    $displayName = '';
    $alertEmail = false; 
    $alertPhone = false; 
    $lobbyActive = false; 
    $currentLobby = null;

    if (Auth::check()) {
        $user = Auth::user();
        $displayName = strtok($user->name, ' '); 
        $isStaff = in_array($user->role, ['admin', 'owner']);
        $alertEmail = !$isStaff && !$user->hasVerifiedEmail();
        $alertPhone = !$isStaff && is_null($user->phone_verified_at);
        
        // 1. Verificamos si el usuario está en modo juego/búsqueda
        $lobbyActive = $user->status === 'searching' || $user->status === 'ingame';
        
        // 2. Cargamos el Lobby y el Deporte para saber los cupos reales
        if ($lobbyActive) {
            $slot = \App\Models\LobbySlot::where('user_id', $user->id)->with('lobby.sport')->first();
            if ($slot) {
                $currentLobby = $slot->lobby;
            }
        }
    }
@endphp

{{-- RAIZ ÚNICA: Navegación Principal --}}
<nav 
    x-data="{ 
        open: false, 
        darkMode: {{ $forceLightMode ? 'false' : "localStorage.getItem('dark-mode') === 'true'" }},
        toggleTheme() {
            if ({{ $forceLightMode ? 'true' : 'false' }}) return;
            this.darkMode = !this.darkMode;
            localStorage.setItem('dark-mode', this.darkMode);
            if (this.darkMode) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        }
    }" 
    @if(!$forceLightMode)
        x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); 
                if(darkMode) document.documentElement.classList.add('dark');"
    @endif
    class="relative"
>
    <style>
        .logo-super-glow { filter: brightness(1.3) contrast(1.1) drop-shadow(0 0 1px rgba(255,255,255,1)); transition: all 0.4s; }
        .logo-super-glow:hover { transform: scale(1.05); filter: brightness(1.5) drop-shadow(0 0 10px rgba(255,255,255,0.8)); }
        .nav-neon-link { position: relative; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.05em; transition: color 0.3s ease; display: flex; align-items: center; gap: 0.4rem; text-transform: uppercase; }
        .nav-neon-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 50%; background-color: #4ade80; box-shadow: 0 0 10px #4ade80; transition: width 0.3s ease, left 0.3s ease; transform: translateX(-50%); }
        .nav-neon-link:hover::after, .nav-neon-link.active::after { width: 100%; }
        .btn-pill-3d { transition: all 0.2s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2); }
        .btn-pill-3d:hover { transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3); }
        .btn-pill-3d:active { transform: translateY(1px); box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.3); }
    </style>

    {{-- HEADER FLOTANTE --}}
    <header class="fixed w-full top-0 z-50 transition-all duration-300 bg-[#0f172a]/95 backdrop-blur-xl border-b border-white/10 shadow-[0_4px_30px_rgba(0,0,0,0.5)] px-4 sm:px-6 lg:px-[34px]">
        <section class="flex justify-between h-20 items-center w-full">
            
            {{-- LOGO --}}
            <figure class="flex-shrink-0 flex items-center">
                @include('navigation.partials.logo')
            </figure>

            {{-- LINKS ESCRITORIO --}}
            <ul class="hidden lg:flex items-center gap-6 px-4 list-none">
                @include('navigation.partials.public-links')
            </ul>

            {{-- 🟢 ESTADO DEL LOBBY (DINÁMICO) - APARECE EN MEDIO --}}
            @if($lobbyActive && $currentLobby)
                <aside class="hidden lg:flex items-center ml-auto mr-4">
                    <a href="{{ route('lobby.show', $currentLobby->id) }}" 
                       class="group relative flex items-center gap-3 bg-gray-900 border border-gray-700 rounded-full pl-1 pr-4 py-1 hover:border-green-500 transition-all shadow-lg overflow-hidden">
                        
                        {{-- Efecto Brillo --}}
                        <span class="absolute inset-0 bg-green-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>

                        {{-- Icono Estado --}}
                        <figure class="relative flex items-center justify-center w-9 h-9 bg-gray-800 rounded-full border border-gray-600 group-hover:bg-gray-700 z-10">
                            @if($currentLobby->status === 'searching')
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-20"></span>
                                <svg class="w-4 h-4 text-blue-400 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            @else
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-20"></span>
                                <svg class="w-4 h-4 text-yellow-400 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </figure>

                        {{-- Info Texto --}}
                        <section class="flex flex-col z-10">
                            <h3 class="text-[9px] font-black uppercase tracking-widest text-gray-400 group-hover:text-white transition-colors leading-none mb-1">
                                {{ $currentLobby->status === 'searching' ? 'BUSCANDO' : 'CONFIRMAR' }}
                            </h3>
                            
                            <p class="flex items-center gap-2 leading-none">
                                {{-- 🟢 AQUÍ ESTÁ EL NÚMERO DINÁMICO (Ej: 1/2 o 5/14) --}}
                                <span class="text-xs font-bold text-white font-mono">
                                    {{ $currentLobby->slots_count }}/{{ $currentLobby->max_slots ?? 14 }}
                                </span>
                                
                                <span class="text-[8px] text-gray-500">•</span>
                                
                                <span class="text-[10px] font-bold text-gray-400 truncate max-w-[80px]">
                                    {{ $currentLobby->sport->name ?? 'Deporte' }}
                                </span>
                            </p>
                        </section>
                    </a>
                </aside>
            @endif

            {{-- LINKS GESTIÓN --}}
            <ul class="hidden lg:flex items-center gap-3 {{ ($lobbyActive && $currentLobby) ? 'ml-4' : 'ml-auto' }} mr-4 list-none">
                @auth
                    @include('navigation.partials.management-links')
                @endauth
            </ul>

            {{-- DROPDOWN USUARIO --}}
            <aside class="flex items-center gap-2 sm:gap-4">
                @include('navigation.partials.user-dropdown')
            </aside>

            {{-- BOTÓN HAMBURGUESA (MÓVIL) --}}
            <section class="-mr-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </section>
        </section>

        {{-- MENÚ MÓVIL --}}
        <section x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="lg:hidden bg-[#0f172a] border-t border-gray-700 -mx-4 sm:-mx-6 lg:-mx-[34px]">
            
            {{-- 🟢 ESTADO DEL LOBBY (VISIÓN MÓVIL) --}}
            @if($lobbyActive && $currentLobby)
                <article class="pt-4 pb-2 px-4 bg-gray-900 border-b border-gray-700">
                    <header class="flex items-center justify-between">
                        <h4 class="text-white font-bold text-sm flex items-center gap-2">
                            @if($currentLobby->status === 'searching')
                                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                                Buscando Partida
                            @else
                                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-bounce"></span>
                                Confirmando
                            @endif
                        </h4>
                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-mono">
                            {{ $currentLobby->slots_count }}/{{ $currentLobby->max_slots ?? 14 }}
                        </span>
                    </header>
                    <a href="{{ route('lobby.show', $currentLobby->id) }}" class="mt-2 block w-full text-center bg-blue-600 hover:bg-blue-500 text-white text-xs py-2 rounded uppercase font-bold tracking-wider">
                        Ir a la Sala de {{ $currentLobby->sport->name ?? 'Juego' }}
                    </a>
                </article>
            @endif

            @include('navigation.partials.mobile-menu')
        </section>
    </header>

    {{-- ESPACIADOR --}}
    <div class="h-20 w-full pointer-events-none"></div>
</nav>