<div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
    
    {{-- Mensaje de Éxito: Usamos !empty para asegurar que no falle si la variable es nula --}}
    @if (!empty($successMessage))
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/40 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl font-bold flex items-center gap-3 animate-pulse">
            <span class="text-xl">✅</span>
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Campo Nombre --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                <input type="text" wire:model="name" 
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500 transition-colors">
                @error('name') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>

            {{-- Campo Email --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Correo Electrónico</label>
                <input type="email" wire:model="email" 
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500 transition-colors">
                @error('email') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Campo Asunto --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">¿Cuál es el motivo de tu contacto?</label>
            <select wire:model="subject" 
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500 transition-colors">
                <option value="">Selecciona una opción</option>
                <option value="Problema con una Reserva">⚠️ Problema con una Reserva</option>
                <option value="Duda sobre Pagos">💰 Duda sobre Pagos</option>
                <option value="Error en la aplicación">💻 Error en la aplicación</option>
                <option value="Información para Dueños">🏟️ Información para Dueños</option>
                <option value="Otros">📩 Otros</option>
            </select>
            @error('subject') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
        </div>

        {{-- Campo Mensaje --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tu Mensaje</label>
            <textarea wire:model="message" rows="4" 
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500 transition-colors" 
                placeholder="Cuéntanos con detalle cómo podemos ayudarte..."></textarea>
            @error('message') <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span> @enderror
        </div>

        {{-- Botón con Estado de Carga --}}
        <button type="submit" 
            wire:loading.attr="disabled"
            class="w-full bg-green-600 hover:bg-green-500 disabled:opacity-50 text-white font-black py-4 rounded-xl transition shadow-lg shadow-green-500/30 flex justify-center items-center gap-3">
            
            {{-- Texto normal --}}
            <span wire:loading.remove>Enviar Consulta Directa</span>
            
            {{-- Texto mientras carga --}}
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Procesando envío...
            </span>
        </button>
    </form>
</div>