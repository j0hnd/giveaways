<?php

namespace App\Console\Commands;

use App\Repositories\RaffleRepository;
use ClassPreloader\Config;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Raffle;
use App\Configurations;
use Carbon\Carbon;



class RaffleDraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raffle:draw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Draw raffle on background';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();

        Log::info('['.$now.'] Auto draw started');

        // get configuration for auto draw
        $config = Configurations::getConfig('auto_draw');

        if ($config->config_value == 'no') {
            Log::warning('['.$now.'] Auto draw is disabled');
            exit;
        }

        // get raffles
        $timestamp = date('Y-m-d H:i:00', strtotime($now));
        $raffles = Raffle::getRafflesForDraw($timestamp);

        if ($raffles->count()) {
            foreach ($raffles->get() as $raffle) {

                $end_date = strtotime($raffle->end_date);
                $next = date('Y-m-d H:i:s', ceil($end_date/300) * 300);

                $time_object = new \DateTime($raffle->end_date);
                $next_object = new \DateTime($next);

                $carbon_object = Carbon::instance($time_object);
                $min_difference = Carbon::instance($next_object)->diffInMinutes($carbon_object);

                $response = RaffleRepository::draw($raffle->raffle_id, $min_difference);

                if ($response['success']) {
                    // tag raffle as queued
                    Raffle::updateRaffle($raffle->raffle_id, ['is_queued' => 1]);

                    Log::info('['.$now.'] '.$raffle->name .' has been added to queue...');
                } else {
                    Log::error('['.$now.'] '.$response['message']);
                }

            }
        } else {
           Log::info('['.$now.'] No raffle to be drawn for this time.');
        }

        Log::info('['.$now.'] Auto draw ended');

        exit;
    }
}
