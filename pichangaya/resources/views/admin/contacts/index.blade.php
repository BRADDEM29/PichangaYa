<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\contacts\index.blade.php --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                {{-- Icono Header --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-indigo-500">
                    <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                    <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                </svg>
                {{ __('Buzón de Consultas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de éxito mejorado --}}
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl ring-1 ring-gray-900/5">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">
                        Mensajes Recibidos
                    </h3>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2 dark:bg-gray-700 dark:text-gray-300 border border-gray-500">
                        Total: {{ $contacts->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 font-bold">Recepción</th>
                                <th class="px-6 py-4 font-bold">Remitente</th>
                                <th class="px-6 py-4 font-bold">Contacto</th>
                                <th class="px-6 py-4 font-bold">Asunto</th>
                                <th class="px-6 py-4 font-bold">Mensaje</th>
                                <th class="px-6 py-4 font-bold">Estado</th>
                                <th class="px-6 py-4 font-bold text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($contacts as $contact)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                                    
                                    {{-- FECHA Y HORA --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0h18M5.25 12h13.5" />
                                            </svg>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $contact->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $contact->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    
                                    {{-- REMITENTE --}}
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $contact->name }}</div>
                                        <div class="flex items-center gap-1 mt-1 text-xs text-indigo-500 hover:text-indigo-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                                <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                                            </svg>
                                            {{ $contact->email }}
                                        </div>
                                    </td>

                                    {{-- CELULAR --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $contact->phone ?? '---' }}
                                        </div>
                                        @if($contact->phone)
                                            <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" target="_blank" 
                                               class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full hover:bg-green-200 transition border border-green-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                                Chat
                                            </a>
                                        @endif
                                    </td>

                                    {{-- ASUNTO --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            {{ $contact->subject ?? 'Sin asunto' }}
                                        </span>
                                    </td>

                                    {{-- MENSAJE --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs truncate text-gray-500 italic mb-1" title="{{ $contact->message }}">
                                            "{{ $contact->message }}"
                                        </div>
                                        {{-- Botón VER mejorado --}}
                                        <button onclick="alert('Mensaje de {{ $contact->name }}:\n\nAsunto: {{ $contact->subject }}\n\n{{ addslashes($contact->message) }}')" 
                                                class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition group">
                                            <div class="p-1 rounded-full bg-indigo-50 group-hover:bg-indigo-100 dark:bg-indigo-900/50 dark:group-hover:bg-indigo-900 mr-1.5 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            Leer completo
                                        </button>
                                    </td>

                                    {{-- ESTADO (Selector limpio sin emojis en texto) --}}
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.contacts.updateStatus', $contact->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="relative">
                                                <select name="status" onchange="this.form.submit()" 
                                                    class="appearance-none block w-full pl-3 pr-8 py-1 text-xs font-bold rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 transition ease-in-out duration-150 border-0 shadow-sm
                                                    {{ $contact->status == 'pendiente' ? 'bg-red-100 text-red-700 ring-red-200' : '' }}
                                                    {{ $contact->status == 'en_revision' ? 'bg-yellow-100 text-yellow-700 ring-yellow-200' : '' }}
                                                    {{ $contact->status == 'atendido' ? 'bg-green-100 text-green-700 ring-green-200' : '' }}">
                                                    
                                                    <option value="pendiente" {{ $contact->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="en_revision" {{ $contact->status == 'en_revision' ? 'selected' : '' }}>Revisando</option>
                                                    <option value="atendido" {{ $contact->status == 'atendido' ? 'selected' : '' }}>Atendido</option>
                                                </select>
                                                {{-- Flecha custom para el select --}}
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                                    <svg class="h-3 w-3 {{ $contact->status == 'pendiente' ? 'text-red-700' : ($contact->status == 'en_revision' ? 'text-yellow-700' : 'text-green-700') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </form>
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este mensaje permanentemente?')" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="group text-gray-400 hover:text-red-500 transition p-1.5 hover:bg-red-50 rounded-full" title="Eliminar mensaje">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 mb-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                            </svg>
                                            <p class="text-gray-500 italic">No hay mensajes en el buzón.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($contacts->hasPages())
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $contacts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>