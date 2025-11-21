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
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // PK: book_id
            $table->foreignId('author_id')->constrained('authors')->onDelete('restrict');
            $table->string('BookName', 255);
            $table->string('ISBN', 17)->unique();
            $table->year('PublishYear');
            $table->unsignedInteger('NoOfBooks')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
