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
        if (!Schema::hasTable('studentsbycourses')) {
            Schema::create('studentsbycourses', function (Blueprint $table) {
                $table->id();

                $table->timestamps();

                $table->foreignId('course_id')
                ->constrained('courses')
                ->onUpdate('cascade')
                ->onDelete('restrict');

                $table->foreignId('student_id')
                ->constrained('students')
                ->onUpdate('cascade')
                ->onDelete('restrict');

                $table->index('course_id');
                $table->index('student_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentsbycourses');
    }
};
