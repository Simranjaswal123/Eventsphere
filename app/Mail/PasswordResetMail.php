<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;

    public function __construct(public User $user, string $token)
    {
        $this->resetUrl = url('/reset-password/' . $token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset Your EventSphere Password');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.password-reset');
    }
}
