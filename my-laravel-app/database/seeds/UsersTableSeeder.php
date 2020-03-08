<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param1 = [
            'name'              => 'zd3H02',
            'email'             => 'a@b.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
        ];

        DB::table('users')->insert($param1);

        $param1 = [
            'name'              => 'tanuki',
            'email'             => 'tanuki@tanuki.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('tanukitanuki'),
        ];

        DB::table('users')->insert($param1);
    }
}
