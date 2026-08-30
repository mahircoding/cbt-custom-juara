<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
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

        $dataCity = [];

        foreach ($data['kabupaten'] as $kodeProvinsi => $kabupatenList) {
            foreach ($kabupatenList as $kodeKabupaten => $namaKabupaten) {
                $kodeFinal = intval($kodeProvinsi . str_pad($kodeKabupaten, 2, '0', STR_PAD_LEFT));

                $dataCity[] = [
                    $kodeFinal,              
                    $namaKabupaten,          
                    intval($kodeProvinsi)
                ];
            }
        }

        $dataSubCity = [];
        foreach ($dataCity as $key => $value) {
            $dataSubCity[$key] = [
                'id' => $value[0],
                'province_id' => $value[2],
                'name' => $value[1]
            ];
        }

        \App\Models\Region\City::truncate();
        \App\Models\Region\City::insert($dataSubCity);
    }
}
