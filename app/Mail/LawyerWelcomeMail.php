<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LawyerWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $lawyer;
    public $plainPassword;
    public string $firmName;

    public function __construct($lawyer, $plainPassword)
    {
        $this->lawyer        = $lawyer;
        $this->plainPassword = $plainPassword;
        $this->firmName      = firm_name();
    }

    public function build()
    {
        return $this->subject("⚖️ مرحباً بك في منصة {$this->firmName} - بيانات حسابك الجديد")
                    ->view('emails.lawyer_welcome');
        // $firmName is auto-available in the view as a public property
    }
}