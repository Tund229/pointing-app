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
        Schema::create('course_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id'); // Champ lié à la table 'courses'
            $table->unsignedBigInteger('promotion_id'); // Champ lié à la table 'promotions'
            $table->unsignedBigInteger('user_id'); // Champ lié à la table 'users'
            $table->string('support_file'); // Nom du fichier ou chemin
            $table->text('comment')->nullable(); // Commentaire facultatif
            $table->enum('state', ['en attente', 'validé', 'rejété'])->default('en attente');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('promotion_id')->references('id')->on('promotions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_deposits');
    }
};
