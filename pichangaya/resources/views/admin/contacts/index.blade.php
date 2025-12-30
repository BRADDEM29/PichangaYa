<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\contacts\index.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buzón de Consultas') }}
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
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-6">Mensajes Recibidos</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Recepción</th>
                                <th class="px-6 py-3">Remitente</th>
                                <th class="px-6 py-3">Celular</th>
                                <th class="px-6 py-3">Asunto</th> {{-- 🟢 NUEVA COLUMNA ASUNTO --}}
                                <th class="px-6 py-3">Mensaje</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                    
                                    {{-- FECHA Y HORA --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $contact->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $contact->created_at->format('h:i A') }}</div>
                                    </td>
                                    
                                    {{-- REMITENTE (Nombre y Correo) --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $contact->name }}</div>
                                        <div class="text-xs text-indigo-500">{{ $contact->email }}</div>
                                    </td>

                                    {{-- CELULAR --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                            {{ $contact->phone ?? '---' }}
                                        </div>
                                        @if($contact->phone)
                                            <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" target="_blank" class="flex items-center gap-1 text-xs text-green-600 hover:text-green-800 font-bold mt-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                                WhatsApp
                                            </a>
                                        @endif
                                    </td>

                                    {{-- 🟢 ASUNTO (Ahora en su propia columna) --}}
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ $contact->subject ?? 'Sin asunto' }}
                                        </span>
                                    </td>

                                    {{-- MENSAJE (Con botón "Ver" incluido aquí) --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs truncate italic text-gray-500" title="{{ $contact->message }}">
                                            "{{ $contact->message }}"
                                        </div>
                                        {{-- 🟢 BOTÓN VER AQUÍ MISMO --}}
                                        <button onclick="alert('Mensaje de {{ $contact->name }}:\n\nAsunto: {{ $contact->subject }}\n\n{{ addslashes($contact->message) }}')" 
                                                class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline mt-1 flex items-center gap-1">
                                            <span>👁️ Leer completo</span>
                                        </button>
                                    </td>

                                    {{-- ESTADO (SELECTOR) --}}
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.contacts.updateStatus', $contact->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" onchange="this.form.submit()" 
                                                class="text-xs border-none rounded-full px-3 py-1 font-bold cursor-pointer focus:ring-0
                                                {{ $contact->status == 'pendiente' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $contact->status == 'en_revision' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $contact->status == 'atendido' ? 'bg-green-100 text-green-800' : '' }}">
                                                <option value="pendiente" {{ $contact->status == 'pendiente' ? 'selected' : '' }}>🔴 Pendiente</option>
                                                <option value="en_revision" {{ $contact->status == 'en_revision' ? 'selected' : '' }}>🟡 Revisando</option>
                                                <option value="atendido" {{ $contact->status == 'atendido' ? 'selected' : '' }}>🟢 Atendido</option>
                                            </select>
                                        </form>
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            {{-- El botón "Ver" se movió a la columna Mensaje --}}
                                            
                                            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este mensaje permanentemente?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                    {{-- Icono de Basura SVG --}}
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                                        No hay mensajes en el buzón.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $contacts->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>