<?php

use Illuminate\Database\Seeder;

class InsertAreaSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\AreasCoop::create([
            'area_coop'=> 'Pendidikan'
        ]);

        \App\AreasCoop::create([
            'area_coop'=> 'Penelitian'
        ]);

        \App\AreasCoop::create([
            'area_coop'=> 'Pengabdian Kepada Masyarakat'
        ]);
    }
}
