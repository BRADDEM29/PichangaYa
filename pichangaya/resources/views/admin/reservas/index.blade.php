<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\reservas\index.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Reservas - Administrador') }}
        </h2>
    </x-slot>

    {{-- Estilos para el reloj digital --}}
    <style>
        .font-digital {
            font-family: 'Courier New', Courier, monospace;
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Botón Volver --}}
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="text-indigo-600 font-bold hover:underline">
                    &larr; Volver a la lista de canchas
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-700">
                        Reservas para: <span class="text-indigo-600">{{ $cancha->name }}</span>
                    </h3>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($reservas->isEmpty())
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <p class="text-lg text-gray-500 font-medium">Esta cancha no tiene reservas registradas aún.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Registrado el</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tiempo Restante</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Horario Cancha</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado de Pago</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            
                            {{-- 🟢 CUERPO DE TABLA CON ID PARA AJAX --}}
                            <tbody id="reservas-table-body" class="bg-white divide-y divide-gray-200">
                                @include('admin.reservas.partials.table-body')
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $reservas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 🟢 SCRIPT HÍBRIDO: RELOJ + AUTO RECARGA (CORREGIDO) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. FUNCIÓN PARA ACTUALIZAR TEMPORIZADORES (VISUAL)
            function updateAdminTimers() {
                const timerElements = document.querySelectorAll('.admin-timer');
                const now = new Date().getTime();
                
                timerElements.forEach(el => {
                    const expiry = parseInt(el.getAttribute('data-expiry'));
                    const distance = expiry - now;
                    
                    if (distance < 0) {
                        el.innerHTML = "EXPIRADO";
                        el.classList.remove('text-red-600');
                        el.classList.add('text-gray-500'); 
                        return;
                    }
                    
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    el.innerHTML = `${minutes < 10 ? '0'+minutes : minutes}:${seconds < 10 ? '0'+seconds : seconds}`;
                });
            }

            // 2. FUNCIÓN PARA RECARGAR DATOS DEL SERVIDOR (AJAX)
            function pollTableData() {
                // 🛑 CORRECCIÓN AQUÍ: Usamos $cancha directo, no $cancha->id
                // Esto asegura que si tu sistema usa Slugs, la URL se genere bien.
                fetch("{{ route('admin.canchas.reservas.polling', $cancha) }}")
                    .then(response => {
                        if (!response.ok) { throw new Error('Network response was not ok'); }
                        return response.text();
                    })
                    .then(html => {
                        document.getElementById('reservas-table-body').innerHTML = html;
                        // Reiniciamos los timers después de actualizar el HTML
                        updateAdminTimers();
                    })
                    .catch(error => console.error('Error polling table:', error));
            }

            // Ejecutar timers cada segundo
            setInterval(updateAdminTimers, 1000);
            updateAdminTimers(); 

            // Ejecutar recarga de datos cada 10 segundos
            setInterval(pollTableData, 10000);
        });
    </script>
</x-app-layout>