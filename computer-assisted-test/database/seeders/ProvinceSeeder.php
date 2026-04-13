<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonUrl = 'https://gist.githubusercontent.com/codenoid/a2f06c0f23fdb99e2a8a247ecfc59c16/raw/8f9f38640151f38b33c57ef943497f83819970b8/wilayah.json';
        $jsonData = file_get_contents($jsonUrl);
        $data = json_decode($jsonData, true);

        $dataProvince = [];

        foreach ($data['provinsi'] as $kodeProvinsi => $namaProvinsi) {
            $dataProvince[] = [
                intval($kodeProvinsi), 
                $namaProvinsi          
            ];
        }

        $dataSubProvince = [];
        foreach ($dataProvince as $key => $value) {
            $dataSubProvince[$key] = [
                'id' => $value[0],
                'name' => $value[1]
            ];
        }
        
        \App\Models\Region\Province::truncate();
        \App\Models\Region\Province::insert($dataSubProvince);
    }
}
