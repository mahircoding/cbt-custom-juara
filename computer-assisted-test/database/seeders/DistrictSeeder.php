<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
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

        $dataDistrict = [];

        foreach ($data['kecamatan'] as $kodeKabupaten => $kecamatanList) {
            foreach ($kecamatanList as $kodeKecamatan => $namaKecamatan) {
                $kodeFinal = intval($kodeKabupaten . str_pad($kodeKecamatan, 2, '0', STR_PAD_LEFT));
                $dataDistrict[] = [
                    $kodeFinal,             
                    $namaKecamatan,         
                    intval($kodeKabupaten)
                ];
            }
        }

        $dataSubdistrict = [];
        foreach ($dataDistrict as $key => $value) {
            $dataSubdistrict[$key] = [
                'id' => $value[0],
                'city_id' => $value[2],
                'name' => $value[1]
            ];
        }

        \App\Models\Region\District::truncate();
        \App\Models\Region\District::insert($dataSubdistrict);
    }
}
