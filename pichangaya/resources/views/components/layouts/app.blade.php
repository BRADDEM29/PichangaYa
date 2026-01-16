<x-layouts.app.sidebar :title="$title ?? null">
    
    <flux:main>
        {{ $slot }}
    </flux:main>

    {{-- 
       🟢 COMPONENTE SOCIAL FLOTANTE (Omnipresente)
       Se coloca aquí para que flote sobre todo el sitio (Mapa, Inicio, Dashboard, etc.)
       sin verse afectado por los márgenes de <flux:main> 
    --}}
    <livewire:social.social-panel />

</x-layouts.app.sidebar>