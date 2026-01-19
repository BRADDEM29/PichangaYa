<div>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation-menu.blade.php --}}
    @php
        $forceLightMode = request()->routeIs([
            'login', 'register', 'password.*', 'admin.*', 'owner.*'
        ]) || request()->is(['admin/*', 'panel-dueno/*']);
    @endphp

    <nav x-data="{ 
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
        class="fixed w-full top-0 z-50 transition-all duration-300
               bg-[#0f172a]/95 backdrop-blur-xl border-b border-white/10
               shadow-[0_4px_30px_rgba(0,0,0,0.5)]">

        {{-- VARIABLES PHP --}}
        @php
            $displayName = '';
            $alertEmail = false; 
            $alertPhone = false; 
            $lobbyActive = false; 

            if (Auth::check()) {
                $user = Auth::user();
                $displayName = strtok($user->name, ' '); 
                $isStaff = in_array($user->role, ['admin', 'owner']);
                $alertEmail = !$isStaff && !$user->hasVerifiedEmail();
                $alertPhone = !$isStaff && is_null($user->phone_verified_at);
                $lobbyActive = $user->status === 'searching' || $user->status === 'ingame';
            }
        @endphp

        <style>
            .logo-super-glow { filter: brightness(1.3) contrast(1.1) drop-shadow(0 0 1px rgba(255,255,255,1)); transition: all 0.4s; }
            .logo-super-glow:hover { transform: scale(1.05); filter: brightness(1.5) drop-shadow(0 0 10px rgba(255,255,255,0.8)); }
            
            .nav-neon-link {
                position: relative; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.05em;
                transition: color 0.3s ease; display: flex; align-items: center; gap: 0.4rem; text-transform: uppercase;
            }
            .nav-neon-link::after {
                content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 50%;
                background-color: #4ade80; box-shadow: 0 0 10px #4ade80;
                transition: width 0.3s ease, left 0.3s ease; transform: translateX(-50%);
            }
            .nav-neon-link:hover::after, .nav-neon-link.active::after { width: 100%; }

            .btn-pill-3d {
                transition: all 0.2s ease;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 
                            inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
            .btn-pill-3d:hover { transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3); }
            .btn-pill-3d:active { transform: translateY(1px); box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.3); }
        </style>

        {{-- 🟢 AQUÍ ESTÁ EL CAMBIO: lg:px-[34px] (Exactamente 34px a los lados) --}}
        <div class="w-full px-4 sm:px-6 lg:px-[34px]">
            <div class="flex justify-between h-20 items-center"> 
                
                {{-- 1. IZQUIERDA: LOGO --}}
                <div class="flex-shrink-0 flex items-center">
                    @include('navigation.partials.logo')
                </div>

                {{-- 2. CENTRO: MENÚ PÚBLICO --}}
                <div class="hidden lg:flex items-center gap-6 px-4">
                    @include('navigation.partials.public-links')
                </div>

                {{-- 3. CENTRO-DERECHA: GESTIÓN --}}
                <div class="hidden lg:flex items-center gap-3 ml-auto mr-4">
                    @include('navigation.partials.management-links')
                </div>

                {{-- 4. DERECHA EXTREMA: PERFIL --}}
                <div class="flex items-center gap-2 sm:gap-4">
                    @include('navigation.partials.user-dropdown')
                </div>

                {{-- BOTÓN MÓVIL --}}
                <div class="-mr-2 flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        {{-- MENÚ MÓVIL --}}
        @include('navigation.partials.mobile-menu')

    </nav>
    <div class="h-20 w-full bg-transparent"></div>
</div>