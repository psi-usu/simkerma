<?php

use Illuminate\Database\Seeder;

class DefaultUserSeed extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_auth = new \App\UserAuth();
        $user_auth->username = '940311170420001';
        $user_auth->auth_type = 'SU';
        $user_auth->created_by = 'seeder';
        $user_auth->save();
    }
}
