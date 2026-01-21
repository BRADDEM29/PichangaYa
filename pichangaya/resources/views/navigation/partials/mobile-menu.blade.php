{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation\partials\mobile-menu.blade.php --}}

<nav class="pt-2 pb-3 space-y-1 px-4 overflow-y-auto max-h-[calc(100vh-100px)]">
    
    {{-- Enlaces Principales --}}
    <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" 
        class="text-white !border-none !bg-transparent aria-[current]:!bg-[#2fa027] aria-[current]:!text-white hover:text-[#2fa027]">
        Inicio
    </x-responsive-nav-link>

    <x-responsive-nav-link href="{{ route('arena.index') }}" :active="request()->routeIs('arena.index')" 
        class="text-white !border-none !bg-transparent aria-[current]:!bg-[#2fa027] aria-[current]:!text-white hover:text-[#2fa027]">
        Campeonatos
    </x-responsive-nav-link>

    <x-responsive-nav-link href="{{ route('mapa.index') }}" :active="request()->routeIs('mapa.index')" 
        class="text-white !border-none !bg-transparent aria-[current]:!bg-[#2fa027] aria-[current]:!text-white hover:text-[#2fa027]">
        Mapa
    </x-responsive-nav-link>
    
    @auth
        <x-responsive-nav-link href="{{ route('reservas.user.index') }}" :active="request()->routeIs('reservas.user.*')" 
            class="text-white !border-none !bg-transparent aria-[current]:!bg-[#2fa027] aria-[current]:!text-white hover:text-[#2fa027]">
            Mis Reservas
        </x-responsive-nav-link>
        
        {{-- Gestión Admin (Rojo 3D) --}}
        @if (Auth::user()->role === 'admin')
            <section class="mt-4 border border-red-500/30 bg-gradient-to-br from-red-600 to-red-700 rounded-xl overflow-hidden shadow-lg">
                <header class="flex items-center gap-2 px-4 py-2 bg-black/20 border-b border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-white">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Gestión del Sistema</span>
                </header>
                
                <x-responsive-nav-link href="{{ route('admin.dashboard') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Ver Resumen</x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.contacts.index') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Consultas</x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.suggestions.received') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Sugerencias</x-responsive-nav-link>
                
                <hr class="border-t border-white/10 mx-4">
                
                <x-responsive-nav-link href="{{ route('admin.users.index') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Usuarios</x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.districts.index') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Distritos</x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.sports.index') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Deportes</x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.services.index') }}" class="!text-red-50 !border-none aria-[current]:!bg-[#2fa027]">Servicios</x-responsive-nav-link>
                
                <hr class="border-t border-white/10 mx-4">

                <x-responsive-nav-link href="{{ route('admin.tournaments.index') }}" :active="request()->routeIs('admin.tournaments.*')" 
                    class="!text-white font-bold !bg-green-600/30 !border-none aria-[current]:!bg-[#2fa027] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    Gestión Torneos
                </x-responsive-nav-link>
            </section>

            {{-- Botón Dueños (Púrpura 3D) --}}
            <section class="mt-3 border border-purple-500/30 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl overflow-hidden shadow-lg">
                <x-responsive-nav-link href="{{ route('admin.owners.index') }}" class="!flex items-center gap-3 !py-3 !text-white !border-none aria-[current]:!bg-[#2fa027]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                    </svg>
                    <span class="font-bold uppercase text-xs tracking-widest">Dueños</span>
                </x-responsive-nav-link>
            </section>
        @endif

        {{-- Gestión Proveedor (Verde 3D) --}}
        @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
            <section class="mt-3 border border-green-500/30 bg-gradient-to-br from-green-600 to-green-700 rounded-xl overflow-hidden shadow-lg">
                <header class="flex items-center gap-2 px-4 py-2 bg-black/20 border-b border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-white">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Gestión de Canchas</span>
                </header>
                <x-responsive-nav-link href="{{ route('owner.canchas.index') }}" class="!text-green-50 !border-none aria-[current]:!bg-[#2fa027]">Mis Canchas</x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('owner.reservas.index') }}" class="!text-green-50 !border-none aria-[current]:!bg-[#2fa027]">Gestionar Reservas</x-responsive-nav-link>
            </section>
        @endif
    @endauth
</nav>