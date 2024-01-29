<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MyCustomMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     public $data;

public function __construct($data)
{
    
    $this->data = $data;
}


    /**
     * Build the message.
     *
     * @return $this
     */

    //  public function build()
    // {
    //     return $this->view('emails.my_mail')->with(['data' => $this->data]);
    // }

    public function build()
    {
      
        return $this->subject('Thank you for subscribing to our newsletter')
            ->markdown('emails.my_mail') // Assuming you have a Blade file named my_mail.blade.php in the emails directory
            ->with(['data' => $this->data]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'My Custom Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
