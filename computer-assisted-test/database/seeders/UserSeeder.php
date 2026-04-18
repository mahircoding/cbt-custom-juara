<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MasterData\Category;
use App\Models\Lesson\LessonCategory;
use App\Models\Exam\ExamGroup;
use App\Models\Exam\ExamGroupUser;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'administrator@pelajarin.com'],
            [
                "id" => "20b2a4122c614bb68e41b1a6f3f37780",
                "name" => "Admin Pelajarin",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "level" => 1,
                "member_type" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ]
        );

        $iqbal = User::firstOrCreate(
            ['username' => 'iqbal'],
            [
                'name' => 'Iqbal Demo',
                'email' => 'iqbal.demo@example.com',
                'password' => bcrypt('12345678'),
                'level' => 2,
                'member_type' => 2,
                'is_active' => 1,
            ]
        );

        if ((int) $iqbal->level !== 2 || (int) $iqbal->is_active !== 1) {
            $iqbal->update([
                'level' => 2,
                'is_active' => 1,
            ]);
        }

        $category = Category::query()->orderBy('order', 'asc')->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Demo Kategori',
                'order' => 1,
                'development_status' => 'production',
                'enable_practice_question_sales' => 0,
                'practice_question_sales_type' => 0,
                'enable_tryout_sales' => 0,
                'tryout_sales_type' => 0,
                'enable_module_material_sales' => 0,
                'module_material_sales_type' => 0,
                'enable_video_module_sales' => 0,
                'video_module_sales_type' => 0,
                'enable_course_sales' => 0,
                'course_sales_type' => 0,
                'enable_classroom_sales' => 0,
                'classroom_sales_type' => 0,
            ]);
        }

        $lessonCategory = LessonCategory::query()
            ->where('category_id', $category->id)
            ->orderBy('order', 'asc')
            ->first();

        if (!$lessonCategory) {
            $lessonCategory = LessonCategory::create([
                'category_id' => $category->id,
                'name' => 'Demo Kategori Mata Pelajaran',
                'description' => 'Kategori mata pelajaran demo untuk sertifikat.',
                'order' => 1,
                'development_status' => 'production',
            ]);
        }

        $demoExamGroup = ExamGroup::query()->firstOrCreate(
            ['title' => 'Demo Sertifikat - Tryout Iqbal'],
            [
                'user_id' => null,
                'category_id' => $category->id,
                'lesson_category_id' => $lessonCategory->id,
                'description' => 'Tryout demo untuk menampilkan sertifikat akun iqbal.',
                'exam_group_type' => 1,
                'assessment_type' => 1,
                'minimal_grade_type' => 0,
                'certificate_print_user' => 1,
                'exam_status' => 'active',
                'price_type' => 1,
                'show_ranking_exam' => 0,
            ]
        );

        ExamGroupUser::updateOrCreate(
            [
                'user_id' => $iqbal->id,
                'exam_group_id' => $demoExamGroup->id,
            ],
            [
                'duration' => 0,
                'section' => 1,
                'total_section' => 1,
                'total_repeat' => 0,
                'grade' => 92,
                'is_finished' => 1,
                'passed' => 1,
                'description' => 'Lulus',
                'start_time' => now()->subHours(2),
                'end_time' => now()->subHours(1),
            ]
        );
    }
}
