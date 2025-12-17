<div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
    @if ($successMessage)
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg font-bold flex items-center gap-2">
            ✅ {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                <input type="text" wire:model="name" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Correo Electrónico</label>
                <input type="email" wire:model="email" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">¿En qué podemos ayudarte hoy?</label>
            <select wire:model="subject" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500">
                <option value="">Selecciona una opción</option>
                <option value="Problema con una Reserva">Problema con una Reserva</option>
                <option value="Duda sobre Pagos">Duda sobre Pagos</option>
                <option value="Error en la aplicación">Error en la aplicación</option>
                <option value="Otros">Otros</option>
            </select>
            @error('subject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tu Mensaje</label>
            <textarea wire:model="message" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500" placeholder="Describe tu problema con detalle..."></textarea>
            @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-black py-4 rounded-xl transition shadow-lg shadow-green-500/30 flex justify-center items-center gap-2">
            <span wire:loading.remove>Enviar Consulta</span>
            <span wire:loading>Enviando...</span>
        </button>
    </form>
</div>