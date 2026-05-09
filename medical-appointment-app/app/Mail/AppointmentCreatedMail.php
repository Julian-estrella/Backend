<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $pdfPath;
    public $recipientType; // 'patient' or 'doctor'

    /**
     * Create a new message instance.
     */
    public function __construct($appointment, $pdfPath, $recipientType = 'patient')
    {
        $this->appointment = $appointment;
        $this->pdfPath = $pdfPath;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'patient' 
            ? 'Confirmación de su Cita Médica - Healthify' 
            : 'Nueva Cita Programada - Healthify';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-created',
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
                ->as('Comprobante_Cita.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
