<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $case;
    public string $firmName;

    /**
     * Create a new message instance.
     */
    public function __construct($case)
    {
        $this->case     = $case;
        $this->firmName = firm_name();
    }

    /**
     * Build the message with a dynamic firm name in the subject.
     */
    public function build()
    {
        return $this->subject("💼 تم تسجيل قضية جديدة بنجاح - {$this->firmName}")
                    ->view('emails.new_case');
        // $firmName and $case are auto-available as public properties
    }
}