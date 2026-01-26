<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between py-1">
            <hgroup>
                <h1 class="text-xl font-black leading-tight text-gray-800 uppercase tracking-tighter sm:text-2xl">
                    Gestión de <span class="text-indigo-600">Dueños</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Administración de socios y propietarios de establecimientos
                </p>
            </hgroup>

            <nav aria-label="Acciones rápidas">
                {{-- Espacio para botones de exportar o filtros si los hubiera --}}
            </nav>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <section class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <article class="overflow-hidden bg-white border border-gray-100 shadow-xl sm:rounded-3xl">
                {{-- Cabecera interna de la tabla --}}
                <header class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="flex items-center text-xs font-bold text-gray-500 uppercase tracking-[0.2em]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        Listado de Propietarios Registrados
                    </h3>
                </header>

                {{-- Contenedor de tabla con scroll horizontal --}}
                <section class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-left text-gray-400 uppercase tracking-widest">
                                    Información del Socio
                                </th>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-left text-gray-400 uppercase tracking-widest">
                                    Contacto Directo
                                </th>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-center text-gray-400 uppercase tracking-widest">
                                    Operaciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($owners as $owner)
                                <tr class="transition-colors duration-200 hover:bg-indigo-50/30 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                                            {{ $owner->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-500">
                                            {{ $owner->email }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('admin.owners.courts', $owner) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-black uppercase tracking-widest text-indigo-600 shadow-sm transition-all duration-300 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:shadow-indigo-100 active:scale-95">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            Ver Canchas
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                {{-- Footer de la tabla para paginación --}}
                <footer class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                    <nav role="navigation" aria-label="Paginación de dueños">
                        {{ $owners->links() }}
                    </nav>
                </footer>
            </article>

        </section>
    </main>
</x-app-layout>