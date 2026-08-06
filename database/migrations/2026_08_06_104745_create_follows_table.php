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
        Schema::create('follows', function (Blueprint $table): void {
            // Locally generated id: the sync endpoint (PUT/DELETE /internal/follows)
            // is keyed by the (follower_id, following_id) pair, not a path {id}.
            $table->uuid('id')->primary();
            $table->uuid('follower_id');
            $table->uuid('following_id');
            $table->timestamps();

            $table->unique(['follower_id', 'following_id']);
            $table->index('following_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
