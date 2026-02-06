<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.25 2.25 0 11-4.5 0v3.375m7.5 0v2.25m-7.5 0v2.25" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gestión de Torneos
            </h2>
        </div>
    </x-slot>

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Botón Crear --}}
            <nav class="mb-6 flex justify-end">
                <a href="{{ route('admin.tournaments.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md flex items-center gap-2 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Nuevo Torneo
                </a>
            </nav>

            <section class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 dark:border-gray-700">
                @if($tournaments->isEmpty())
                    <article class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 class="text-lg font-semibold">No se han creado torneos aún</h3>
                        <p class="text-sm mt-1">Comienza creando uno nuevo con el botón de arriba.</p>
                    </article>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Horario</th> 
                                    <th scope="col" class="px-6 py-3">Equipos</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($tournaments as $t)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        
                                        {{-- Nombre --}}
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            {{ $t->name }}
                                        </td>
                                        
                                        {{-- Estado --}}
                                        <td class="px-6 py-4">
                                            @if($t->status === 'finished')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded border border-yellow-200 uppercase tracking-wide">Finalizado</span>
                                            @elseif($t->status === 'active')
                                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded border border-green-200 uppercase tracking-wide">Activo</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded border border-gray-200 uppercase tracking-wide">Pendiente</span>
                                            @endif
                                        </td>

                                        {{-- Fecha --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($t->start_date)->format('d/m/Y') }}
                                            </div>
                                        </td>

                                        {{-- 🟢 HORARIO 12H (AM/PM) --}}
                                        <td class="px-6 py-4">
                                            @php
                                                // Intentamos buscar la reserva asociada para saber la hora fin real
                                                $reserva = \App\Models\Reserva::where('cancha_id', $t->cancha_id)
                                                    ->where('start_time', $t->start_date)
                                                    ->first();
                                                
                                                // Usamos 'h:i A' para formato 12 horas (04:00 PM)
                                                $horaInicio = \Carbon\Carbon::parse($t->start_date)->format('h:i A');
                                                $horaFin = $reserva ? \Carbon\Carbon::parse($reserva->end_time)->format('h:i A') : '???';
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold font-mono">
                                                {{ $horaInicio }} - {{ $horaFin }}
                                            </span>
                                        </td>

                                        {{-- Conteo Equipos --}}
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                {{ $t->teams()->count() }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Link Público --}}
                                                <a href="{{ route('arena.show', $t->id) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors" title="Ver Link Público">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                    </svg>
                                                </a>

                                                <div class="h-4 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                                                {{-- Gestionar --}}
                                                <a href="{{ route('admin.tournaments.manage', $t->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg shadow-sm transition-all" title="Gestionar Partidos">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                                    </svg>
                                                </a>

                                                {{-- Editar --}}
                                                <a href="{{ route('admin.tournaments.edit', $t->id) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 dark:hover:bg-blue-900 p-2 rounded-lg border border-blue-200 dark:border-blue-800 transition-all" title="Editar Configuración">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                    </svg>
                                                </a>

                                                {{-- Eliminar --}}
                                                <form action="{{ route('admin.tournaments.destroy', $t->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Se borrarán todos los equipos y partidos asociados.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/50 dark:text-red-300 dark:hover:bg-red-900 p-2 rounded-lg border border-red-200 dark:border-red-800 transition-all ml-2" title="Eliminar Torneo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </main>
</x-app-layout>