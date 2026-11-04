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
            // Primary key mirrors the id assigned by the consuming application,
            // making PUT /internal/posts/{id} a natural idempotent upsert.
            $table->unsignedBigInteger('id')->primary();

            // Identifies the author in the consuming application. No local
            // foreign key: this microservice never touches another app's users table.
            $table->unsignedBigInteger('author_id');

            $table->text('content')->nullable();
            $table->string('type')->default('text');
            $table->string('status')->default('published');

            // Denormalized counters, trusted as sent by the consumer on each
            // sync — avoids this service having to recompute them locally.
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('reposts_count')->default(0);

            $table->foreignId('reposted_from_id')->nullable()->constrained('posts')->nullOnDelete();

            // Original creation time in the source system, used for feed
            // ordering so late-arriving syncs don't jump to the top of the feed.
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
