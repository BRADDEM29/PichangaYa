<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8">Buzón de Sugerencias</h2>
            
            <div class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Usuario</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500 text-center">Calificación</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Comentario</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-500">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($suggestions as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="p-4 font-bold text-gray-900 dark:text-white">{{ $s->name }}</td>
                            <td class="p-4 text-2xl text-center">
                                @php $emojis = [1=>'😡', 2=>'☹️', 3=>'😐', 4=>'🙂', 5=>'😍']; @endphp
                                {{ $emojis[$s->rating] ?? '🤔' }}
                            </td>
                            <td class="p-4 text-sm text-gray-600 dark:text-gray-400">{{ $s->comment }}</td>
                            <td class="p-4 text-xs text-gray-400">{{ \Carbon\Carbon::parse($s->created_at)->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $suggestions->links() }}</div>
        </div>
    </div>
</x-app-layout>