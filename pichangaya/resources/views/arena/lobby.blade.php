<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-white mb-4">
                    Sala de Espera #{{ $lobby->id }}
                </h1>

                <div class="text-green-400 text-xl font-mono mb-6">
                    Estado: {{ strtoupper($lobby->status) }} | 
                    Deporte: {{ $lobby->sport->name }}
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-xl font-bold text-blue-400 mb-4">Equipo A</h3>
                        @foreach($lobby->slots->where('team_side', 'A') as $slot)
                            <div class="flex items-center gap-2 mb-2 text-white">
                                <img src="{{ $slot->user->profile_photo_url }}" class="w-8 h-8 rounded-full">
                                <span>{{ $slot->user->name }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-gray-700 p-4 rounded-lg">
                        <h3 class="text-xl font-bold text-red-400 mb-4">Equipo B</h3>
                        @foreach($lobby->slots->where('team_side', 'B') as $slot)
                            <div class="flex items-center gap-2 mb-2 text-white">
                                <img src="{{ $slot->user->profile_photo_url }}" class="w-8 h-8 rounded-full">
                                <span>{{ $slot->user->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>