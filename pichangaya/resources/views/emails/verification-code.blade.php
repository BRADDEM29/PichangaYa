<x-mail::message>
{{-- C:\laragon\www\PichangaYa\pichangaya\resources\views\emails\verification-code.blade.php --}}
# ¡Hola Pichanguero! 

Gracias por registrarte en **PichangaYa**. Para validar tu cuenta y empezar a reservar canchas, usa el siguiente código:

<x-mail::panel>
<div style="text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px;">
    {{ $code }}
</div>
</x-mail::panel>

Si tú no solicitaste este código, simplemente ignora este correo.

Saludos,<br>
El equipo de PichangaYa
</x-mail::message>