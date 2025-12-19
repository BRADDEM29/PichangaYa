<div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\contact-form.blade.php --}}
    
    {{-- 1. ÉXITO --}}
    @if ($successMessage)
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/40 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl font-bold flex items-center gap-3">
            <span>✅</span>
            {{ $successMessage }}
        </div>
    @endif

    {{-- 2. BLOQUEO --}}
    @if (!$canSend && !$successMessage)
        <div class="mb-6 p-6 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 text-amber-700 dark:text-amber-400 rounded-r-xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xl">⏳</span>
                <p class="font-bold text-lg">Límite de envío alcanzado</p>
            </div>
            <p class="text-sm">Hola **{{ Auth::user()->name }}**, ya recibimos una consulta tuya en las últimas 24 horas. Para darte una mejor atención, por favor espera a que procesemos la anterior.</p>
        </div>
    @endif

    {{-- 3. FORMULARIO --}}
    @if ($canSend)
        <form wire:submit.prevent="submit" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                    <input type="text" wire:model="name" readonly class="w-full rounded-xl border-gray-300 bg-gray-50 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tu Correo</label>
                    <input type="email" wire:model="email" readonly class="w-full rounded-xl border-gray-300 bg-gray-50 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed font-medium">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Celular de Contacto</label>
                <input type="text" wire:model="phone" placeholder="Ej: 987654321" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-700 focus:ring-green-500 focus:border-green-500">
                @error('phone') <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">¿Cuál es el motivo?</label>
                <input type="text" wire:model="subject" placeholder="Ej: Duda sobre mi reserva..." class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-700 focus:ring-green-500 focus:border-green-500">
                @error('subject') <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            {{-- 🟢 LÓGICA DE 200 PALABRAS CON ALPINE.JS --}}
            <div x-data="{ 
                content: @entangle('message'), 
                limit: 200, 
                get count() { 
                    return this.content ? this.content.trim().split(/\s+/).filter(w => w !== '').length : 0 
                },
                checkLimit() {
                    if (this.count > this.limit) {
                        // Cortar el texto a las primeras 200 palabras
                        this.content = this.content.trim().split(/\s+/).slice(0, this.limit).join(' ');
                    }
                }
            }">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex justify-between">
                    <span>Tu Mensaje</span>
                    {{-- Contador visual --}}
                    <span class="text-xs" :class="count >= limit ? 'text-red-500 font-black' : 'text-gray-400 font-normal'">
                        <span x-text="count"></span> / <span x-text="limit"></span> palabras
                    </span>
                </label>
                
                <textarea 
                    wire:model.lazy="message" 
                    x-model="content"
                    @input="checkLimit()"
                    rows="4" 
                    class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-700 focus:ring-green-500 focus:border-green-500" 
                    placeholder="Escribe aquí los detalles... (Máximo 200 palabras)">
                </textarea>
                
                @error('message') <span class="text-red-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                
                <div x-show="count >= limit" class="text-red-500 text-xs mt-1 font-bold animate-pulse">
                    ⚠️ Has alcanzado el límite de palabras.
                </div>
            </div>

            <button type="submit" wire:loading.attr="disabled" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl transition shadow-lg flex justify-center items-center gap-3 transform hover:scale-[1.02]">
                <span wire:loading.remove wire:target="submit">Enviar Consulta Directa</span>
                <span wire:loading wire:target="submit">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Procesando...
                </span>
            </button>
        </form>
    @endif

    <div class="mt-4 text-center">
        <p class="text-xs text-gray-500 italic">* Máximo una consulta cada 24 horas.</p>
    </div>
</div>