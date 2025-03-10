<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('source');
            $table->string('author')->nullable();
            $table->string('url');
            $table->string('category');
            $table->string('external_id')->unique();
            $table->timestamp('published_at');
            $table->timestamps();

            $table->index(['published_at', 'source', 'category']);
            $table->fulltext(['title', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};