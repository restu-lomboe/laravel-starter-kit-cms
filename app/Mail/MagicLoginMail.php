<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MagicLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $url, public string $expiresMinutes = '15')
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your magic sign-in link — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.magic-login',
            with: [
                'url' => $this->url,
                'expiresMinutes' => $this->expiresMinutes,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
