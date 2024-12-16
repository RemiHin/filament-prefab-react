<?php

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->boolean('visible')->default(false);
            $table->text('intro')->nullable();
            $table->json('content')->nullable();
            $table->foreignIdFor(Media::class, 'image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->date('publish_from')->nullable()->index();
            $table->date('publish_until')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
