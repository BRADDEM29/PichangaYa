{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\navigation\partials\management-links.blade.php --}}

@if (Auth::user()->role === 'admin')
    <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-700 shadow-xl">
        <x-slot name="trigger">
            {{-- Botón Admin Rojo 3D --}}
            <button id="tour-admin" type="button" class="btn-pill-3d flex items-center gap-2 px-4 py-1.5 bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-full border border-red-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                </svg>
                Admin
            </button>
        </x-slot>
        
        <x-slot name="content">
            <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">{{ __('Gestión del Sistema') }}</div>
            <x-dropdown-link href="{{ route('admin.dashboard') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Ver Resumen') }}</x-dropdown-link>
            <div class="border-t border-gray-100 dark:border-gray-700"></div>
            <x-dropdown-link href="{{ route('admin.contacts.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Consultas') }}</x-dropdown-link>
            <x-dropdown-link href="{{ route('admin.suggestions.received') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Sugerencias') }}</x-dropdown-link>
            <div class="border-t border-gray-100 dark:border-gray-700"></div>
            <x-dropdown-link href="{{ route('admin.users.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Usuarios') }}</x-dropdown-link>
            <x-dropdown-link href="{{ route('admin.districts.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Distritos') }}</x-dropdown-link>
            <x-dropdown-link href="{{ route('admin.sports.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Deportes') }}</x-dropdown-link>
            <x-dropdown-link href="{{ route('admin.services.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Servicios') }}</x-dropdown-link>
            
            <div class="border-t border-gray-100 dark:border-gray-700"></div>
            <x-dropdown-link href="{{ route('admin.tournaments.index') }}" class="text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900/20 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                {{ __('Gestión Torneos') }}
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>

    {{-- Botón Gestión Dueños Púrpura 3D --}}
    <a href="{{ route('admin.owners.index') }}" class="btn-pill-3d flex items-center gap-2 px-4 py-1.5 bg-gradient-to-br from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white text-xs font-bold uppercase tracking-wider rounded-full border border-purple-500/30">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
        </svg>
        Dueños
    </a>
@endif

@if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
    <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800 border border-gray-700 shadow-xl">
        <x-slot name="trigger">
            {{-- Botón Proveedor Verde 3D --}}
            <button id="tour-proveedor" type="button" class="btn-pill-3d flex items-center gap-2 px-4 py-1.5 bg-gradient-to-br from-green-600 to-green-700 hover:from-green-500 hover:to-green-600 text-white text-xs font-bold uppercase tracking-wider rounded-full border border-green-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                Proveedor
            </button>
        </x-slot>
        
        <x-slot name="content">
            <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">{{ __('Gestión de Canchas') }}</div>
            <x-dropdown-link href="{{ route('owner.canchas.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Mis Canchas') }}</x-dropdown-link>
            <x-dropdown-link href="{{ route('owner.reservas.index') }}" class="dark:text-white dark:hover:bg-gray-700">{{ __('Gestionar Reservas') }}</x-dropdown-link>
        </x-slot>
    </x-dropdown>
@endif