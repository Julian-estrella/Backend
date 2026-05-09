<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda Diaria de Pacientes</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #4a90e2; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #4a90e2; }
        .title { font-size: 20px; margin-top: 5px; }
        .info-doctor { margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f4f4f4; color: #555; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Healthify</div>
        <div class="title">Agenda Diaria de Pacientes</div>
    </div>

    <div class="info-doctor">
        <strong>Doctor:</strong> {{ $doctor->user->name }}<br>
        <strong>Especialidad:</strong> {{ $doctor->specialty }}<br>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Teléfono</th>
                <th>Motivo / Detalles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                    <td>{{ $appointment->patient->user->name }}</td>
                    <td>{{ $appointment->patient->user->phone }}</td>
                    <td>{{ $appointment->reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema Healthify.</p>
        <p>&copy; {{ date('Y') }} Healthify - Gestión Médica</p>
    </div>
</body>
</html>
