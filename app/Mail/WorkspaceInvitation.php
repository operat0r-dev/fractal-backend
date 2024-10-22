<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkspaceInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $workspaceName;
    public $userEmail;
    public $link;

    public function __construct(string $workspaceName, string $userEmail, string $link)
    {
        $this->workspaceName = $workspaceName;
        $this->userEmail = $userEmail;
        $this->link = $link;
    }

    public function build()
    {
        return $this
            ->subject("You're invited to join the workspace: {$this->workspaceName}")
            ->view('emails.workspace_invitation');
    }
}
