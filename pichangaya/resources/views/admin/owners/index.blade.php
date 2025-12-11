<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Dueños') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($owners as $owner)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $owner->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $owner->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.owners.courts', $owner) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        Ver Canchas
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $owners->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>