<form wire:submit.prevent="updateReserva" class="space-y-6">
    <div class="border-b pb-4 mb-4">
        <h4 class="text-2xl font-bold text-gray-900">Editar Reserva</h4>
        <p class="text-sm text-gray-500">Modifica la fecha o duración de tu partido.</p>
    </div>

    {{-- Feedback --}}
    @error('availability')
        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800">⚠️ {{ $message }}</div>
    @enderror
    
    {{-- FECHA --}}
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">📅 Fecha del Partido</label>
        <input type="date" wire:model.live="date" min="{{ \Carbon\Carbon::today()->toDateString() }}" 
               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-2">
    </div>

    {{-- HORARIO Y DURACIÓN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">⏰ Hora de Inicio</label>
            <div class="relative">
                <select wire:model.live="time" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-2 pl-3 pr-10">
                    @foreach($timeSlots as $slot)
                        <option value="{{ $slot['value'] }}" 
                                @if($slot['occupied']) disabled class="bg-gray-100 text-gray-400" @endif>
                            {{ $slot['label'] }} 
                            @if($slot['occupied']) (Ocupado) 🔴 @else (Disponible) 🟢 @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">⏳ Duración</label>
            <select wire:model.live="duration" 
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-2">
                @for ($i = 1; $i <= 4; $i++)
                    <option value="{{ $i }}">{{ $i }} Hora{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
        </div>
    </div>

    {{-- PRECIO --}}
    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex justify-between items-center shadow-sm">
        <div>
            <span class="block text-xs text-gray-500 uppercase font-bold tracking-wider">Nuevo Total</span>
            <span class="text-xs text-gray-400">Pago en local</span>
        </div>
        <div class="text-3xl font-black text-indigo-600">
            S/ {{ number_format($total_price, 2) }}
        </div>
    </div>

    {{-- BOTÓN --}}
    <button type="submit" 
            class="w-full bg-indigo-600 text-white font-bold py-4 rounded-lg hover:bg-indigo-700 transition shadow-lg flex justify-center items-center"
            wire:loading.attr="disabled">
        <span wire:loading.remove>Guardar Cambios 💾</span>
        <span wire:loading>Procesando...</span>
    </button>
    
    <div class="text-center">
        <a href="{{ route('reservas.user.index') }}" class="text-sm text-gray-500 hover:underline">Cancelar edición</a>
    </div>
</form>