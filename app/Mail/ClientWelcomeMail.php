<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $activationUrl;

    public function __construct(
        public readonly User   $user,
        public readonly Client $client,
        string $activationToken
    ) {
        $this->activationUrl = url('/activate/' . $activationToken);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'مرحباً بك في ' . firm_name() . ' — فعّل حسابك الآن',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-welcome',
            with: [
                'clientName'    => $this->client->name,
                'firmName'      => firm_name(),
                'activationUrl' => $this->activationUrl,
            ],
        );
    }
}
