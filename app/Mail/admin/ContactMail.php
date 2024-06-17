<?php

namespace App\Mail\admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        $to = array(
            'admin1@sample.com',
            'admin2@sample.com',
        );
        return new Envelope(
            subject: '- ACE HOME - お問い合わせを受信しました',
            from: new Address(config('mail.from.address'),config('mail.from.name')),
            to: $to,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.contact',
            with: [
                'attributes' => $this->attributes,
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
