<?php

use Illuminate\Database\Seeder;

class MasterDataSeed extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Auth::create([
            'type'        => 'SU',
            'description' => 'Super User',
            'created_by'  => 'seeder'
        ]);
        \App\Auth::create([
            'type'        => 'AU',
            'description' => 'Admin Unit',
            'created_by'  => 'seeder'
        ]);
        \App\Auth::create([
            'type'        => 'AP',
            'description' => 'Admin Prodi',
            'created_by'  => 'seeder'
        ]);

        \App\CoopType::create([
            'type'        => 'MOU',
            'description' => 'Memorandum of Understanding',
            'created_by'  => 'seeder'
        ]);

        \App\CoopType::create([
            'type'        => 'MOA',
            'description' => 'Memorandum of Agreement',
            'created_by'  => 'seeder'
        ]);

        \App\CoopType::create([
            'type'        => 'ADDENDUM',
            'description' => 'Addendum',
            'created_by'  => 'seeder'
        ]);
    }
}
