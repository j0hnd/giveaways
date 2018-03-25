<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\RaffleEntryThankyou;


class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $signups;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($signups)
    {
        $this->signups = $signups;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new RaffleEntryThankyou($this->signups);

        Mail::to($this->signups['recipient'])->send($email);
    }
}
