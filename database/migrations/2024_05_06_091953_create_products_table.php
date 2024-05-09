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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body_html')->nullable();
            $table->string('vendor');
            $table->string('product_type');
            $table->string('handle');
            $table->timestamp('published_at')->nullable();
            $table->string('template_suffix')->nullable();
            $table->string('published_scope');
            $table->string('status');
            $table->string('admin_graphql_api_id');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};