<?php

namespace App\Console\Commands;

use App\Mail\AdminDailyReportMail;
use App\Mail\DoctorDailyReportMail;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendDailyAppointmentReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-daily-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar reportes diarios de citas a administradores y doctores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $this->info("Generando reportes para el día: {$today}");

        // 1. Enviar reporte al Administrador
        $allAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $today)
            ->orderBy('start_time')
            ->get();

        // Asumimos que el admin es el usuario con email configurado en .env o el primer admin
        $adminEmail = config('mail.from.address'); // O un email específico para reportes
        
        Mail::to($adminEmail)->send(new AdminDailyReportMail($allAppointments, $today));
        $this->info("Reporte general enviado al administrador.");

        // 2. Enviar reporte a cada Doctor
        $doctorsWithAppointments = Doctor::whereHas('user')
            ->get();

        foreach ($doctorsWithAppointments as $doctor) {
            $doctorAppointments = Appointment::with(['patient.user'])
                ->where('doctor_id', $doctor->id)
                ->where('date', $today)
                ->orderBy('start_time')
                ->get();

            if ($doctorAppointments->count() > 0) {
                Mail::to($doctor->user->email)->send(new DoctorDailyReportMail($doctorAppointments, $doctor, $today));
                $this->info("Reporte enviado al Dr. {$doctor->user->name}");
            }
        }

        return Command::SUCCESS;
    }
}
