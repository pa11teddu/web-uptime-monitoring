<?php

namespace App\Mail;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteDown extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Website $monitoredSite;

    /**
     * Initialize a new downtime notification email
     */
    public function __construct(Website $website)
    {
        $this->monitoredSite = $website;
    }

    /**
     * Configure the email envelope
     */
    public function envelope(): Envelope
    {
        $senderAddress = new Address('do-not-reply@danavis.in', 'Web Uptime Monitor');
        $emailSubject = "Alert: {$this->monitoredSite->url} is currently unavailable";

        return new Envelope(
            from: $senderAddress,
            subject: $emailSubject,
        );
    }

    /**
     * Define the email content template
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.website_down',
        );
    }
}
