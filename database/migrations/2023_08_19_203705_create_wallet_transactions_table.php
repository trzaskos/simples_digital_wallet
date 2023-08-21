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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer');
            $table->unsignedBigInteger('payee');
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('amount', 9);
            $table->string('description')->nullable();
            $table->enum('type', ['SEND', 'RECEIVE']);
            $table->enum('status', ['SUCCESS', 'FAIL'])->nullable();
            $table->string('status_message')->nullable();
            $table->timestamps();

            $table->foreign('payer')->references('id')->on('users');
            $table->foreign('payee')->references('id')->on('users');
            $table->foreign('wallet_id')->references('id')->on('wallet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
