<x-app-layout>
    {{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\admin\owners\courts.blade.php --}}
    
    <x-slot name="header">
        <header class="flex items-center justify-between py-1">
            <hgroup>
                <h1 class="text-xl font-black leading-tight text-gray-800 uppercase tracking-tighter sm:text-2xl">
                    Canchas de: <span class="text-indigo-600">{{ $user->name }}</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">
                    Panel de Control • Gestión de Instalaciones Deportivas
                </p>
            </hgroup>
        </header>
    </x-slot>

    <main class="py-12 bg-gray-50/50">
        <section class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            
            {{-- SECCIÓN DE MENSAJES DE ESTADO --}}
            @if(session('success'))
                <aside class="flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-sm" role="alert">
                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-bold text-emerald-800">
                        <span class="uppercase tracking-tight">¡Éxito!</span> 
                        <span class="font-medium opacity-90">{{ session('success') }}</span>
                    </p>
                </aside>
            @endif

            <article class="bg-white border border-gray-100 shadow-xl overflow-hidden sm:rounded-3xl">
                
                {{-- BARRA DE ACCIONES SUPERIOR --}}
                <nav class="flex flex-col sm:flex-row justify-between items-center p-6 gap-4 border-b border-gray-50 bg-white" aria-label="Controles de tabla">
                    <a href="{{ route('admin.owners.index') }}" 
                       class="group inline-flex items-center text-sm font-black text-indigo-600 uppercase tracking-widest transition-all hover:text-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver a Dueños
                    </a>
                    
                    <a href="{{ route('admin.owners.canchas.create', $user) }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-[0.15em] rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:bg-indigo-700 hover:shadow-indigo-200 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Crear Nueva Cancha
                    </a>
                </nav>
                
                {{-- TABLA DE RESULTADOS --}}
                <section class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-left text-gray-400 uppercase tracking-[0.2em]">Cancha / Tarifa</th>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-left text-gray-400 uppercase tracking-[0.2em]">Ubicación</th>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-center text-gray-400 uppercase tracking-[0.2em]">Estado</th>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-center text-gray-400 uppercase tracking-[0.2em]">Agenda</th>
                                <th scope="col" colspan="2" class="px-6 py-4 text-[10px] font-black text-center text-gray-400 uppercase tracking-[0.2em]">Acciones Administrativas</th>
                                <th scope="col" class="px-6 py-4 text-[10px] font-black text-center text-gray-400 uppercase tracking-[0.2em]">Visibilidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($canchas as $cancha)
                                <tr class="transition hover:bg-indigo-50/20 group">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition">{{ $cancha->name }}</div>
                                        <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter mt-0.5">S/ {{ number_format($cancha->price_per_hour, 2) }} / hora</div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center text-xs font-medium text-gray-600">
                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $cancha->district->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($cancha->is_featured)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100 text-[10px] font-black uppercase tracking-widest gap-1.5 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                                </svg>
                                                Destacada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-50 text-gray-400 border border-gray-100 text-[10px] font-black uppercase tracking-widest">
                                                Estándar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.canchas.reservas.index', $cancha) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-xl transition hover:bg-indigo-600 hover:text-white">
                                            Gestionar
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.canchas.edit', $cancha) }}" 
                                           class="text-blue-500 hover:text-blue-700 transition active:scale-90 inline-block p-1"
                                           title="Editar Cancha">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.canchas.destroy', $cancha) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cancha?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-600 transition active:scale-90 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.canchas.toggleFeatured', $cancha) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 text-[10px] font-black uppercase tracking-widest border transition-all rounded-lg active:scale-95 {{ $cancha->is_featured ? 'border-amber-200 text-amber-600 bg-amber-50 hover:bg-amber-100' : 'border-emerald-200 text-emerald-600 bg-emerald-50 hover:bg-emerald-100' }}">
                                                {{ $cancha->is_featured ? 'Quitar' : 'Poner' }}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $cancha->is_featured ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 ml-1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                @if($canchas->isEmpty())
                    <footer class="p-12 text-center bg-gray-50/30">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="mt-2 text-sm font-bold text-gray-400 uppercase tracking-widest">Este dueño aún no tiene canchas registradas</p>
                    </footer>
                @endif
            </article>

        </section>
    </main>
</x-app-layout>