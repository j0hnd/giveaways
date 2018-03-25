<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DefaultUserSeeder::class);
        $this->call(DefaultActionsSeeder::class);
        $this->call(DefaultConfigurations::class);
    }
}
