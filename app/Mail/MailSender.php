<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\View\View;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var View
     */
    public $view;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var array
     */
    public $myAttachments;

    /**
     * @var string|null
     */
    public $replyToAddress;

    public function __construct(View $view, string $subject, array $attachments, ?string $replyToAddress = null)
    {
        $this->view    = $view;
        $this->subject = $subject;
        $this->myAttachments = $attachments;
        $this->replyToAddress = $replyToAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown('emails.layout', ['contentView' => $this->view])
            ->from('test@wp.pl')
            ->subject(config('app.name') . ' - ' . $this->subject);

        if ($this->replyToAddress) {
            $email->replyTo($this->replyToAddress);
        }

        foreach ($this->myAttachments as $attachment) {
            $email->attach($attachment);
        }

        return $email;
    }
}
