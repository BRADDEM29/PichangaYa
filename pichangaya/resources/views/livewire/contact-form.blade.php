<div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
    
    {{-- 1. ÉXITO: Solo si existe el mensaje --}}
    @if ($successMessage)
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/40 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl font-bold flex items-center gap-3">
            <span>✅</span>
            {{ $successMessage }}
        </div>
    @endif

    {{-- 2. BLOQUEO: Solo si canSend es falso Y NO acabamos de enviar con éxito --}}
    @if (!$canSend && !$successMessage)
        <div class="mb-6 p-6 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 text-amber-700 dark:text-amber-400 rounded-r-xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xl">⏳</span>
                <p class="font-bold text-lg">Límite de envío alcanzado</p>
            </div>
            <p class="text-sm">Hola **{{ Auth::user()->name }}**, ya recibimos una consulta tuya en las últimas 24 horas. Para darte una mejor atención, por favor espera a que procesemos la anterior.</p>
        </div>
    @endif

    {{-- 3. FORMULARIO: Solo visible si puede enviar --}}
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
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">¿Cuál es el motivo?</label>
                <select wire:model="subject" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:text-white focus:ring-green-500">
                    <option value="">Selecciona una opción</option>
                    <option value="Problema con una Reserva">⚠️ Problema con una Reserva</option>
                    <option value="Duda sobre Pagos">💰 Duda sobre Pagos</option>
                    <option value="Error en la aplicación">💻 Error en la aplicación</option>
                    <option value="Información para Dueños">🏟️ Información para Dueños</option>
                    <option value="Otros">📩 Otros</option>
                </select>
                @error('subject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tu Mensaje</label>
                <textarea wire:model="message" rows="4" class="w-full rounded-xl border-gray-300 dark:bg-gray-800 dark:text-white focus:ring-green-500" placeholder="Escribe aquí..."></textarea>
                @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" class="w-full bg-green-600 hover:bg-green-500 text-white font-black py-4 rounded-xl transition shadow-lg flex justify-center items-center gap-3">
                <span wire:loading.remove wire:target="submit">Enviar Consulta Directa</span>
                <span wire:loading wire:target="submit">Procesando...</span>
            </button>
        </form>
    @endif

    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800 text-center">
        <p class="text-xs text-gray-500 italic">* Máximo una consulta cada 24 horas.</p>
    </div>
</div>