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
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id');
            $table->float('amount')->default(0);
            $table->float('total_hours')->default(0);
            $table->string('month')->nullable();
            $table->string('file_path')->nullable();
            $table->string('code')->nullable(); 
            $table->timestamps();
            $table->boolean('state')->default(true);
            
            // Déclaration des clés étrangères pour user_id (enseignant) et admin_id (admin)
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->where('role', 'teacher'); // Lier à un utilisateur avec le rôle d'enseignant
        
            $table->foreign('admin_id')
                ->references('id')
                ->on('users')
                ->where('role', 'admin'); // Lier à un utilisateur avec le rôle d'administrateur
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_slips');
    }
};
