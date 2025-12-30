<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\suggestions\index.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buzón de Sugerencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">Feedback de Usuarios</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        Total: {{ $suggestions->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Fecha/Hora</th>
                                <th class="px-6 py-3">Usuario</th>
                                <th class="px-6 py-3">Calificación</th>
                                <th class="px-6 py-3">Comentario</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($suggestions as $s)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                    {{-- FECHA/HORA --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $s->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $s->created_at->format('h:i A') }}</div>
                                    </td>

                                    {{-- USUARIO --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $s->user ? $s->user->name : ($s->name ?? 'Anónimo') }}
                                        </div>
                                        @if($s->user)
                                            <div class="text-xs text-indigo-500">{{ $s->user->email }}</div>
                                        @else
                                            <div class="text-xs text-gray-400 italic">No registrado</div>
                                        @endif
                                    </td>

                                    {{-- CALIFICACIÓN (Lógica visual mejorada) --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $ratingMap = [
                                                1 => ['emoji' => '😡', 'text' => 'Muy Malo', 'bg' => 'bg-red-100 text-red-800'],
                                                2 => ['emoji' => '☹️', 'text' => 'Malo', 'bg' => 'bg-orange-100 text-orange-800'],
                                                3 => ['emoji' => '😐', 'text' => 'Regular', 'bg' => 'bg-yellow-100 text-yellow-800'],
                                                4 => ['emoji' => '🙂', 'text' => 'Bueno', 'bg' => 'bg-blue-100 text-blue-800'],
                                                5 => ['emoji' => '😍', 'text' => 'Excelente', 'bg' => 'bg-green-100 text-green-800'],
                                            ];
                                            $ratingInfo = $ratingMap[$s->rating] ?? ['emoji' => '🤔', 'text' => '?', 'bg' => 'bg-gray-100 text-gray-800'];
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="text-xl">{{ $ratingInfo['emoji'] }}</span>
                                            <span class="text-xs font-bold px-2 py-1 rounded {{ $ratingInfo['bg'] }}">
                                                {{ $ratingInfo['text'] }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- COMENTARIO (Con botón leer más) --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs truncate italic text-gray-500 mb-1" title="{{ $s->comment }}">
                                            "{{ $s->comment }}"
                                        </div>
                                        @if(strlen($s->comment) > 30)
                                            <button onclick="alert('Comentario de {{ $s->user ? $s->user->name : 'Anónimo' }}:\n\n{{ addslashes($s->comment) }}')" 
                                                    class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                                <span>👁️ Leer completo</span>
                                            </button>
                                        @endif
                                    </td>

                                    {{-- ESTADO (Selector coloreado) --}}
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.suggestions.updateStatus', $s->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-xs border-none rounded-full px-3 py-1 font-bold cursor-pointer focus:ring-0
                                                {{ $s->status == 'pendiente' ? 'bg-gray-100 text-gray-600' : '' }}
                                                {{ $s->status == 'leido' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $s->status == 'implementado' ? 'bg-green-100 text-green-800' : '' }}">
                                                <option value="pendiente" {{ $s->status == 'pendiente' ? 'selected' : '' }}>⚪ Pendiente</option>
                                                <option value="leido" {{ $s->status == 'leido' ? 'selected' : '' }}>🔵 Leído</option>
                                                <option value="implementado" {{ $s->status == 'implementado' ? 'selected' : '' }}>🟢 Implementado</option>
                                            </select>
                                        </form>
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.suggestions.destroy', $s->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta sugerencia permanentemente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition p-2 hover:bg-red-50 rounded-full" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50 dark:bg-gray-900 rounded-b-lg">
                                        No hay sugerencias registradas todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Paginación --}}
                <div class="mt-6">
                    {{ $suggestions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>