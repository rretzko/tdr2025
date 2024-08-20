<?php

namespace App\Mail;

use App\Models\County;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\User;
use App\Models\UserConfig;
use App\ValueObjects\TeacherNameAndSchoolValueObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestInvitationToEventMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $eventManager;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly Version $version)
    {
        //returns user object
        $this->eventManager = $version->getVersionManager();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('rick@mfrholdings.com', 'Rick Retzko'),
            subject: 'Request Invitation To Event Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $requester = auth()->user();
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $school = School::find(UserConfig::getValue('schoolId'));
        $schoolCounty = County::find($school->county_id)->name;
        $valueObject = TeacherNameAndSchoolValueObject::getVo($teacher);

        return new Content(
            view: 'mail.requestInvitationToEvent',
            with: [
                'firstName' => $this->eventManager->first_name,
                'requesterFirstName' => $requester->first_name,
                'requesterEmail' => $requester->email,
                'name' => $this->eventManager->first_name,
                'schoolName' => $school->name,
                'schoolVo' => $valueObject,
                'schoolCounty' => $schoolCounty,
                'workEmail' => 'work@email.com',
                'verificationUrl' => '12345',
                'versionName' => $this->version->name,
            ],
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
