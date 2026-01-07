{{-- 🟢 POLL DE 2 SEGUNDOS: Verifica el estado constantemente --}}
<div wire:poll.2s="checkReservaStatus" class="relative">

    {{-- 🛑 SCRIPT DE SEGURIDAD: Escucha la orden de expulsión del PHP --}}
    @script
    <script>
        $wire.on('force-redirect', (event) => {
            // Obtener URL (dependiendo de la versión de Livewire puede ser event.url o event[0].url)
            let destination = event.url || event[0].url;
            // Forzar navegación
            window.location.href = destination;
        });
    </script>
    @endscript

    {{-- 🛑 BLOQUEO VISUAL: Si Livewire detecta el cambio, tapamos la pantalla inmediatamente --}}
    @if(!in_array($reserva->status, ['pending', 'advance', 'advance_paid', 'confirmed', 'paid', 'fully_paid']))
        <div class="fixed inset-0 z-[100] bg-gray-900 bg-opacity-90 flex flex-col items-center justify-center backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white p-8 rounded-2xl shadow-2xl text-center max-w-md mx-4 transform scale-100">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Edición Bloqueada</h2>
                <p class="text-gray-600 mb-4">El estado de la reserva ha cambiado. Ya no se puede editar.</p>
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                </div>
                <p class="text-sm text-gray-400 mt-4">Redirigiendo...</p>
            </div>
        </div>
    @endif

    {{-- CONTENEDOR INTERNO CON ESTILOS ORIGINALES --}}
    <div class="bg-white relative p-1 rounded-xl">
        <style>
            .custom-scrollbar::-webkit-scrollbar { height: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        </style>

        {{-- MENSAJE DE ÉXITO --}}
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        
        @if (session()->has('error'))
             <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
                <span class="font-medium">{{ session('error') }}</span>
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

        {{-- 2. TIMELINE DE HORARIOS --}}
        <div class="mb-4">
            <div class="flex justify-between items-end mb-3 px-1">
                <h3 class="text-sm font-bold text-gray-800">Modificar horario</h3>
                
                {{-- LEYENDA --}}
                <div class="flex gap-2 text-[10px] uppercase font-bold text-gray-500 flex-wrap justify-end">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-white border border-gray-300 rounded-full"></span> Libre</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-yellow-400 rounded-full border border-yellow-500"></span> Espera</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-blue-100 border border-blue-500 rounded-full"></span> Mis Reservas</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-gray-300 rounded-full"></span> Ocupado</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Selección</span>
                </div>
            </div>

            <div class="relative w-full overflow-x-auto pb-4 custom-scrollbar">
                @if(empty($timeSlots))
                    <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-gray-400 flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="block text-sm font-medium">No hay horarios disponibles para cambiar.</span>
                    </div>
                @else
                    <div class="inline-flex min-w-full gap-1">
                        @foreach($timeSlots as $slot)
                            @php
                                // --- EXTRACCIÓN DE DATOS ---
                                $isOccupied  = $slot['is_occupied'] ?? false;
                                $isPending   = $slot['is_pending'] ?? false;
                                $isMyBooking = $slot['is_my_booking'] ?? false; 
                                $disabled    = $slot['disabled'] ?? false;
                                
                                // --- LÓGICA VISUAL (Rango seleccionado) ---
                                $isInRange = false;
                                if ($time) {
                                    $slotTime = \Carbon\Carbon::parse($slot['value']);
                                    $selectedStart = \Carbon\Carbon::parse($time);
                                    $currentDuration = (int) ($duration ?? 1); 
                                    $selectedEnd = $selectedStart->copy()->addHours($currentDuration);

                                    if ($slotTime->gte($selectedStart) && $slotTime->lt($selectedEnd)) {
                                        $isInRange = true;
                                    }
                                }

                                // --- 🔵 PRIORIDAD DE ESTILOS CSS ---
                                if ($isInRange) {
                                    // 1. VERDE (Seleccionando activamente)
                                    $bgClass = 'bg-green-500 text-white shadow-md transform -translate-y-1 z-10 ring-2 ring-green-300 ring-offset-1';
                                    $borderClass = 'border-none';
                                    $icon = 'check';
                                } elseif ($isMyBooking) {
                                    // 2. 🔵 AZUL (Tus reservas confirmadas)
                                    $bgClass = 'bg-blue-50 text-blue-700 shadow-sm z-0'; 
                                    $borderClass = 'border border-blue-300';
                                    $icon = 'mine';
                                } elseif ($isPending) {
                                    // 3. AMARILLO (Pendiente)
                                    $bgClass = 'bg-yellow-50 text-yellow-600 cursor-not-allowed';
                                    $borderClass = 'border border-yellow-300';
                                    $icon = 'pending';
                                } elseif ($isOccupied) {
                                    // 4. GRIS (Ocupado por otros)
                                    $bgClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                                    $borderClass = 'border border-gray-200';
                                    $icon = 'lock';
                                } elseif ($disabled) {
                                    // 5. GRIS CLARO (Pasado)
                                    $bgClass = 'bg-gray-50 text-gray-300 cursor-not-allowed opacity-60';
                                    $borderClass = 'border border-gray-100';
                                    $icon = 'none';
                                } else {
                                    // 6. BLANCO (Libre)
                                    $bgClass = 'bg-white text-gray-700 hover:border-green-400 hover:text-green-600 cursor-pointer hover:shadow-sm';
                                    $borderClass = 'border border-gray-200';
                                    $icon = 'free';
                                }
                            @endphp

                            <div 
                                @if(!$disabled && !$isPending && (!$isOccupied || $isMyBooking)) 
                                    @if(!$isMyBooking && !$isOccupied)
                                        wire:click="$set('time', '{{ $slot['value'] }}')" 
                                    @endif
                                @endif
                                class="flex flex-col items-center justify-center w-14 h-20 flex-shrink-0 rounded-lg transition-all duration-200 {{ $bgClass }} {{ $borderClass }} select-none relative"
                            >
                                {{-- Icono flotante para pendientes --}}
                                @if($isPending)
                                    <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                @endif

                                <span class="text-xs font-bold">{{ $slot['value'] }}</span>
                                
                                {{-- ICONOGRAFÍA SVG --}}
                                @if($icon === 'check')
                                    <svg class="w-4 h-4 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                
                                @elseif($icon === 'mine') 
                                    {{-- 🔵 ICONO AZUL: Usuario/Check --}}
                                    <svg class="w-4 h-4 mt-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>

                                @elseif($icon === 'pending')
                                    <svg class="w-4 h-4 mt-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>

                                @elseif($icon === 'lock')
                                    <svg class="w-3 h-3 mt-1 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                
                                @elseif($icon === 'free')
                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mt-2 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @error('time') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
        </div>

        {{-- 3. BARRA DE CONFIRMACIÓN (ACTUALIZAR) --}}
        @if($time)
            <div class="mt-4 border-t border-dashed border-gray-200 pt-4 animate-in slide-in-from-bottom-2 fade-in duration-300">
                
                <div class="flex items-center justify-between mb-5 px-1 gap-4">
                    
                    {{-- PRECIO TOTAL --}}
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-extrabold tracking-wider">Total a pagar</span>
                        <span class="text-3xl font-black text-indigo-600 tracking-tight leading-none">S/ {{ number_format($total_price, 2) }}</span>
                    </div>

                    {{-- SELECTOR DE DURACIÓN --}}
                    <div class="flex items-center bg-gray-100 rounded-xl p-1 border border-gray-200">
                        <div class="px-2 text-indigo-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="relative">
                            <select wire:model.live="duration" class="appearance-none bg-white text-gray-800 font-bold text-lg py-2 pl-3 pr-8 rounded-lg border-none focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer w-28 shadow-sm">
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} Horas</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Lógica estricta para el botón --}}
                @php
                    // El botón solo vive si el estado es estrictamente 'pending'.
                    // Si cambia a 'confirmed', 'cancelled', etc., esto será false.
                    $isEditable = $reserva->status === 'pending'; 
                @endphp

                <button 
                    {{-- 1. Evitar que dispare la función si no es editable --}}
                    @if($isEditable) wire:click="update" @endif
                    
                    {{-- 2. Deshabilitar el atributo HTML --}}
                    @disabled(!$isEditable)
                    
                    class="w-full text-lg font-bold py-3.5 px-6 rounded-xl transition duration-200 shadow-lg flex justify-center items-center gap-2 transform 
                    {{-- 3. Cambio de clases en tiempo real (Estilos Visuales) --}}
                    @if($isEditable)
                        bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-200 active:scale-[0.98] cursor-pointer
                    @else
                        bg-gray-400 text-gray-200 cursor-not-allowed shadow-none opacity-70
                    @endif"
                    
                    wire:loading.attr="disabled">

                    <div wire:loading.remove class="flex items-center gap-2">
                        @if($isEditable)
                            <span>Actualizar Reserva</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        @else
                            <span>Edición Bloqueada</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        @endif
                    </div>

                    <div wire:loading.flex class="items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="whitespace-nowrap">Procesando...</span>
                    </div>
                </button>

                @if(!$isEditable)
                    <p class="text-center text-xs text-red-500 mt-2 font-bold animate-pulse">
                        El estado de la reserva ha cambiado a "{{ $reserva->status }}". No se puede editar.
                    </p>
                @endif
            </div>
        @else
            <div class="text-center py-6 text-gray-400 text-xs bg-gray-50 rounded-xl border border-gray-100 flex justify-center gap-2 items-center">
                 <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                 Selecciona el nuevo horario para actualizar
            </div>
        @endif
    </div>
</div>