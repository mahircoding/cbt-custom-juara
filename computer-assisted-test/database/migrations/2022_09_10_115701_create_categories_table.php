<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->integer('order');
            $table->enum('development_status', ['development', 'production']);

            // transaction by category
            $table->tinyInteger('enable_practice_question_sales')->default(0);
            $table->tinyInteger('practice_question_sales_type')->default(0);
            $table->tinyInteger('enable_tryout_sales')->default(0);
            $table->tinyInteger('tryout_sales_type')->default(0);
            $table->tinyInteger('enable_module_material_sales')->default(0);
            $table->tinyInteger('module_material_sales_type')->default(0);
            $table->tinyInteger('enable_video_module_sales')->default(0);
            $table->tinyInteger('video_module_sales_type')->default(0);
            $table->tinyInteger('enable_course_sales')->default(0);
            $table->tinyInteger('course_sales_type')->default(0);
            $table->tinyInteger('enable_classroom_sales')->default(0);
            $table->tinyInteger('classroom_sales_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
