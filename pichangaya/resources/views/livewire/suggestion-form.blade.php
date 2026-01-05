{{-- resources/views/livewire/suggestion-form.blade.php --}}
<div class="bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800">
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm italic">
            ⚠️ {{ session('error') }}
        </div>
    @endif
    
    @if ($successMessage)
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold">
            ✅ {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="text-center">
            <p class="text-gray-500 mb-6 font-bold uppercase text-xs tracking-widest">¿Qué te parece la App?</p>
            
            <div class="flex justify-center items-center gap-2 sm:gap-4">
                @php
                    $emojis = [
                        1 => [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18" /><path d="M8 9l2 1" /><path d="M16 9l-2 1" /><path d="M14.5 16.05a3.5 3.5 0 0 0 -5 0" /></svg>',
                            'color' => 'text-red-500'
                        ],
                        2 => [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>',
                            'color' => 'text-orange-500'
                        ],
                        3 => [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 16a10 10 0 0 1 6 -1.5" /></svg>',
                            'color' => 'text-yellow-500'
                        ],
                        4 => [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>',
                            'color' => 'text-green-400'
                        ],
                        5 => [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 12a9 9 0 1 0 -8.012 8.946" /><path d="M9 10h.01" /><path d="M15 10h.01" /><path d="M9.5 15a3.59 3.59 0 0 0 2.774 .99" /><path d="M18.994 21.5l2.518 -2.58a1.74 1.74 0 0 0 .004 -2.413a1.627 1.627 0 0 0 -2.346 -.005l-.168 .172l-.168 -.172a1.627 1.627 0 0 0 -2.346 -.004a1.74 1.74 0 0 0 -.004 2.412l2.51 2.59" /></svg>',
                            'color' => 'text-red-500'
                        ],
                    ];
                @endphp

                @foreach($emojis as $v => $data)
                    <button type="button" 
                            wire:click="$set('rating', {{ $v }})" 
                            class="w-12 h-12 transition-all duration-300 transform hover:scale-125 {{ $rating == $v ? $data['color'] . ' scale-110' : 'text-gray-300 dark:text-gray-600 grayscale opacity-40' }}">
                        {!! $data['icon'] !!}
                    </button>
                @endforeach
            </div>
        </div>

        <textarea wire:model="comment" 
                  rows="3"
                  class="w-full rounded-2xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-green-500 focus:border-green-500" 
                  placeholder="Cuéntanos, ¿en qué podemos mejorar?"></textarea>
        
        <div class="text-center">
            <button type="submit" class="w-full bg-green-600 text-white font-black py-4 rounded-2xl hover:bg-green-500 transition-all shadow-lg shadow-green-600/20 active:scale-95">
                Enviar Sugerencia
            </button>
            <p class="mt-4 text-[10px] text-gray-400 dark:text-gray-600 uppercase tracking-widest font-medium">
                * Máximo una sugerencia cada 24 horas.
            </p>
        </div>
    </form>
</div>