<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 leading-tight">
                {{ __('Mis Reservas') }}
            </h2>
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs font-bold px-3 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 rounded-full border border-indigo-200 dark:border-indigo-700 shadow-sm flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Historial
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-[#0f172a] min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- MENSAJES FLASH --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 mx-4 sm:mx-0 p-4 bg-green-500/10 border border-green-500/50 text-green-700 dark:text-green-400 rounded-xl flex items-center justify-between shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 mx-4 sm:mx-0 p-4 bg-red-500/10 border border-red-500/50 text-red-700 dark:text-red-400 rounded-xl flex items-center justify-between shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif

            {{-- CONTENEDOR PRINCIPAL --}}
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-2xl sm:rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                {{-- CABECERA DE LA TARJETA --}}
                <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900/50">
                    <div class="text-center sm:text-left">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center justify-center sm:justify-start gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Mis Partidos Programados
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestiona tus reservas y pagos pendientes</p>
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center px-6 py-2 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-500/30">
                        <span class="relative flex items-center gap-2">
                            Nueva Reserva
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </span>
                    </a>
                </div>

                @if ($reservas->isEmpty())
                    {{-- ESTADO VACÍO --}}
                    <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-700 dark:text-white mb-2">¡Aún no tienes partidos!</h4>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8">Es momento de reunir al equipo y pisar la cancha. Reserva tu primer partido ahora.</p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold hover:underline">
                             Ir al buscador de canchas 
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @else
                    
                    {{-- 🟢 VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50/50 dark:bg-gray-900/30">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cancha</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Solicitado el</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha Partido</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($reservas as $reserva)
                                    @php
                                        // CONFIGURACIÓN DE ESTADOS
                                        $statusConfig = [
                                            'pending'      => ['label' => 'Pendiente',       'color' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600', 'dot' => 'bg-gray-500'],
                                            'confirmed'    => ['label' => 'Confirmada',      'color' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800', 'dot' => 'bg-blue-500'],
                                            'advance_paid' => ['label' => 'Seña Pagada',     'color' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800', 'dot' => 'bg-amber-500'],
                                            'fully_paid'   => ['label' => 'Pagado',          'color' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800', 'dot' => 'bg-emerald-500'],
                                            'cancelled'    => ['label' => 'Cancelada',       'color' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800', 'dot' => 'bg-red-500'],
                                        ];

                                        $config = $statusConfig[$reserva->status] ?? $statusConfig['pending'];
                                        $statusClasses = $config['color'];
                                        $statusLabel   = $config['label'];
                                        $dotColor      = $config['dot'];

                                        // LÓGICA DE ACCIONES (CORE LOGIC)
                                        // Regla: Solo se puede editar/cancelar si es PENDING y fecha futura.
                                        // Si tiene CUALQUIER otro estado (pagado parcial, completo, cancelado), se bloquea.
                                        $isPending = $reserva->status === 'pending';
                                        $isPast    = $reserva->start_time <= now();

                                        $canAction = $isPending && !$isPast;
                                        
                                        $canEdit   = $canAction;
                                        $canCancel = $canAction;
                                    @endphp
                                    
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        {{-- CANCHA --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $reserva->cancha->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $reserva->cancha->sport ?? 'Fútbol' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- SOLICITADO EL --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                    {{ $reserva->created_at->format('d/m/Y') }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-500 pl-5">
                                                    {{ $reserva->created_at->format('h:i A') }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- FECHA PARTIDO --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-900 dark:text-gray-200 font-bold flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                    </svg>
                                                    {{ $reserva->start_time->format('d M, Y') }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 pl-5">
                                                    {{ $reserva->start_time->format('h:i A') }} - {{ $reserva->end_time->format('h:i A') }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- TOTAL --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">S/ {{ number_format($reserva->total_price, 2) }}</div>
                                        </td>

                                        {{-- ESTADO --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusClasses }}">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotColor }}"></span>
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        {{-- ACCIONES --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-3">
                                                
                                                @if ($canAction)
                                                    {{-- BOTÓN EDITAR (Solo Pending) --}}
                                                    <a href="{{ route('reservas.edit', $reserva) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition p-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/30" title="Editar Reserva">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </a>

                                                    {{-- BOTÓN CANCELAR (Solo Pending) --}}
                                                    <form action="{{ route('reservas.cancel', $reserva) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas cancelar? Esta acción es irreversible.');">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30" title="Cancelar Reserva">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- SI NO SE PUEDE EDITAR (Pagado o Cancelado) --}}
                                                    <span class="text-gray-300 dark:text-gray-600 cursor-not-allowed px-2 flex items-center justify-end" title="Acción no disponible">
                                                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                                        </svg>
                                                    </span>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- 🟢 VISTA MÓVIL (CARDS) --}}
                    <div class="md:hidden grid grid-cols-1 gap-4 p-4 bg-gray-50 dark:bg-black/20">
                        @foreach ($reservas as $reserva)
                            @php
                                $statusConfigMobile = [
                                    'pending'      => ['label' => 'Pendiente',       'color' => 'bg-gray-100 text-gray-700', 'side' => 'bg-gray-400'],
                                    'confirmed'    => ['label' => 'Confirmada',      'color' => 'bg-blue-100 text-blue-700', 'side' => 'bg-blue-500'],
                                    'advance_paid' => ['label' => 'Seña Pagada',     'color' => 'bg-amber-100 text-amber-700', 'side' => 'bg-amber-500'],
                                    'fully_paid'   => ['label' => 'Pagado',          'color' => 'bg-emerald-100 text-emerald-700', 'side' => 'bg-emerald-500'],
                                    'cancelled'    => ['label' => 'Cancelada',       'color' => 'bg-red-100 text-red-700', 'side' => 'bg-red-500'],
                                ];

                                $configMobile = $statusConfigMobile[$reserva->status] ?? $statusConfigMobile['pending'];
                                $statusClasses = $configMobile['color'];
                                $statusLabel   = $configMobile['label'];
                                $statusColorBg = $configMobile['side'];

                                $isPending = $reserva->status === 'pending';
                                $isPast    = $reserva->start_time <= now();
                                $canAction = $isPending && !$isPast;
                            @endphp

                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700 p-5 relative overflow-hidden">
                                {{-- Borde lateral indicador --}}
                                <div class="absolute top-0 left-0 h-full w-1.5 {{ $statusColorBg }}"></div>
                                
                                <div class="flex justify-between items-start mb-3 pl-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $reserva->cancha->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                            </svg>
                                            {{ $reserva->start_time->format('d M, Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        <span class="block font-extrabold text-emerald-600 dark:text-emerald-400 text-lg">S/ {{ number_format($reserva->total_price, 2) }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-2 pl-2">
                                    <svg class="w-4 h-4 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $reserva->start_time->format('h:i A') }} - {{ $reserva->end_time->format('h:i A') }}
                                </div>

                                <div class="flex items-center text-xs text-gray-400 dark:text-gray-500 mb-4 pl-2 border-t border-gray-100 dark:border-gray-700 pt-2 border-dashed">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    Solicitado: {{ $reserva->created_at->format('d/m/Y h:i A') }}
                                </div>

                                {{-- Botones Móvil (Solo si canAction es true) --}}
                                @if($canAction)
                                    <div class="flex gap-2 pl-2">
                                        <a href="{{ route('reservas.edit', $reserva) }}" class="flex-1 text-center py-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 rounded-lg text-sm font-bold hover:bg-indigo-100 transition flex items-center justify-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                                Editar
                                        </a>

                                        <form action="{{ route('reservas.cancel', $reserva) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Cancelar reserva?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="w-full py-2 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg text-sm font-bold hover:bg-red-100 transition flex items-center justify-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                </svg>
                                                Cancelar
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    {{-- Mensaje de bloqueo en móvil --}}
                                    <div class="mt-2 text-center text-xs text-gray-400 italic bg-gray-50 dark:bg-gray-700/30 py-2 rounded">
                                        No se pueden realizar cambios
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- PAGINACIÓN --}}
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        {{ $reservas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="relative z-10">
        <x-footer />
    </div>

</x-app-layout>