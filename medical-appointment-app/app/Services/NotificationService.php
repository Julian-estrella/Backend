<?php

namespace App\Services;

use App\Mail\AppointmentCreatedMail;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $pdfService;
    protected $whatsAppService;

    public function __construct(PdfService $pdfService, WhatsAppService $whatsAppService)
    {
        $this->pdfService = $pdfService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send all notifications for an appointment.
     * 
     * @param int $appointmentId
     * @return array Status of each notification
     */
    public function sendAppointmentNotifications($appointmentId)
    {
        $appointment = Appointment::with(['patient.user', 'doctor.user'])->find($appointmentId);
        $results = [
            'pdf' => false,
            'patient_email' => false,
            'doctor_email' => false,
            'whatsapp' => false,
        ];

        if (!$appointment) return $results;

        try {
            // 1. Generar PDF para el paciente
            $pdfPath = $this->pdfService->generateAppointmentPdf($appointment);
            $results['pdf'] = true;
        } catch (\Exception $e) {
            Log::error("Error generando PDF: " . $e->getMessage());
        }

        if ($results['pdf']) {
            // 2. Enviar correo al Paciente (Redirigido a julianstarbe@gmail.com)
            try {
                Mail::to('julianstarbe@gmail.com')
                    ->send(new AppointmentCreatedMail($appointment, $pdfPath, 'patient'));
                $results['patient_email'] = true;
            } catch (\Exception $e) {
                Log::error("Error enviando correo al paciente: " . $e->getMessage());
            }

            // 3. Enviar correo al Doctor (Redirigido a julianstarbe@gmail.com)
            try {
                Mail::to('julianstarbe@gmail.com')
                    ->send(new AppointmentCreatedMail($appointment, $pdfPath, 'doctor'));
                $results['doctor_email'] = true;
            } catch (\Exception $e) {
                Log::error("Error enviando correo al doctor: " . $e->getMessage());
            }
        }

        // 4. Enviar WhatsApp al Paciente (Formato Healthify solicitado)
        try {
            $patientName = $appointment->patient->user->name;
            $doctorName = $appointment->doctor->user->name;
            $dateFormatted = \Carbon\Carbon::parse($appointment->date)->format('d/m/Y');
            $timeFormatted = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');

            $whatsAppMessage = "Hola *{$patientName}*, tu cita médica ha sido confirmada ✅\n\n" .
                               "📅 *Fecha:* {$dateFormatted}\n" .
                               "🕒 *Hora:* {$timeFormatted}\n" .
                               "👨‍⚕️ *Doctor:* Dr. {$doctorName}\n\n" .
                               "Por favor llega 10 minutos antes de tu cita.\n\n" .
                               "Si necesitas reprogramar o cancelar, responde a este mensaje.\n\n" .
                               "Gracias. *{$patientName}*";
            
            $results['whatsapp'] = $this->whatsAppService->sendMessage($appointment->patient->user->phone, $whatsAppMessage);
        } catch (\Exception $e) {
            Log::error("Error enviando WhatsApp: " . $e->getMessage());
        }

        return $results;
    }

    /**
     * Send daily report to a doctor via MailerSend API.
     */
    public function sendDailyDoctorReport($doctorId)
    {
        $doctor = \App\Models\Doctor::with('user')->find($doctorId);
        if (!$doctor) return false;

        $today = date('Y-m-d');
        $appointments = Appointment::with('patient.user')
            ->where('doctor_id', $doctorId)
            ->where('date', $today)
            ->orderBy('start_time')
            ->get();

        if ($appointments->isEmpty()) return false;

        try {
            // Generar PDF del reporte diario
            $pdfPath = $this->pdfService->generateDailyDoctorReportPdf($doctor, $appointments, $today);

            // Enviar vía correo (Redirigido a julianstarbe@gmail.com)
            Mail::to('julianstarbe@gmail.com')->send(new \App\Mail\DoctorDailyReportMail($doctor, $appointments, $pdfPath, $today));

            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando reporte diario al doctor {$doctorId}: " . $e->getMessage());
            return false;
        }
    }
}
