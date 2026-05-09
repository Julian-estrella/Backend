<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Cita Médica</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4a90e2;
        }
        .title {
            font-size: 20px;
            margin-top: 5px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 150px;
            display: inline-block;
        }
        .info-value {
            color: #000;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .details-table th {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .folio {
            float: right;
            font-weight: bold;
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="folio">Folio: {{ $folio }}</div>
        <div class="logo">Healthify</div>
        <div class="title">Comprobante de Cita Médica</div>
    </div>

    <div class="info-section">
        <h3>Información del Paciente</h3>
        <p><span class="info-label">Nombre:</span> <span class="info-value">{{ $patient->name }}</span></p>
        <p><span class="info-label">Teléfono:</span> <span class="info-value">{{ $patient->phone }}</span></p>
        <p><span class="info-label">Email:</span> <span class="info-value">{{ $patient->email }}</span></p>
    </div>

    <div class="info-section">
        <h3>Detalles de la Cita</h3>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <h3>Motivo de la Consulta</h3>
        <p>{{ $appointment->reason }}</p>
    </div>

    <div class="footer">
        <p>Este es un comprobante automático de su cita programada.</p>
        <p>Por favor, llegue 15 minutos antes de su cita. Si necesita cancelar, hágalo con al menos 24 horas de anticipación.</p>
        <p>&copy; {{ date('Y') }} Healthify - Sistema de Gestión Médica</p>
    </div>
</body>
</html>
