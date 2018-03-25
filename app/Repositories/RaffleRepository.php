<?php

namespace App\Repositories;

use App\Raffle;
use App\PrizeRoster;
use App\RaffleEntry;
use App\Jobs\DrawWinners;
use Carbon\Carbon;


class RaffleRepository
{
    public static function draw($raffle_id, $delay = 1)
    {
        // get raffle info
        $raffle_info = Raffle::getRaffleInfo($raffle_id);

        if (count($raffle_info)) {
            // check if number of required winners and prizes are equal
            if ($raffle_info->number_of_winners <> PrizeRoster::countRafflePrize($raffle_info->raffle_id)) {
                $response['message'] = "The number of winners set to this raffle and set prizes are not the same.";
                return $response;
            }

            $raffle_entries = RaffleEntry::getRawRaffleEntries($raffle_id);

            if (empty($raffle_entries)) {
                $response['message'] = "No raffle entries found";
                return response()->json($response);
            }

            // draw winner
            $job = (new DrawWinners([
                'count'             => count($raffle_entries),
                'number_of_winners' => $raffle_info->number_of_winners,
                'entries'           => $raffle_entries
            ]))->delay(Carbon::now()->addMinute($delay));

            dispatch($job);

            $response = ['success' => true, 'message' => "Please wait, the raffle is now on queue to be drawn."];

        } else {
            $response['message'] = "Can't get raffle details";
        }

        return $response;
    }
}