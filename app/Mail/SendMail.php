<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $subjectText; // add dynamic subject

    public function __construct($email, $password, $subjectText)
    {
        $this->email = $email;
        $this->password = $password;
        $this->subjectText = $subjectText;
    }

    public function build()
    {
        return $this->subject($this->subjectText) // use dynamic subject
        ->view('components.email.user-credentials')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
            ]);
    }
}
