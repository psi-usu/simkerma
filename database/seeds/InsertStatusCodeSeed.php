<?php

use Illuminate\Database\Seeder;

class InsertStatusCodeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\StatusCode::create([
            'code'=> 'SS',
            'description' => 'Simpan Sementara',
            'created_by' => 'seeder'
        ]);

        \App\StatusCode::create([
            'code'=> 'SB',
            'description' => 'Submitted',
            'created_by' => 'seeder'
        ]);

        \App\StatusCode::create([
            'code'=> 'AC',
            'description' => 'Diterima',
            'created_by' => 'seeder'
        ]);

        \App\StatusCode::create([
            'code'=> 'RJ',
            'description' => 'Ditolak',
            'created_by' => 'seeder'
        ]);
    }
}
