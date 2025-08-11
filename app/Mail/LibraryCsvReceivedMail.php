<?php

namespace App\Mail;

use App\Models\Libraries\LibLibrarian;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LibraryCsvReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $teacherUser;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $storedFileName)
    {
        $this->teacherUser = $this->getTeacherUser();
    }

    private function getTeacherUser(): User
    {
        if (auth()->user()->isTeacher()) {
            return auth()->user();
        }

        if (auth()->user()->isLibrarian()) {
            $teacherUserId = LibLibrarian::where('user_id', auth()->user()->id)->first()->teacherUserId;
            return User::find($teacherUserId);
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $founder = User::find(368);
        $email = strip_tags($founder->email);

        return new Envelope(
            from: new Address('rick@mfrholdings.com', 'Rick Retzko'),
            subject: 'Library Csv Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.libraryCsvReceived',
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
