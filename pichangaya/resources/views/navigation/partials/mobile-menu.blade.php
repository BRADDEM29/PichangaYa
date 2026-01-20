<div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-[#0f172a] border-t border-white/10 shadow-inner">
    <div class="pt-2 pb-3 space-y-1 px-4">
        
        {{-- Links Públicos Móvil --}}
        <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" class="text-gray-200">Inicio</x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('arena.index') }}" :active="request()->routeIs('arena.index')" class="text-gray-200">Campeonatos</x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('mapa.index') }}" :active="request()->routeIs('mapa.index')" class="text-gray-200">Mapa</x-responsive-nav-link>
        
        @auth
            <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" class="text-gray-200">Mis Reservas</x-responsive-nav-link>
            
            {{-- Gestión Admin Móvil --}}
            @if (Auth::user()->role === 'admin')
                <div class="mt-4 border-t border-white/10 pt-2 bg-white/5 rounded-lg p-2">
                    <div class="text-xs text-red-400 font-bold uppercase mb-1 px-4">Admin</div>
                    <x-responsive-nav-link href="{{ route('admin.dashboard') }}" class="text-gray-300 pl-4">Resumen</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.owners.index') }}" class="text-gray-300 pl-4">Dueños</x-responsive-nav-link>
                    {{-- CAMBIO AQUÍ: Ahora lleva al INDEX de torneos --}}
                    <x-responsive-nav-link href="{{ route('admin.tournaments.index') }}" :active="request()->routeIs('admin.tournaments.*')" class="text-green-400 pl-4 font-bold">
                        ⚡ Gestión Torneos
                    </x-responsive-nav-link>
                </div>
            @endif

            {{-- Gestión Proveedor Móvil --}}
            @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                <div class="mt-2 border-t border-white/10 pt-2 bg-white/5 rounded-lg p-2">
                    <div class="text-xs text-green-400 font-bold uppercase mb-1 px-4">Proveedor</div>
                    <x-responsive-nav-link href="{{ route('owner.canchas.index') }}" class="text-gray-300 pl-4">Mis Canchas</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('owner.reservas.index') }}" class="text-gray-300 pl-4">Reservas</x-responsive-nav-link>
                </div>
            @endif
        @endauth
    </div>
</div>