<?php

declare(strict_types=1);

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
        Schema::create('posts', function (Blueprint $table): void {
            // This service is the source of truth for posts, so it assigns the ids.
            $table->id();

            // Identifies the author in the consuming application. No local
            // foreign key: this microservice never touches another app's users table.
            $table->unsignedBigInteger('author_id');

            $table->text('content')->nullable();
            $table->string('type')->default('text');
            $table->string('status')->default('published');

            // Denormalized counters, kept up to date as likes/comments/reposts
            // are added and removed, so feeds never have to count rows.
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('reposts_count')->default(0);

            $table->foreignId('reposted_from_id')->nullable()->constrained('posts')->nullOnDelete();

            // Publication time, used for feed ordering.
            $table->timestamp('posted_at')->index();

            $table->softDeletes();
            $table->timestamps();

            // Serves the core feed query: posts by a set of authors, published, newest first.
            $table->index(['author_id', 'status', 'posted_at']);

            // Serves the global feed query: every author's posts, published, newest first.
            $table->index(['status', 'posted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
