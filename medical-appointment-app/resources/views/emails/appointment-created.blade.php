<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nueva Cita Programada</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4a90e2; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #4a90e2; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Healthify</h1>
        </div>
        <div class="content">
            @if($recipientType === 'patient')
                <h2>Hola, {{ $appointment->patient->user->name }}</h2>
                <p>Tu cita médica ha sido confirmada exitosamente.</p>
            @else
                <h2>Hola, Dr. {{ $appointment->doctor->user->name }}</h2>
                <p>Se ha programado una nueva cita con un paciente.</p>
            @endif

            <p><strong>Detalles de la cita:</strong></p>
            <ul>
                <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</li>
                <li><strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</li>
                <li><strong>Motivo:</strong> {{ $appointment->reason }}</li>
                @if($recipientType === 'patient')
                    <li><strong>Doctor:</strong> {{ $appointment->doctor->user->name }}</li>
                @else
                    <li><strong>Paciente:</strong> {{ $appointment->patient->user->name }}</li>
                @endif
            </ul>

            <p>Adjunto a este correo encontrarás el comprobante en formato PDF.</p>
            
            <p>Si tienes alguna duda, por favor contáctanos.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Healthify. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
