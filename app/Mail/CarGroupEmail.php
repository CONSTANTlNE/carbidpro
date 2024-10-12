<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarGroupEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $carGroup;

    /**
     * Create a new message instance.
     */
    public function __construct($carGroup)
    {
        $this->carGroup = $carGroup;

    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Car Group Email')
            ->view('emails.car-group-email');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.car-group-email',
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
