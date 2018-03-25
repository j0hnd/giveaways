<?php

use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = [
            'email'         => 'admin@default.com',
            'password'      => \Hash::make('adm1n'),
            'name'          => 'Admin',
            'is_visible'    => 0,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        DB::table('users')->insert($default);
    }
}
