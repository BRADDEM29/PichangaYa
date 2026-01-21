{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation\partials\public-links.blade.php --}}

<a href="{{ route('home') }}" class="flex items-center gap-2 px-2 font-bold uppercase text-sm tracking-wide transition-colors duration-300 text-white hover:text-[#2fa027]">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Inicio
</a>

<a href="{{ route('arena.index') }}" class="flex items-center gap-2 px-2 font-bold uppercase text-sm tracking-wide transition-colors duration-300 {{ request()->routeIs('arena.*') ? 'text-[#2fa027]' : 'text-white hover:text-[#2fa027]' }}">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3a1 1 0 0 1 .993 .883l.007 .117v2.17a3 3 0 1 1 0 5.659v.171a6.002 6.002 0 0 1 -5 5.917v2.083h3a1 1 0 0 1 .117 1.993l-.117 .007h-8a1 1 0 0 1 -.117 -1.993l.117 -.007h3v-2.083a6.002 6.002 0 0 1 -4.996 -5.692l-.004 -.225v-.171a3 3 0 0 1 -3.996 -2.653l-.003 -.176l.005 -.176a3 3 0 0 1 3.995 -2.654l-.001 -2.17a1 1 0 0 1 1 -1h10zm-12 5a1 1 0 1 0 0 2a1 1 0 0 0 0 -2m14 0a1 1 0 1 0 0 2a1 1 0 0 0 0 -2"/></svg>
    Campeonatos
</a>

<a href="{{ route('mapa.index') }}" class="flex items-center gap-2 px-2 font-bold uppercase text-sm tracking-wide transition-colors duration-300 {{ request()->routeIs('mapa.index') ? 'text-[#2fa027]' : 'text-white hover:text-[#2fa027]' }}">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
    Mapa
</a>

@auth
    <a href="{{ route('reservas.user.index') }}" class="flex items-center gap-2 px-2 font-bold uppercase text-sm tracking-wide transition-colors duration-300 {{ request()->routeIs('reservas.user.*') ? 'text-[#2fa027]' : 'text-white hover:text-[#2fa027]' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        Mis Reservas
    </a>
@endauth