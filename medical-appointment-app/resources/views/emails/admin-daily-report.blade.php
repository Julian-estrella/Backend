<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario de Citas</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte General de Citas</h1>
            <p>Fecha: {{ $date }}</p>
        </div>

        @if(count($appointments) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->doctor->user->name }}</td>
                            <td>{{ $appointment->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay citas programadas para el día de hoy.</p>
        @endif
    </div>
</body>
</html>
