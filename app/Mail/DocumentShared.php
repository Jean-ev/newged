<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentShared extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Document $document,
        public User $sharedBy,
        public string $permission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📄 ' . $this->sharedBy->name . ' a partagé un document avec vous',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.document-shared',
        );
    }
}