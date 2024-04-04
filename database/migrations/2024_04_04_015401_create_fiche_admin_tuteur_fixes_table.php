<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiche_admin_tuteur_fixes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tuteur_fixe_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->float('amount')->default(0);
            $table->float('total_hours')->default(0);
            $table->string('month')->nullable();
            $table->string('file_path')->nullable();
            $table->string('code')->nullable(); 
            $table->timestamps();
            $table->boolean('state')->default(true);
            
            $table->foreign('tuteur_fixe_id')
                ->references('id')
                ->on('users')
                ->where('role', 'teacher'); 
        
            $table->foreign('admin_id')
                ->references('id')
                ->on('users')
                ->where('role', 'admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fiche_admin_tuteur_fixes');
    }
};
