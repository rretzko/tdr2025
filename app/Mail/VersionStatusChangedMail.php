<?php

namespace App\Mail;

use App\Models\Events\Versions\Version;
use App\Models\User;
use App\Models\UserConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VersionStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    private Version $version;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly string $statusId)
    {
        $this->version = Version::find(UserConfig::getValue('versionId'));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(auth()->user()->email, auth()->user()->name),
            subject: 'Version Status Changed Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.versionStatusChanged',
            with: [
                'name' => 'Rick',
                'versionName' => $this->version->name,
                'status' => $this->statusId,
                'sender' => auth()->user()->name.' ('.auth()->user()->email.')',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
