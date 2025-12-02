<form wire:submit.prevent="submitReserva" class="space-y-6">
    <div class="border-b pb-4 mb-4">
        <h4 class="text-2xl font-bold text-gray-900">Reservar Cancha</h4>
        <p class="text-sm text-gray-500">Selecciona tu horario ideal.</p>
    </div>

    {{-- Feedback Mensajes --}}
    @if (session()->has('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700">✅ {{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700">❌ {{ session('error') }}</div>
    @endif
    @error('availability')
        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800">⚠️ {{ $message }}</div>
    @enderror
    
    {{-- 1. FECHA --}}
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">📅 Fecha del Partido</label>
        <input type="date" wire:model.live="date" min="{{ \Carbon\Carbon::today()->toDateString() }}" 
               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-2">
    </div>

    {{-- 2. HORARIO (DROPDOWN INTELIGENTE) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">⏰ Hora de Inicio</label>
            
            <div class="relative">
                <select wire:model.live="time" 
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg py-2 pl-3 pr-10
                               {{ $time == '' ? 'border-red-300 ring-red-200' : '' }}">
                    
                    @if(empty($timeSlots))
                        <option value="">Cargando horarios...</option>
                    @else
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot['value'] }}" 
                                    @if($slot['occupied']) disabled class="bg-gray-100 text-gray-400" @endif>
                                {{ $slot['label'] }} 
                                @if($slot['occupied']) (Ocupado) 🔴 @else (Disponible) 🟢 @endif
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            @error('time') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            
            @if($time == '')
                <p class="text-xs text-red-500 mt-1">⚠️ No hay horarios disponibles o todos están ocupados.</p>
            @endif
        </div>
        
        {{-- 3. DURACIÓN --}}
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

    {{-- RESUMEN DE PRECIO --}}
    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex justify-between items-center shadow-sm">
        <div>
            <span class="block text-xs text-gray-500 uppercase font-bold tracking-wider">Total a Pagar</span>
            <span class="text-xs text-gray-400">Pago en local</span>
        </div>
        <div class="text-3xl font-black text-indigo-600">
            S/ {{ number_format($total_price, 2) }}
        </div>
    </div>

    {{-- BOTÓN --}}
    @guest
        <a href="{{ route('login') }}" class="block w-full text-center bg-gray-800 text-white font-bold py-4 rounded-lg hover:bg-gray-700 transition shadow-lg">
            🔒 Inicia Sesión para Reservar
        </a>
    @else
        <button type="submit" 
                class="w-full bg-indigo-600 text-white font-bold py-4 rounded-lg hover:bg-indigo-700 transition shadow-lg flex justify-center items-center disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                @if($time == '') disabled @endif>
            
            <span wire:loading.remove>Confirmar Reserva ⚽</span>
            <span wire:loading>Procesando...</span>
        </button>
    @endguest
</form>