<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
    
    {{-- FONDO DE PELOTAS --}}
    <div class="absolute inset-0 z-0">
        {{-- He puesto una imagen de balones de alta calidad. Si tienes una propia en tu carpeta public,
             cambia la URL por: asset('images/tu-fondo-pelotas.jpg') --}}
        <img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?q=80&w=1920&auto=format&fit=crop" 
             alt="Fondo Deportivo" 
             class="w-full h-full object-cover">
             
        {{-- Capa oscura para asegurar que el texto se lea bien --}}
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
    </div>

    {{-- LOGO (Encima del fondo) --}}
    <div class="relative z-10 mb-6">
        {{ $logo }}
    </div>

    {{-- TARJETA DEL FORMULARIO --}}
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-2xl overflow-hidden sm:rounded-2xl relative z-10 border border-gray-200 dark:border-gray-700">
        {{ $slot }}
    </div>
</div>