<?php

namespace App\Mail\user;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $attributes;
    /**
     * Create a new message instance.
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '- ACE HOME - お問い合わせを受付しました',
            from: new Address(config('mail.from.address'),config('mail.from.name')),
            to: $this->attributes['email'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user.contact',
            with: [
                'attributes' => $this->attributes,
                'mail' => config('mail.from.address'),
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
