<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendDailyDoctorReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-doctor-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía el reporte diario de citas a cada doctor con su lista de pacientes';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $doctors = Doctor::all();
        $this->info("Iniciando envío de reportes diarios a " . $doctors->count() . " doctores...");

        foreach ($doctors as $doctor) {
            $this->info("Enviando reporte al Dr. " . $doctor->user->name . "...");
            $sent = $notificationService->sendDailyDoctorReport($doctor->id);
            
            if ($sent) {
                $this->info("Reporte enviado exitosamente.");
            } else {
                $this->warn("No se enviaron reportes (posiblemente sin citas hoy).");
            }
        }

        $this->info("Proceso finalizado.");
    }
}
