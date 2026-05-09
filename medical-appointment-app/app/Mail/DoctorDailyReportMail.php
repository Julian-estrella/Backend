<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DoctorDailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $appointments;
    public $pdfPath;
    public $date;

    /**
     * Create a new message instance.
     */
    public function __construct($doctor, $appointments, $pdfPath, $date)
    {
        $this->doctor = $doctor;
        $this->appointments = $appointments;
        $this->pdfPath = $pdfPath;
        $this->date = $date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Su Agenda de Hoy - ' . $this->date . ' - Healthify',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.doctor-daily-report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Agenda_Del_Dia_' . $this->date . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
