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
        if (!Schema::hasTable('posts')) {

           /* Schema::create('posts', function (Blueprint $table) {
                 $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('slug')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->string('featured_image')->nullable();
                $table->boolean('published')->default(true);
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
            });*/
        }

       /* Schema::create('post_tag', function (Blueprint $table) {
           $table->id();
           $table->foreignId('post_id')->constrained()->onDelete('cascade');
           $table->foreignId('tag_id')->constrained()->onDelete('cascade');
           $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('post_tag');
        // Schema::dropIfExists('posts');
    }
};
