<!DOCTYPE html>
<html>
<head>
    <title>Estado de Reserva</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px;">
        <h2 style="color: #2563eb;">Hola, {{ $reserva->user->name }}</h2>
        
        <p>El estado de tu reserva para la cancha <strong>{{ $reserva->cancha->name }}</strong> ha cambiado.</p>

        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p><strong>Nuevo Estado:</strong> 
                @if($reserva->status == 'confirmed') <span style="color:green">CONFIRMADA ✅</span>
                @elseif($reserva->status == 'cancelled') <span style="color:red">CANCELADA ❌</span>
                @elseif($reserva->status == 'advance_paid') <span style="color:blue">ADELANTO PAGADO 💵</span>
                @else {{ $reserva->status }}
                @endif
            </p>
            <p><strong>Fecha:</strong> {{ $reserva->start_time->format('d/m/Y') }}</p>
            <p><strong>Hora:</strong> {{ $reserva->start_time->format('H:i') }} - {{ $reserva->end_time->format('H:i') }}</p>
            <p><strong>Total:</strong> S/ {{ $reserva->total_price }}</p>
        </div>

        <p>Si tienes dudas, contáctanos.</p>
        <p style="font-size: 12px; color: #888;">Atte. El equipo de PichangaYa</p>
    </div>
</body>
</html>