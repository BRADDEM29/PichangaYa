<div x-show="isLoading"
     x-transition:leave="transition ease-in duration-500"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     {{-- 👇 AQUÍ ESTÁ EL ARREGLO: Usamos Tailwind directo para fijarlo en pantalla --}}
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/95"
     style="display: none;"
     x-bind:style="isLoading ? 'display: flex' : 'display: none'">

    {{-- Tu logo con la ruta correcta y clases de tamaño --}}
    <img src="{{ asset('images/2-sinfondo.webp') }}" 
         alt="Cargando..." 
         class="w-32 h-auto animate-pulse"> 
         {{-- 'w-32' es aprox 128px de ancho. 'animate-pulse' es una animación nativa de Tailwind --}}

</div>