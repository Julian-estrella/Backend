<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de WhatsApp para las citas de mañana';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->where('status', 1) // Solo pendientes
            ->get();

        $this->info("Buscando citas para el día: {$tomorrow}");
        $this->info("Citas encontradas: " . $appointments->count());

        foreach ($appointments as $appointment) {
            $timeFormatted = Carbon::parse($appointment->start_time)->format('H:i');
            $message = "Recordatorio: Tienes una cita mañana {$tomorrow} a las {$timeFormatted} con el Dr. {$appointment->doctor->user->name}. ¡Te esperamos!";
            
            $success = $whatsAppService->sendMessage($appointment->patient->user->phone, $message);
            
            if ($success) {
                $this->info("Recordatorio enviado a: " . $appointment->patient->user->name);
            } else {
                $this->error("Error enviando recordatorio a: " . $appointment->patient->user->name);
            }
        }

        return Command::SUCCESS;
    }
}
