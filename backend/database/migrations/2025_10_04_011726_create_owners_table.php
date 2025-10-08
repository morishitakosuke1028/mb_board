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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kana');
            $table->string('company_name')->nullable();
            $table->string('company_kana')->nullable();
            $table->string('contact_zip')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_tel')->nullable()->unique();
            $table->string('secret_zip');
            $table->string('secret_address');
            $table->string('secret_tel')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
