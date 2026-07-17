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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('location');
            $table->string('cover_image')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active');// active, inactive
            $table->enum('church_type',['local','international','metropolitan'])->default('metropolitan'); // local, international
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
