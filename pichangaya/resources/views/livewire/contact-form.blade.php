<div class="bg-white dark:bg-gray-900 p-8 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-xl relative overflow-hidden">
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\contact-form.blade.php --}}
    
    {{-- Decoración de fondo --}}
    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-500 rounded-full opacity-10 blur-2xl"></div>

    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        Envíanos tu consulta
    </h2>

    {{-- MENSAJES DE ESTADO --}}
    @if($enviado)
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm animate-pulse flex items-center gap-2">
            <span class="text-xl">✅</span>
            <div>
                <p class="font-bold">¡Consulta Enviada!</p>
                <p class="text-sm">Gracias por tu consulta. Te responderemos en breve.</p>
            </div>
        </div>
    @endif

    @if(!$canSend && Auth::check() && !$enviado)
        <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-700 rounded-r shadow-sm">
            <p class="font-bold">⏳ Límite alcanzado</p>
            <p class="text-sm">Solo puedes enviar una consulta cada 24 horas.</p>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        
        {{-- Motivo --}}
        <div>
            <label for="motivo" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Asunto / Motivo</label>
            <select wire:model="motivo" id="motivo" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 transition h-12">
                <option value="">Selecciona un motivo...</option>
                <option value="Soporte Técnico">Problema Técnico / Web</option>
                <option value="Problema Reserva">Problema con una Reserva</option>
                <option value="Pagos">Consultas sobre Pagos</option>
                <option value="Registrar Complejo">Quiero registrar mi Complejo Deportivo</option>
                <option value="Otro">Otro tema</option>
            </select>
            @error('motivo') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        {{-- Mensaje (Con AlpineJS para contador) --}}
        <div x-data="{ 
            content: @entangle('mensaje'), 
            limit: 200, 
            get count() { return this.content ? this.content.trim().split(/\s+/).filter(w => w !== '').length : 0 }
        }">
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex justify-between">
                <span>Tu Mensaje</span>
                <span class="text-xs" :class="count >= limit ? 'text-red-500 font-black' : 'text-gray-400 font-normal'">
                    <span x-text="count"></span> / <span x-text="limit"></span> palabras
                </span>
            </label>
            <textarea wire:model="mensaje" x-model="content" rows="5" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 transition p-4" placeholder="Cuéntanos en detalle cómo podemos ayudarte..."></textarea>
            @error('mensaje') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        {{-- CAMPO TELÉFONO --}}
        @if(Auth::check())
            <div class="animate-fade-in-down">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Celular de Contacto</label>
                <input type="text" wire:model="phone" placeholder="Ej: 987654321" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-700 focus:ring-green-500 focus:border-green-500">
                @error('phone') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            </div>
        @endif

        {{-- Botón de Acción con Iconos SVG --}}
        <button type="submit" 
            @if(Auth::check() && !$canSend) disabled @endif
            class="w-full py-4 px-6 rounded-xl font-black text-white shadow-lg transition transform hover:scale-[1.02] flex justify-center items-center gap-2
            {{ Auth::check() ? ($canSend ? 'bg-gray-900 hover:bg-gray-800 dark:bg-white dark:text-gray-900' : 'bg-gray-400 cursor-not-allowed') : 'bg-blue-600 hover:bg-blue-700' }}">
            
            @if(Auth::check())
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-message-chatbot"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12" /><path d="M9.5 9h.01" /><path d="M14.5 9h.01" /><path d="M9.5 13a3.5 3.5 0 0 0 5 0" /></svg>
                <span>ENVIAR MENSAJE AHORA</span>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                <span>INICIAR SESIÓN PARA ENVIAR</span>
            @endif

            {{-- Spinner --}}
            <svg wire:loading wire:target="submit" class="animate-spin h-5 w-5 text-current ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </button>

        @if(!Auth::check())
            <p class="text-xs text-center text-gray-400 mt-2">
                * Tus datos se guardarán automáticamente mientras inicias sesión.
            </p>
        @endif
    </form>
</div>