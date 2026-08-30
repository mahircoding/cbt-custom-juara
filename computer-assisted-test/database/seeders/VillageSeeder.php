<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
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
    
        $dataVillage = [];
    
        // Looping data kelurahan
        foreach ($data['kelurahan'] as $kodeKecamatan => $kelurahanList) {
            foreach ($kelurahanList as $kodeKelurahan => $namaKelurahan) {
                $kodeFinal = intval($kodeKecamatan . str_pad($kodeKelurahan, 2, '0', STR_PAD_LEFT));
    
                $dataVillage[] = [
                    'id' => $kodeFinal,
                    'district_id' => intval($kodeKecamatan),
                    'name' => $namaKelurahan
                ];
            }
        }
    
        $chunkSize = 500;
        $chunks = array_chunk($dataVillage, $chunkSize);
    
        \App\Models\Region\Village::truncate();
    
        foreach ($chunks as $chunk) {
            \App\Models\Region\Village::insert($chunk);
        }
    }
}
