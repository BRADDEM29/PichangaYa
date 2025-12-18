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
        {{-- (Selector de emojis igual al anterior) --}}
        <div class="text-center">
            <p class="text-gray-500 mb-4 font-bold uppercase text-xs tracking-widest">¿Qué te parece la App?</p>
            <div class="flex justify-center gap-4">
                @foreach([1=>'😡', 2=>'☹️', 3=>'😐', 4=>'🙂', 5=>'😍'] as $v => $e)
                    <button type="button" wire:click="$set('rating', {{ $v }})" class="text-4xl transition-transform hover:scale-125 {{ $rating == $v ? 'grayscale-0' : 'grayscale opacity-40' }}">
                        {{ $e }}
                    </button>
                @endforeach
            </div>
        </div>

        <textarea wire:model="comment" class="w-full rounded-2xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Tu sugerencia..."></textarea>
        
        <div class="text-center">
            <button type="submit" class="w-full bg-green-600 text-white font-black py-4 rounded-2xl hover:bg-green-500 transition-colors">
                Enviar Sugerencia
            </button>
            {{-- AVISO DISCRETO --}}
            <p class="mt-4 text-[10px] text-gray-400 dark:text-gray-600 uppercase tracking-widest font-medium">
                * Máximo una sugerencia cada 24 horas para usuarios registrados.
            </p>
        </div>
    </form>
</div>