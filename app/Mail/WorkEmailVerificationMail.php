<?php

namespace App\Mail;

use App\Events\WorkEmailChangedEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class WorkEmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected User $sendTo;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly WorkEmailChangedEvent $event)
    {
        $this->sendTo = $event->schoolTeacher->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('rick@mfrholdings.com', 'TheDirectorsRoom.com'),
            replyTo: [new Address('rick@mfrholdings.com', 'Rick Retzko')],
            subject: 'TDR Work Email Verification Mail',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.workEmailVerification',
            with: [
                'name' => $this->sendTo->name,
                'workEmail' => $this->event->schoolTeacher->email,
                'verificationUrl' => $this->verificationUrl(),
                'schoolVo' => $this->event->schoolTeacher->schoolVo,
            ]
        );
        /**
         *  return (new MailMessage)
         * ->subject(Lang::get('Verify Email Address'))
         * ->line(Lang::get('Please click the button below to verify your email address.'))
         * ->action(Lang::get('Verify Email Address'), $url)
         * ->line(Lang::get('If you did not create an account, no further action is required.'));
         */
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

    protected function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->event->schoolTeacher->teacher_id,
                'hash' => sha1($this->event->schoolTeacher->email),
            ]
        );
    }
}
