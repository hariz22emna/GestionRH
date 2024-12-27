<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExigenceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $attachmentPath;

    public function __construct($subject, $data,$attachmentPath)
    {
        $this->subject = $subject;
        $this->data = $data;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.exigence_template')
            ->attach($this->attachmentPath)
            ->with(['data' => $this->data]);
    }
}
