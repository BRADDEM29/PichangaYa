<div class="bg-white relative">
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\livewire\cancha-reserva-form.blade.php --}}
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- 1. SELECCIÓN DE FECHA --}}
    <div class="flex items-center justify-between mb-6 bg-white border border-gray-200 p-2 rounded-xl shadow-sm hover:border-indigo-300 transition-colors">
        <div class="flex items-center px-2 text-indigo-600">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="font-bold text-sm uppercase tracking-wide">Fecha</span>
        </div>
        <input type="date" wire:model.live="date" class="border-none bg-transparent font-bold text-gray-800 focus:ring-0 cursor-pointer text-right outline-none" min="{{ date('Y-m-d') }}">
    </div>

    {{-- 2. TIMELINE HORIZONTAL DE HORARIOS --}}
    <div class="mb-4">
        <div class="flex justify-between items-end mb-3 px-1">
            <h3 class="text-sm font-bold text-gray-800">Selecciona tu horario</h3>
            {{-- LEYENDA (Sin cambios en diseño, solo agregando indicador amarillo) --}}
            <div class="flex gap-2 text-[10px] uppercase font-bold text-gray-500">
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-white border border-gray-300 rounded-full"></span> Libre</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-yellow-400 rounded-full border border-yellow-500"></span> Espera</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-gray-300 rounded-full"></span> Ocupado</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Tu Juego</span>
            </div>
        </div>

        <div class="relative w-full overflow-x-auto pb-4 custom-scrollbar">
            @if(empty($timeSlots))
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-gray-400">
                    <span class="block text-2xl mb-1">😴</span>
                    No hay horarios para hoy.
                </div>
            @else
                <div class="inline-flex min-w-full gap-1">
                    @foreach($timeSlots as $slot)
                        @php
                            // Capturamos los estados que vienen del componente Livewire
                            $isOccupied = $slot['is_occupied'];
                            $isPending  = $slot['is_pending'] ?? false; // 🟡 Captura amarillo
                            $disabled   = $slot['disabled'];
                            
                            // LÓGICA VISUAL DE RANGO SELECCIONADO (Tu lógica original intacta)
                            $isInRange = false;
                            if ($time) {
                                $slotTime = \Carbon\Carbon::parse($slot['value']);
                                $selectedStart = \Carbon\Carbon::parse($time);
                                $selectedEnd = $selectedStart->copy()->addHours($duration);

                                if ($slotTime->gte($selectedStart) && $slotTime->lt($selectedEnd)) {
                                    $isInRange = true;
                                }
                            }

                            // ASIGNACIÓN DE ESTILOS
                            if ($isOccupied) {
                                // GRIS: Ocupado confirmado
                                $bgClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                                $borderClass = 'border border-gray-200';
                            } elseif ($isPending) {
                                // 🟡 AMARILLO: Pendiente (10 min)
                                $bgClass = 'bg-yellow-50 text-yellow-600 cursor-not-allowed';
                                $borderClass = 'border border-yellow-300';
                            } elseif ($disabled) {
                                // GRIS: Pasado de tiempo (Tolerancia 20 min agotada)
                                $bgClass = 'bg-gray-100 text-gray-300 cursor-not-allowed opacity-60';
                                $borderClass = 'border border-gray-100';
                            } elseif ($isInRange) {
                                // VERDE: Seleccionado
                                $bgClass = 'bg-green-500 text-white shadow-md transform -translate-y-1 z-10 ring-2 ring-green-300 ring-offset-1';
                                $borderClass = 'border-none';
                            } else {
                                // BLANCO: Libre
                                $bgClass = 'bg-white text-gray-700 hover:border-green-400 hover:text-green-600 cursor-pointer';
                                $borderClass = 'border border-gray-200';
                            }
                        @endphp

                        <div 
                            @if(!$disabled) wire:click="selectTimeSlot('{{ $slot['value'] }}')" @endif
                            class="flex flex-col items-center justify-center w-14 h-20 flex-shrink-0 rounded-lg transition-all duration-200 {{ $bgClass }} {{ $borderClass }} select-none relative"
                        >
                            {{-- Icono pequeño para pendientes --}}
                            @if($isPending)
                                <span class="absolute top-1 right-1 text-[8px]">⏳</span>
                            @endif

                            <span class="text-xs font-bold">{{ $slot['value'] }}</span>
                            
                            @if($isInRange)
                                <svg class="w-4 h-4 mt-1 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif(!$disabled && !$isPending && !$isOccupied)
                                <div class="w-1.5 h-1.5 bg-green-400 rounded-full mt-2 opacity-0 group-hover:opacity-100"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @error('time') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
    </div>

    {{-- 3. BARRA DE CONFIRMACIÓN (Siempre visible si hay selección) --}}
    @if($time)
        <div class="mt-4 border-t border-dashed border-gray-200 pt-4 animate-in slide-in-from-bottom-2 fade-in duration-300">
            <div class="flex justify-between items-center mb-4 px-2">
                <div class="flex flex-col">
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total a Pagar</span>
                    <span class="text-3xl font-black text-indigo-600 tracking-tight">S/ {{ number_format($total_price, 2) }}</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400 block font-bold uppercase">Duración</span>
                    <span class="text-lg font-bold text-gray-800 bg-gray-100 px-3 py-1 rounded-lg">
                        {{ $duration }} Hora{{ $duration > 1 ? 's' : '' }}
                    </span>
                </div>
            </div>

            <button wire:click="save" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold py-3.5 px-6 rounded-xl transition duration-200 shadow-lg shadow-indigo-200 flex justify-center items-center gap-2 transform active:scale-[0.98]"
                wire:loading.attr="disabled">
                
                {{-- 1. Estado NORMAL (Visible cuando NO carga) --}}
                <div wire:loading.remove class="flex items-center gap-2">
                    <span>Confirmar Reserva</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
                
                {{-- 2. Estado CARGANDO (Visible SOLO cuando carga) --}}
                <div wire:loading.flex class="items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="whitespace-nowrap">Procesando...</span>
                </div>
            </button>
        </div>
    @else
        <div class="text-center py-6 text-gray-400 text-xs bg-gray-50 rounded-xl border border-gray-100">
            👆 Toca una hora para comenzar la reserva
        </div>
    @endif

</div>