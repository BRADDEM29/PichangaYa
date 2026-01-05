{{-- resources/views/components/floating-buttons.blade.php --}}
<div x-data="{ open: false }" class="fixed bottom-6 right-6 flex flex-col-reverse items-center gap-4 z-[9999]">
    
    {{-- Botón Principal (El SVG es el botón) --}}
    <button id="btn-ayuda-home" 
            @click="open = !open" 
            :class="open ? 'text-gray-800 dark:text-gray-200 rotate-180' : 'text-green-600 hover:text-green-500'"
            class="transition-all duration-300 transform hover:scale-110 active:scale-95 focus:outline-none flex items-center justify-center p-0">
        
        {{-- Icono Desplegable (Chevrons Up) --}}
        <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'hidden' : 'block'" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler-circle-chevrons-up">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l3 -3l3 3" /><path d="M9 11l3 -3l3 3" /><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
        </svg>

        {{-- Icono de Cerrar (X) cuando está abierto --}}
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" />
        </svg>
    </button>

    {{-- Botón de WhatsApp --}}
    <a href="https://wa.me/51900000000" target="_blank"
       x-show="open"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 translate-y-4 scale-50"
       x-transition:enter-end="opacity-100 translate-y-0 scale-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 translate-y-0 scale-100"
       x-transition:leave-end="opacity-0 translate-y-4 scale-50"
       class="text-[#25D366] hover:text-[#128C7E] transition-all transform hover:scale-125"
       title="Contactar por WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
        </svg>
    </a>

    {{-- Botón de Ayuda (Tutorial) --}}
    <button @click="window.driverHome.drive(); open = false"
            x-show="open"
            x-transition:enter="transition ease-out duration-300 delay-[50ms]"
            x-transition:enter-start="opacity-0 translate-y-4 scale-50"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-50"
            class="text-blue-500 hover:text-blue-600 transition-all transform hover:scale-125"
            title="Ver tutorial de ayuda">
        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" />
        </svg>
    </button>

</div>