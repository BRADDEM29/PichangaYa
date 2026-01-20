{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\tournaments\index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.25 2.25 0 11-4.5 0v3.375m7.5 0v2.25m-7.5 0v2.25" />
            </svg>
            Gestión de Torneos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Botón Crear --}}
            <div class="mb-6 flex justify-end">
                <a href="{{ route('admin.tournaments.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md flex items-center gap-2 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Nuevo Torneo
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                @if($tournaments->isEmpty())
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <p class="text-lg font-semibold">No se han creado torneos aún</p>
                        <p class="text-sm">Comienza creando uno nuevo con el botón de arriba.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Fecha Inicio</th>
                                    <th scope="col" class="px-6 py-3">Equipos</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournaments as $t)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            {{ $t->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($t->status === 'finished')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded border border-yellow-200">FINALIZADO</span>
                                            @elseif($t->status === 'active')
                                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded border border-green-200">ACTIVO</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded border border-gray-200">PENDIENTE</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($t->start_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $t->teams()->count() }} Equipos
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                        {{-- Botón Público --}}
                                        <a href="{{ route('arena.show', $t->id) }}" target="_blank" class="text-gray-500 hover:text-blue-500 text-xs font-bold uppercase">
                                            Ver como Público
                                        </a>

                                        {{-- 🟢 BOTÓN ADMIN PARA GESTIONAR --}}
                                        <a href="{{ route('admin.tournaments.manage', $t->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded text-xs flex-inline items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 inline">
                                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                            </svg>
                                            Gestionar Bracket
                                        </a>
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>