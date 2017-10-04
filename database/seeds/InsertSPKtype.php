<?php

use Illuminate\Database\Seeder;

class InsertSPKtype extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\CoopType::create([
            'type'        => 'SPK',
            'description' => 'Surat Perintah Kerja',
            'created_by'  => 'seeder'
        ]);
    }
}
