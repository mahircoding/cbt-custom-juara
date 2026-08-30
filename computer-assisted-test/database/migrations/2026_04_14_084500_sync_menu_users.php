<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MenuUser;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add "type" column if it doesn't exist
        if (!Schema::hasColumn('menu_users', 'type')) {
            Schema::table('menu_users', function (Blueprint $table) {
                $table->string('type')->nullable()->after('is_active');
            });
        }

        $menus = [
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
        ];

        foreach ($menus as $menu) {
            // Check if question_practice exists and rename it to exam
            if ($menu['code'] == 'exam') {
                $oldMenu = DB::table('menu_users')->where('code', 'question_practice')->first();
                if ($oldMenu) {
                    DB::table('menu_users')->where('code', 'question_practice')->update(['code' => 'exam']);
                    continue;
                }
            }

            // Sync the menu data (using update or insert)
            $exists = DB::table('menu_users')->where('code', $menu['code'])->exists();
            if ($exists) {
                DB::table('menu_users')->where('code', $menu['code'])->update([
                    'description' => $menu['description'],
                    'order' => $menu['order'],
                    'is_active' => $menu['is_active'],
                    'type' => $menu['type']
                ]);
            } else {
                DB::table('menu_users')->insert($menu);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed for data sync in this context
    }
};
