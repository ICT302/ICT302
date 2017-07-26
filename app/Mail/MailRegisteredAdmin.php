<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class MailRegisteredAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $admin)
    {
        $this->admin  = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.registeradmin');
    }
}
