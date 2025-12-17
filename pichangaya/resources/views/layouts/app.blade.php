<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PichangaYa'))</title>
    @stack('meta')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
        if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        .dark .bg-white { background-color: #111827 !important; color: #ffffff !important; }
        .dark .text-gray-900 { color: #d1d5db !important; }
        .dark input, .dark textarea, .dark select { background-color: #030712 !important; color: #fff !important; border-color: #374151 !important; }

        /* --- MEJORA: Animación Swing --- */
        @keyframes swing {
            0%, 100% { transform: rotate(0deg); }
            20% { transform: rotate(15deg); }
            40% { transform: rotate(-10deg); }
            60% { transform: rotate(5deg); }
            80% { transform: rotate(-5deg); }
        }
        .animate-swing {
            animation: swing 2s infinite ease-in-out;
            transform-origin: top center;
            display: inline-block; /* Necesario para que rote correctamente */
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 dark:text-white transition-colors duration-300"
      x-data="{ isLoading: true }"
      x-init="window.addEventListener('load', () => { setTimeout(() => isLoading = false, 1000) })">

    {{-- 1. LÓGICA MEJORADA: Buscar CUALQUIER reserva reciente (no solo pendientes) --}}
    @php
        $recentReserva = null;
        if(auth()->check()) {
            // Buscamos la última reserva creada en los últimos 12 minutos (damos 2 min extra de gracia visual)
            $recentReserva = \App\Models\Reserva::where('user_id', auth()->id())
                ->where('created_at', '>', now()->subMinutes(12)) 
                ->with('cancha')
                ->latest()
                ->first();
        }
    @endphp

    <x-loader />
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        @livewire('navigation-menu')

        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow border-b dark:border-gray-700 transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    @livewireScripts

    {{-- ============================================================== --}}
    {{-- 🟢 SISTEMA INTELIGENTE DE NOTIFICACIONES (Área Fija) --}}
    {{-- ============================================================== --}}
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">

        {{-- A. Notificaciones Flash (Standard) --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="pointer-events-auto bg-white dark:bg-gray-800 border-l-4 border-green-500 shadow-lg rounded-lg p-4 flex items-start animate-bounce-in-right">
                <div class="flex-shrink-0"><svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Operación Exitosa</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-500">✕</button>
            </div>
        @endif

        {{-- B. TARJETA DE ESTADO DE RESERVA RECIENTE --}}
        @if ($recentReserva)
            
            {{-- CASO 1: PENDIENTE (Muestra Temporizador) --}}
            @if ($recentReserva->status === 'pending')
                <div x-data="{ 
                        expiry: {{ $recentReserva->created_at->addMinutes(10)->timestamp }},
                        canchaName: '{{ $recentReserva->cancha->name ?? 'Cancha' }}',
                        timeLeft: '...',
                        progress: 100,
                        isExpired: false,
                        initTimer() {
                            const totalDuration = 600; 
                            const update = () => {
                                const now = Math.floor(Date.now() / 1000);
                                const diff = this.expiry - now;
                                if (diff <= 0) {
                                    this.timeLeft = 'EXPIRADO';
                                    this.isExpired = true;
                                    this.progress = 0;
                                    // Opcional: location.reload(); para actualizar estado visualmente
                                } else {
                                    const m = Math.floor(diff / 60);
                                    const s = diff % 60;
                                    this.timeLeft = `${m}:${s < 10 ? '0' : ''}${s}`;
                                    this.progress = (diff / totalDuration) * 100;
                                }
                            };
                            setInterval(update, 1000);
                            update();
                        }
                     }" 
                     x-init="initTimer()"
                     x-show="!isExpired"
                     class="pointer-events-auto bg-white dark:bg-gray-800 shadow-2xl rounded-lg overflow-hidden border-t-4 border-yellow-500 ring-1 ring-black ring-opacity-5">
                    
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-1">
                                <span class="flex h-10 w-10 relative justify-center items-center">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                    {{-- AQUI SE APLICA LA ANIMACIÓN SWING --}}
                                    <span class="relative text-2xl animate-swing">⏳</span>
                                </span>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Reserva Pendiente</h3>
                                <div class="mt-2 text-xs text-gray-600 dark:text-gray-300 leading-snug">
                                    <p>Para confirmar <span class="font-bold text-gray-800 dark:text-white" x-text="canchaName"></span>:</p>
                                    <p class="mt-1">Realiza el <span class="font-bold text-yellow-600 dark:text-yellow-400">Pago de Adelanto</span> o <span class="font-bold text-green-600 dark:text-green-400">Completo</span>.</p>
                                    <p class="mt-1 text-red-500 dark:text-red-400 font-medium">Si llega a 00:00, se cancelará automáticamente.</p>
                                </div>
                                <div class="mt-3 flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded px-2 py-1">
                                    <span class="text-xs font-semibold text-gray-500 uppercase">Tiempo:</span>
                                    <span class="text-xl font-black font-mono text-red-600 dark:text-red-400" x-text="timeLeft"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('reservas.user.index') }}" class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 rounded text-sm font-bold shadow-sm transition transform hover:scale-[1.02]">
                                Ir a Pagar Ahora 💳
                            </a>
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-full transition-all duration-1000 ease-linear" :style="`width: ${progress}%`" :class="{'bg-green-500': progress > 50, 'bg-yellow-500': progress <= 50, 'bg-red-600': progress <= 20}"></div>
                    </div>
                </div>

            {{-- CASO 2: PAGADO / CONFIRMADO (Muestra Confirmación Verde) --}}
            @elseif ($recentReserva->status === 'advance_paid' || $recentReserva->status === 'fully_paid')
                <div x-data="{ show: true }" x-show="show" 
                     class="pointer-events-auto bg-white dark:bg-gray-800 shadow-2xl rounded-lg overflow-hidden border-t-4 border-green-500 ring-1 ring-black ring-opacity-5">
                    <div class="p-4 flex items-start">
                        <div class="flex-shrink-0">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900 text-2xl">✅</span>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <h3 class="text-sm font-bold text-green-700 dark:text-green-400">¡Pago Confirmado!</h3>
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                Tu reserva para <span class="font-bold">{{ $recentReserva->cancha->name }}</span> ha sido asegurada correctamente.
                            </p>
                            <p class="mt-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                El temporizador se ha detenido.
                            </p>
                        </div>
                        <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-500">✕</button>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 px-4 py-2">
                        <a href="{{ route('reservas.user.index') }}" class="text-xs font-bold text-green-700 dark:text-green-400 hover:underline">Ver mis reservas &rarr;</a>
                    </div>
                </div>

            {{-- CASO 3: CANCELADO (Muestra Error Rojo) --}}
            @elseif ($recentReserva->status === 'cancelled')
                <div x-data="{ show: true }" x-show="show" 
                     class="pointer-events-auto bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border-l-4 border-red-500 ring-1 ring-black ring-opacity-5">
                    <div class="p-4 flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <h3 class="text-sm font-bold text-red-600">Reserva Cancelada</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                El tiempo expiró o la reserva fue cancelada.
                            </p>
                        </div>
                        <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-500">✕</button>
                    </div>
                </div>
            @endif

        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (href && href.startsWith('/') && !href.startsWith('#') && !link.target) {
                        try { document.querySelector('body').__x.$data.isLoading = true; } catch(e){}
                    }
                });
            });
        });
    </script>
</body>
</html>