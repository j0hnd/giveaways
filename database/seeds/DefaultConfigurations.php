<?php

use Illuminate\Database\Seeder;


class DefaultConfigurations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = date('Y-m-d H:i:s');

        $configurations = [
            ['config_name' => 'auto_draw', 'config_value' => 'yes', 'is_active' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp]
        ];

        foreach ($configurations as $config) {
            DB::table('configurations')->insert($config);
        }
    }
}
