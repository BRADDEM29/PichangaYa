<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <h1 class="text-2xl font-bold mb-4">Bienvenido, Admin</h1>
                <p class="mb-4">Aquí podrás gestionar las canchas, usuarios y reservas.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-100 p-4 rounded-lg border border-blue-200">
                        <h3 class="font-bold text-blue-800">Usuarios Totales</h3>
                        <p class="text-2xl">150</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg border border-green-200">
                        <h3 class="font-bold text-green-800">Canchas Activas</h3>
                        <p class="text-2xl">5</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg border border-yellow-200">
                        <h3 class="font-bold text-yellow-800">Reservas Hoy</h3>
                        <p class="text-2xl">12</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>