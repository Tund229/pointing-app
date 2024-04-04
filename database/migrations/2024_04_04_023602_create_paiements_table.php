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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->integer('amount');
            $table->string('externalTransactionId');
            $table->string('status');
            $table->boolean('success');
            $table->integer('transactionFee');
            $table->integer('transactionCommission');
            $table->string('transactionId');
            $table->string('transactionType');
            $table->string('previousBalance');
            $table->string('currentBalance');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('paye_by')->nullable();
            $table->foreign('paye_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('pay_slip_id')->nullable();
            $table->foreign('pay_slip_id')
                ->references('id')
                ->on('pay_slips')
                ->onDelete('cascade');
            $table->unsignedBigInteger('pay_slip_admin_id')->nullable();
            $table->foreign('pay_slip_admin_id')
                ->references('id')
                ->on('fiche_admin_tuteur_fixes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
