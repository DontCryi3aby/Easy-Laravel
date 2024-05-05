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
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postId')->constrained('posts')->onDelete('cascade');
            $table->string('title', 100);
            $table->tinyInteger('published');
            $table->timestamps();
            $table->timestamp('publishedAt')->nullable();
            $table->text('content')->nullable();
        });

        Schema::table('post_comments', function (Blueprint $table) 
        {
            $table->foreignId('parentId')->nullable()->constrained('post_comments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};