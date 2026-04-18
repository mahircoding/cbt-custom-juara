<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuUser;

class MenuUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuUser::insert([
            [
                'code' => 'exam',
                'description' => 'Latihan Soal',
                'order' => 1,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'tryout',
                'description' => 'Tryout',
                'order' => 2,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'announcement',
                'description' => 'Pengumuman',
                'order' => 3,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'faq',
                'description' => 'Faq',
                'order' => 4,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'news',
                'description' => 'Berita',
                'order' => 5,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'psychology_calculator',
                'description' => 'Kalkulator Psikologi',
                'order' => 6,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'module',
                'description' => 'Modul / Materi',
                'order' => 7,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'video_module',
                'description' => 'Video Pembelajaran',
                'order' => 8,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'course',
                'description' => 'Course',
                'order' => 9,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'classroom',
                'description' => 'Live Class',
                'order' => 10,
                'is_active' => 1,
                'type' => 'sale',
            ],
            [
                'code' => 'certificate',
                'description' => 'Sertifikat',
                'order' => 11,
                'is_active' => 1,
                'type' => 'sale',
            ],
        ]);
    }
}
