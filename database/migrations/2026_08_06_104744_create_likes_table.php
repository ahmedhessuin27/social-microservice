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
        Schema::create('likes', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('user_id');

            // Polymorphic target: a post or a comment, both locally owned.
            // The morph map (registered in AppServiceProvider) keeps the
            // stored type short ("post"/"comment") instead of a class FQCN.
            $table->numericMorphs('likeable');

            $table->timestamps();

            // One like per user per post/comment; also makes liking idempotent.
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
