@php
    // 1. CONFIGURACIÓN DE ESTADOS (Colores y Etiquetas)
    $statusConfig = [
        'pending'      => ['label' => 'Pendiente',       'color' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600', 'dot' => 'bg-gray-500', 'side' => 'bg-gray-400'],
        'confirmed'    => ['label' => 'Confirmada',      'color' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800', 'dot' => 'bg-blue-500', 'side' => 'bg-blue-500'],
        'advance_paid' => ['label' => 'Seña Pagada',     'color' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800', 'dot' => 'bg-amber-500', 'side' => 'bg-amber-500'],
        'fully_paid'   => ['label' => 'Pagado',          'color' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800', 'dot' => 'bg-emerald-500', 'side' => 'bg-emerald-500'],
        'cancelled'    => ['label' => 'Cancelada',       'color' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800', 'dot' => 'bg-red-500', 'side' => 'bg-red-500'],
    ];

    $config = $statusConfig[$reserva->status] ?? $statusConfig['pending'];
    $statusClasses = $config['color'];
    $statusLabel   = $config['label'];
    $dotColor      = $config['dot'];
    $statusColorBg = $config['side'];

    // 2. LÓGICA CRÍTICA DE PERMISOS
    // Solo se puede editar si está PENDIENTE y la fecha es en el futuro.
    // Si pagó algo (advance o fully) o está cancelada, NO SE PUEDE EDITAR.
    
    $isPaid      = in_array($reserva->status, ['advance_paid', 'fully_paid']);
    $isCancelled = $reserva->status === 'cancelled';
    $isPast      = $reserva->start_time <= now();

    // Variable final para el botón Editar
    $canEdit     = !$isPaid && !$isCancelled && !$isPast && $reserva->status === 'pending';
    
    // Variable final para el botón Cancelar (Asumiendo que no pueden cancelar desde web si ya pagaron, o dependiendo de tu negocio)
    // Aquí he puesto que pueden cancelar si no es pasado y no está cancelado, pero si quieres bloquear cancelar si pagaron, agrega && !$isPaid
    $canCancel   = !$isCancelled && !$isPast; 
@endphp