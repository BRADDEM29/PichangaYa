<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            {{-- Mensaje éxito --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8">Buzón de Sugerencias</h2>
            
            <div class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Fecha/Hora</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Usuario</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500 text-center">Calificación</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Comentario</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Estado</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($suggestions as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            {{-- FECHA/HORA --}}
                            <td class="p-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $s->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $s->created_at->format('h:i A') }}</div>
                            </td>

                            {{-- USUARIO --}}
                            <td class="p-4 font-medium text-gray-900 dark:text-white">
                                {{ $s->user ? $s->user->name : ($s->name ?? 'Anónimo') }}
                            </td>

                            {{-- RATING --}}
                            <td class="p-4 text-2xl text-center">
                                @php $emojis = [1=>'😡', 2=>'☹️', 3=>'😐', 4=>'🙂', 5=>'😍']; @endphp
                                {{ $emojis[$s->rating] ?? '🤔' }}
                            </td>

                            {{-- COMENTARIO --}}
                            <td class="p-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $s->comment }}</td>

                            {{-- ESTADO --}}
                            <td class="p-4">
                                {{-- 🟢 CORREGIDO: Ruta con admin. --}}
                                <form action="{{ route('admin.suggestions.updateStatus', $s->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="status" onchange="this.form.submit()"
                                        class="text-xs border-none rounded-lg py-1 pl-2 pr-6 font-bold cursor-pointer bg-gray-100 dark:bg-gray-800 dark:text-white focus:ring-0">
                                        <option value="pendiente" {{ $s->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="leido" {{ $s->status == 'leido' ? 'selected' : '' }}>Leído</option>
                                        <option value="implementado" {{ $s->status == 'implementado' ? 'selected' : '' }}>Implementado</option>
                                    </select>
                                </form>
                            </td>

                            {{-- ELIMINAR --}}
                            <td class="p-4 text-right">
                                {{-- 🟢 CORREGIDO: Ruta con admin. --}}
                                <form action="{{ route('admin.suggestions.destroy', $s->id) }}" method="POST" onsubmit="return confirm('¿Borrar?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 font-bold">X</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $suggestions->links() }}</div>
        </div>
    </div>
</x-app-layout>