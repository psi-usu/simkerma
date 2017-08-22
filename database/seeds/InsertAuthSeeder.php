<?php

use Illuminate\Database\Seeder;

class InsertAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Auth::create([
            'type'=> 'SAU',
            'description' => 'Super Admin Unit',
            'created_by' => 'seeder'
        ]);
    }
}
