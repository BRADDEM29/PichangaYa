<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards,')<br>
{{ config('app.name') }}
@endif

{{-- Subcopy (MODIFICADO AL ESPAÑOL DIRECTO) --}}
@isset($actionText)
<x-slot:subcopy>
    Si tienes problemas para hacer clic en el botón "<strong>{{ $actionText }}</strong>", 
    copia y pega la siguiente URL en tu navegador: 
    <span class="break-all" style="word-break: break-all; color: #4ade80;">
        [{{ $displayableActionUrl }}]({!! $actionUrl !!})
    </span>
</x-slot:subcopy>
@endisset
</x-mail::message>