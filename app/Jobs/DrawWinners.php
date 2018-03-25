<?php

namespace App\Jobs;

use App\RaffleSignup;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotifyWinner;
use App\RaffleEntry;
use App\RaffleWinner;
use App\PrizeRoster;
use App\Raffle;
use App\Events\AnnounceWinner;


class DrawWinners implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $entries;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($entries)
    {
        $this->entries = $entries;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // draw winner
        $picked = RaffleEntry::draw($this->entries['count'], $this->entries['number_of_winners']);

        // get current time
        $now = Carbon::now();

        // get drawn winners
        if (count($picked)) {

            foreach ($picked as $key => $winner) {
                $entry = $this->entries['entries'][$winner];

                $winner_details = [
                    'raffle_id'       => $entry['raffle_id'],
                    'raffle_entry_id' => $entry['raffle_entry_id'],
                    'raffle_prize_id' => PrizeRoster::getRafflePrizeId($entry['raffle_id'], $key + 1)
                ];

                // link winner and prize
                if (RaffleWinner::saveWinnerPrizes($winner_details)) {
                    $raffle = Raffle::find(Raffle::getPrimaryId($entry['raffle_id']));
                    $raffle->drawn_date  = date('Y-m-d H:i:s');
                    $raffle->closed_date = date('Y-m-d H:i:s');

                    if ($raffle->save()) {
                        // update raffle_entry
                        $raffle_entry = RaffleEntry::find(RaffleEntry::getPrimaryId($entry['raffle_entry_id']));
                        $raffle_entry->is_winner = 1;
                        $raffle_entry->position  = $key + 1;

                        if ($raffle_entry->save()) {
                            // email raffle entry notification
                            Mail::to($entry['email'])->send(new NotifyWinner(['raffle_name' => $raffle->name]));

                            // prepare object to broadcast
                            $raffle_entry_obj  = RaffleEntry::getDetailsByRaffleEntryId($entry['raffle_entry_id']);
                            $raffle_signup_obj = RaffleSignup::getSignupByRaffleEntryId($raffle_entry_obj->raffle_signup_id);

                            // tag raffle's draw date
                            Raffle::updateRaffle($entry['raffle_id'], ['drawn_date' => $now]);

                            // announce winners
                            event(new AnnounceWinner($raffle_signup_obj, $raffle_entry_obj));
                        }
                    }
                }
            }
        } else {
            event(new AnnounceWinner(null, null));
        }
    }
}
