<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            Schema::create($day, function (Blueprint $table) {
                $table->id();
                $table->string('teacher_name', 100)->nullable();
                $table->string('subject', 100)->nullable();
                $table->string('class_room', 50)->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->string('class_name', 100)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            Schema::dropIfExists($day);
        }
    }
};
