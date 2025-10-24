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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained()->onDelete('cascade');
            $table->string('course_title');
            $table->text('content');
            $table->string('course_image')->nullable();
            $table->string('instructor');
            $table->string('instructor_title')->nullable();
            $table->dateTime('date_time');
            $table->string('participation_fee');
            $table->string('additional_fee')->nullable();
            $table->string('capacity');
            $table->string('venue');
            $table->string('venue_zip');
            $table->string('venue_address');
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('map')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
