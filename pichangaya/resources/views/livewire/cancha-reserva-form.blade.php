<div class="bg-white rounded-lg">
    
    {{-- Mensajes de Feedback --}}
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        
        {{-- 1. SELECCIÓN DE FECHA --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Fecha de Reserva</label>
            <input type="date" wire:model.live="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="{{ date('Y-m-d') }}" required>
            @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- 2. SELECCIÓN DE HORA (DINÁMICO) --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Hora de Inicio</label>
            <select wire:model.live="time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">Seleccione una hora...</option>
                
                @foreach($timeSlots as $slot)
                    <option value="{{ $slot['value'] }}" 
                            @if($slot['disabled']) disabled class="bg-gray-100 text-gray-400" @endif>
                        {{ $slot['label'] }} 
                        {{-- 🟢 CORRECCIÓN AQUÍ: Usamos 'is_occupied' en lugar de 'occupied' --}}
                        @if($slot['is_occupied']) (Ocupado) 🔴 
                        @elseif($slot['disabled']) (Cerrado/Pasado) ⚪
                        @else (Disponible) 🟢 
                        @endif
                    </option>
                @endforeach

            </select>
            
            {{-- Mensaje si no hay horas --}}
            @if(empty($timeSlots))
                <p class="text-xs text-orange-500 mt-1">⚠️ No hay horarios disponibles para esta fecha.</p>
            @endif

            @error('time') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            @error('availability') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        {{-- 3. DURACIÓN --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Duración (Horas)</label>
            <select wire:model.live="duration" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="1">1 Hora</option>
                <option value="2">2 Horas</option>
                <option value="3">3 Horas</option>
            </select>
            @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- 4. TOTAL Y BOTÓN --}}
        <div class="mt-6 pt-4 border-t border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600 font-medium">Total a Pagar:</span>
                <span class="text-2xl font-bold text-indigo-600">S/ {{ number_format($total_price, 2) }}</span>
            </div>

            <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md flex justify-center items-center"
                wire:loading.attr="disabled">
                
                <span wire:loading.remove>Confirmar Reserva</span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Procesando...
                </span>
            </button>
        </div>

    </form>
</div>