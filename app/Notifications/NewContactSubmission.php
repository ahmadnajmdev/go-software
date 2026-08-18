<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Tells the team a lead came in. Queued, so a slow or unreachable mail server
 * can never make the visitor wait — or worse, make the form look broken to
 * someone whose submission was in fact stored.
 */
class NewContactSubmission extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactSubmission $submission)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $lead = $this->submission;

        $mail = (new MailMessage)
            ->subject("New enquiry from {$lead->name}".($lead->service ? " — {$lead->service}" : ''))
            ->replyTo($lead->email, $lead->name)
            ->greeting('New enquiry')
            ->line("**{$lead->name}** got in touch through the website.")
            ->line("**Email:** {$lead->email}")
            ->line('**Phone:** '.($lead->phone ?: '—'))
            ->line('**Service:** '.($lead->service ?: '—'))
            ->line('**Language:** '.strtoupper($lead->locale))
            ->line('**Page:** '.($lead->source ?: '—'));

        if ($lead->utm_source || $lead->utm_campaign) {
            $mail->line('**Campaign:** '.collect([
                $lead->utm_source, $lead->utm_medium, $lead->utm_campaign,
            ])->filter()->implode(' / '));
        }

        return $mail
            ->line('---')
            ->line($lead->message)
            ->action('Open in the inbox', route('admin.inbox.show', $lead));
    }

    /** Also handy for a future Slack/WhatsApp channel. */
    public function toArray(object $notifiable): array
    {
        return $this->submission->only(['id', 'name', 'email', 'service', 'locale', 'source']);
    }
}
