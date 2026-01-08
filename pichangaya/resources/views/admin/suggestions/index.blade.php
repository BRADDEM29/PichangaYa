<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\suggestions\index.blade.php --}}
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Buzón de Sugerencias') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensaje de éxito mejorado --}}
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl ring-1 ring-gray-900/5">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        Feedback de Usuarios
                    </h3>
                    <span class="bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-gray-200 dark:border-gray-600 shadow-sm">
                        Total: {{ $suggestions->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 font-bold">Fecha/Hora</th>
                                <th class="px-6 py-4 font-bold">Usuario</th>
                                <th class="px-6 py-4 font-bold">Calificación</th>
                                <th class="px-6 py-4 font-bold">Comentario</th>
                                <th class="px-6 py-4 font-bold">Estado</th>
                                <th class="px-6 py-4 font-bold text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($suggestions as $s)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                                    
                                    {{-- FECHA/HORA --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0h18M5.25 12h13.5" />
                                            </svg>
                                            <span class="font-bold text-gray-900 dark:text-white">{{ $s->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $s->created_at->format('h:i A') }}
                                        </div>
                                    </td>

                                    {{-- USUARIO --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A9.948 9.948 0 0010 18c1.694 0 3.298-.446 4.707-1.221A5.99 5.99 0 0010 12z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                    {{ $s->user ? $s->user->name : ($s->name ?? 'Anónimo') }}
                                                </div>
                                                @if($s->user)
                                                    <div class="text-xs text-indigo-500">{{ $s->user->email }}</div>
                                                @else
                                                    <div class="text-xs text-gray-400 italic">No registrado</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- CALIFICACIÓN (Sistema de Estrellas) --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $ratingMap = [
                                                1 => ['text' => 'Muy Malo',  'bg' => 'bg-red-50 text-red-700 border-red-200'],
                                                2 => ['text' => 'Malo',      'bg' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                                3 => ['text' => 'Regular',   'bg' => 'bg-yellow-50 text-yellow-700 border-yellow-200'],
                                                4 => ['text' => 'Bueno',     'bg' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                                5 => ['text' => 'Excelente', 'bg' => 'bg-green-50 text-green-700 border-green-200'],
                                            ];
                                            $info = $ratingMap[$s->rating] ?? ['text' => 'N/A', 'bg' => 'bg-gray-100 text-gray-600'];
                                        @endphp
                                        
                                        <div class="flex flex-col items-start gap-1">
                                            {{-- Estrellas --}}
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $s->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                    </svg>
                                                @endfor
                                            </div>
                                            {{-- Etiqueta de texto --}}
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border {{ $info['bg'] }}">
                                                {{ $info['text'] }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- COMENTARIO --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs truncate italic text-gray-500 mb-1" title="{{ $s->comment }}">
                                            "{{ $s->comment }}"
                                        </div>
                                        @if(strlen($s->comment) > 0)
                                            <button onclick="alert('Comentario de {{ $s->user ? $s->user->name : 'Anónimo' }}:\n\n{{ addslashes($s->comment) }}')" 
                                                    class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition group">
                                                <div class="p-1 rounded-full bg-indigo-50 group-hover:bg-indigo-100 dark:bg-indigo-900/50 dark:group-hover:bg-indigo-900 mr-1.5 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                        <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                Leer completo
                                            </button>
                                        @endif
                                    </td>

                                    {{-- ESTADO (Selector limpio) --}}
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.suggestions.updateStatus', $s->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="relative">
                                                <select name="status" onchange="this.form.submit()"
                                                    class="appearance-none block w-full pl-3 pr-8 py-1 text-xs font-bold rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 transition ease-in-out duration-150 border-0 shadow-sm
                                                    {{ $s->status == 'pendiente' ? 'bg-gray-100 text-gray-600 ring-gray-200' : '' }}
                                                    {{ $s->status == 'leido' ? 'bg-blue-100 text-blue-700 ring-blue-200' : '' }}
                                                    {{ $s->status == 'implementado' ? 'bg-green-100 text-green-700 ring-green-200' : '' }}">
                                                    
                                                    <option value="pendiente" {{ $s->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="leido" {{ $s->status == 'leido' ? 'selected' : '' }}>Leído</option>
                                                    <option value="implementado" {{ $s->status == 'implementado' ? 'selected' : '' }}>Implementado</option>
                                                </select>
                                                {{-- Flecha custom --}}
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                                    <svg class="h-3 w-3 {{ $s->status == 'pendiente' ? 'text-gray-500' : ($s->status == 'leido' ? 'text-blue-600' : 'text-green-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.suggestions.destroy', $s->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta sugerencia permanentemente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 hover:bg-red-50 rounded-full group" title="Eliminar">
                                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mb-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            <p class="text-gray-500 italic">No hay sugerencias registradas todavía.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($suggestions->hasPages())
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $suggestions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>