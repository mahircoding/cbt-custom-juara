<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classroom_member_category', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('member_category_id');
            $table->uuid('classroom_id');

            $table->foreign('classroom_id')
                  ->references('id')
                  ->on('classrooms')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('member_category_id')
                  ->references('id')
                  ->on('member_categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_member_category');
    }
};
