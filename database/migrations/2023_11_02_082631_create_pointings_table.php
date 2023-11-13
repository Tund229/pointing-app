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
        Schema::create('pointings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('course_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('promotion_id');
            $table->text('comment')->nullable();
            $table->enum('state', ['en attente', 'validé', 'rejété'])->default('en attente');
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->where('role', 'teacher');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('promotion_id')->references('id')->on('promotions');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointings');
    }
};
