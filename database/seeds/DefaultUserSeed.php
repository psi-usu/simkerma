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
        $user = new \App\User();
        $user = $user->create([
            'username'   => 'superuser',
            'password'   => bcrypt('superst4r'),
            'full_name'  => 'Super User',
            'email'      => 'kokorochi.zhou@gmail.com',
            'created_by' => 'seeder'
        ]);
        $user->userAuth()->create([
            'auth_type' => 'SU',
            'created_by' => 'seeder',
        ]);
    }
}
