<?php

namespace App\Mail;

use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailTemplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $object;
    public $template;
    public $emailSender;
    public $directory;

    /**
     * Create a new message instance.
     */
    public function __construct($object, $template, $emailSender, $directory)
    {
        $this->object = $object;
        $this->template = $template;
        $this->emailSender = $emailSender;
        $this->directory = $directory;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->emailSender),
            subject: $this->object,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.template',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if(!empty($this->directory)){
            foreach(File::files(public_path($this->directory)) as $file){
                $filePath = pathinfo($file);
                $attachments[] = Attachment::fromPath($this->directory . '/' . $filePath['basename']);
            }
        }

        return $attachments;
    }
}
