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
use Illuminate\Support\Facades\URL;

class SendInvitationConfirmationToRequesterMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $eventManager;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly User $user, private readonly Version $version)
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
            subject: 'Your Requested Event Invitation Has Been Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $requester = $this->user;
        $teacher = $this->user->teacher;
        $school = $teacher->schools->first();
        $schoolCounty = County::find($school->county_id)->name;
        $valueObject = TeacherNameAndSchoolValueObject::getVo($teacher);

        return new Content(
            view: 'mail.invitationConfirmationToRequester',
            with: [
                'eventManagerFullName' => $this->eventManager->name,
                'firstName' => $this->eventManager->first_name,
                'requesterFirstName' => $requester->first_name,
                'requesterEmail' => $requester->email,
                'requesterName' => $requester->name,
                'name' => $this->eventManager->first_name,
                'schoolName' => $school->name,
                'schoolVo' => $valueObject,
                'schoolCounty' => $schoolCounty,
                'workEmail' => 'work@email.com',
                'versionName' => $this->version->name,
            ],
        );
    }
}
