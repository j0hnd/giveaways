<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;


class DefaultActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = [
            [
                'action_id'  => Uuid::generate()->string,
                'name'       => 'Sign up for the contest',
                'value'      => 1,
                'is_default' => 1,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'action_id'  => Uuid::generate()->string,
                'name'       => 'Like us on Facebook',
                'value'      => 1,
                'is_default' => 0,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'action_id'  => Uuid::generate()->string,
                'name'       => 'Share on Facebook',
                'value'      => 1,
                'is_default' => 0,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($default as $item) {
            DB::table('actions')->insert($item);
        }
    }
}
