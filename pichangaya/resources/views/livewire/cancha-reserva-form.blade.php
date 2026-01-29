<div class="bg-white relative" wire:poll.7s="refreshSlots">
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>

    {{-- ALERTAS DE SESIÓN --}}
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

    {{-- 2. TIMELINE HORIZONTAL --}}
    <div class="mb-4">
        <div class="flex justify-between items-end mb-3 px-1">
            <h3 class="text-sm font-bold text-gray-800">
                {{ $isTournamentMode ? 'Horario del Torneo' : 'Selecciona tu horario' }}
            </h3>
            
            {{-- LEYENDA SIMPLIFICADA (SIN TORNEO) --}}
            <div class="flex gap-2 text-[10px] uppercase font-bold text-gray-500 flex-wrap justify-end">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-white border border-gray-300 rounded-full"></span> Libre</span>
                
                @if($isTournamentMode)
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-purple-600 rounded-full shadow shadow-purple-200"></span> Selección</span>
                @else
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-yellow-400 rounded-full border border-yellow-500"></span> Espera</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> Tuyo</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-green-500 rounded-full shadow-sm"></span> Tu Selección</span>
                @endif
                
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-gray-300 rounded-full"></span> Ocupado</span>
            </div>
        </div>

        <div class="relative w-full overflow-x-auto pb-4 custom-scrollbar">
            @if(empty($timeSlots))
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-gray-400">
                    No hay horarios disponibles.
                </div>
            @else
                <div class="inline-flex min-w-full gap-2 px-1">
                    @foreach($timeSlots as $slot)
                        @php
                            $isOccupied  = $slot['is_occupied'];
                            $isPending   = $slot['is_pending'] ?? false;
                            $isMyBooking = $slot['is_my_booking'] ?? false; 
                            $disabled    = $slot['disabled'];
                            
                            // Lógica de Selección (Rango)
                            $isSelected = false;
                            if ($time) {
                                $slotTime = \Carbon\Carbon::parse($date . ' ' . $slot['value']);
                                if($slotTime->lt(\Carbon\Carbon::parse($date . ' ' . $cancha->open_time))) {
                                    $slotTime->addDay();
                                }
                                
                                $selectedStart = \Carbon\Carbon::parse($date . ' ' . $time);
                                if($selectedStart->lt(\Carbon\Carbon::parse($date . ' ' . $cancha->open_time))) {
                                    $selectedStart->addDay();
                                }

                                $selectedEnd = $selectedStart->copy()->addHours($duration);

                                if ($slotTime->gte($selectedStart) && $slotTime->lt($selectedEnd)) {
                                    $isSelected = true;
                                }
                            }

                            // 🎨 ESTILOS (Sin morado en casillas ocupadas)
                            
                            // 1. Selección Activa
                            if ($isSelected) {
                                if ($isTournamentMode) {
                                    $bgClass = 'bg-purple-600 text-white shadow-md ring-2 ring-purple-300 transform -translate-y-1';
                                    $borderClass = 'border-purple-600';
                                } else {
                                    $bgClass = 'bg-green-500 text-white shadow-md ring-2 ring-green-300 transform -translate-y-1';
                                    $borderClass = 'border-green-500';
                                }
                            } 
                            // 2. Mi Reserva
                            elseif ($isMyBooking) {
                                $bgClass = 'bg-blue-500 text-white shadow-md cursor-pointer'; 
                                $borderClass = 'border border-blue-600';
                            } 
                            // 3. Pendiente
                            elseif ($isPending) {
                                $bgClass = 'bg-yellow-400 text-white cursor-not-allowed shadow-sm';
                                $borderClass = 'border border-yellow-500';
                            } 
                            // 4. Ocupado (Aquí caen los torneos ajenos o propios si no entra en isMyBooking)
                            elseif ($isOccupied) {
                                $bgClass = 'bg-gray-300 text-gray-500 cursor-not-allowed';
                                $borderClass = 'border border-gray-300';
                            } 
                            // 5. Deshabilitado
                            elseif ($disabled) {
                                $bgClass = 'bg-gray-100 text-gray-300 cursor-not-allowed opacity-50';
                                $borderClass = 'border border-gray-100';
                            } 
                            // 6. Libre
                            else {
                                $bgClass = 'bg-white text-gray-600 hover:border-green-400 hover:text-green-600 cursor-pointer hover:shadow-md';
                                $borderClass = 'border border-gray-200';
                            }
                        @endphp

                        <button 
                            type="button"
                            @if((!$disabled || $isMyBooking) && !$isOccupied) wire:click="selectTimeSlot('{{ $slot['value'] }}')" @endif
                            class="flex flex-col items-center justify-center w-24 h-20 flex-shrink-0 rounded-xl transition-all duration-200 {{ $bgClass }} {{ $borderClass }} select-none relative group"
                        >
                            <div class="flex flex-col items-center leading-tight">
                                <span class="text-[11px] font-black uppercase tracking-tight">
                                    {{ $slot['label_start'] }}
                                </span>
                                <span class="text-[9px] opacity-70 font-bold uppercase mt-0.5">
                                    hasta
                                </span>
                                <span class="text-[11px] font-black uppercase tracking-tight">
                                    {{ $slot['label_end'] }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($isTournamentMode)
             {{-- Botón en formulario padre --}}
        @else
             @error('time') <p class="text-red-500 text-xs mt-1 font-bold bg-red-50 p-1 rounded border border-red-200 flex items-center gap-1"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
        @endif
    </div>

    {{-- 3. SECCIÓN DE CONFIRMACIÓN --}}
    @if(!$isTournamentMode && $time)
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

            @auth
                @php
                    $user = auth()->user();
                    $isVerified = $user->role === 'admin' || ($user->hasVerifiedEmail() || !empty($user->phone));
                @endphp

                @if($isVerified)
                    <button wire:click="save" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold py-3.5 px-6 rounded-xl transition duration-200 shadow-lg shadow-indigo-200 flex justify-center items-center gap-2 transform active:scale-[0.98]"
                        wire:loading.attr="disabled">
                        <div wire:loading.remove class="flex items-center gap-2">
                            <span>{{ $this->reservaEdicion ? 'Actualizar Reserva' : 'Confirmar Reserva' }}</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                        <div wire:loading.flex class="items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="whitespace-nowrap">Procesando...</span>
                        </div>
                    </button>
                @else
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-amber-800">¡Faltan datos de contacto!</p>
                                <p class="text-xs text-amber-700 mt-1">
                                    Para reservar, necesitamos que confirmes tu 
                                    <span class="font-bold">email</span> o registres tu <span class="font-bold">celular</span>.
                                </p>
                                <a href="{{ route('profile.show') }}" class="inline-block mt-2 text-xs font-black text-indigo-600 underline">
                                    Completar mi perfil →
                                </a>
                            </div>
                        </div>
                        <button disabled class="w-full mt-3 bg-gray-300 text-gray-500 text-lg font-bold py-3 px-6 rounded-xl cursor-not-allowed">
                            Confirmar Reserva
                        </button>
                    </div>
                @endif
            @endauth
        </div>
    @else
        <div class="text-center py-6 text-gray-400 text-xs bg-gray-50 rounded-xl border border-gray-100">
            Toca una hora para comenzar la reserva
        </div>
    @endif
</div>