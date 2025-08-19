<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('video_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama grup video
            $table->text('description')->nullable(); // Deskripsi grup
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_group_id')->constrained('video_groups')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail_id');
            $table->string('video_id'); 
            $table->timestamps();
        });
         Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('link');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
        Schema::dropIfExists('video_groups');
        Schema::dropIfExists('communities');
    }
};
