<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyWinner extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from    = env('APP_EMAIL');

        $subject = config('app.name') . ": Congratulations!!! You win on our raffle!";

        return $this->view('emails.notify_winner')
            ->from($from)
            ->with($this->data)
            ->subject($subject);
    }
}
