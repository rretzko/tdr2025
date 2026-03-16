<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly array $stats)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Status for '.$this->stats['shortName'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.registrationStatus',
            with: ['stats' => $this->stats],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
