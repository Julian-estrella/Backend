<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Generate an appointment PDF and save it temporarily.
     *
     * @param \App\Models\Appointment $appointment
     * @return string Path to the generated PDF file.
     */
    public function generateAppointmentPdf($appointment)
    {
        $data = [
            'appointment' => $appointment,
            'patient' => $appointment->patient->user,
            'doctor' => $appointment->doctor->user,
            'folio' => str_pad($appointment->id, 8, '0', STR_PAD_LEFT),
        ];

        $pdf = Pdf::loadView('pdf.appointment-pdf', $data);
        
        $fileName = 'appointment_' . $appointment->id . '_' . time() . '.pdf';
        $filePath = 'temp/' . $fileName;

        if (!Storage::disk('local')->exists('temp')) {
            Storage::disk('local')->makeDirectory('temp');
        }

        $pdfOutput = $pdf->output();
        Storage::disk('local')->put($filePath, $pdfOutput);

        return Storage::disk('local')->path($filePath);
    }

    /**
     * Generate a daily report PDF for a doctor.
     *
     * @param \App\Models\Doctor $doctor
     * @param \Illuminate\Database\Eloquent\Collection $appointments
     * @param string $date
     * @return string Path to the generated PDF file.
     */
    public function generateDailyDoctorReportPdf($doctor, $appointments, $date)
    {
        $data = [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('pdf.daily-doctor-report', $data);
        
        $fileName = 'daily_report_dr_' . $doctor->id . '_' . date('Y-m-d') . '.pdf';
        $filePath = 'temp/' . $fileName;

        if (!Storage::disk('local')->exists('temp')) {
            Storage::disk('local')->makeDirectory('temp');
        }

        $pdfOutput = $pdf->output();
        Storage::disk('local')->put($filePath, $pdfOutput);

        return Storage::disk('local')->path($filePath);
    }
}
