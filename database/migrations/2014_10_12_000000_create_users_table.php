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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName', 50)->nullable();
            $table->string('middleName', 50)->nullable();
            $table->string('lastName', 50)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('email', 50)->unique();
            $table->string('passwordHash');
            $table->timestamp('registeredAt');
            $table->timestamp('lastLogin')->nullable();
            $table->tinyText('intro')->nullable();
            $table->text('profile')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};