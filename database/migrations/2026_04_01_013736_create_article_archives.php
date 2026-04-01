<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_archives', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('original_article_id')->index();

            $table->string('title')->index();

            $table->string('url')->index();

            $table->text('load_text')->nullable();

            $table->string('language', 10)->nullable()->index();

            $table->enum('type', ['news', 'analysis', 'opinion', 'wiki'])->default('news')->index();

            $table->timestamp('published_at')->nullable()->index();

            $table->string('author')->nullable()->index();

            $table->timestamp('accepted_at')->nullable()->index();

            $table->unsignedBigInteger('accepted_by')->nullable();

            $table->timestamp('original_created_at')->nullable();

            $table->timestamp('original_updated_at')->nullable();

            $table->timestamp('archived_at')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_archives');
    }
};