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
        Schema::create('card_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('card_id')->references('id')->on('cards')->onDelete('cascade');
            $table->string('transaction_id');
            $table->string('transaction_date');
            $table->string('posting_date');
            $table->string('billing_amount');
            $table->string('transaction_type');
            $table->string('merchant_description');
            $table->string('is_credit');
            $table->integer('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_statements');
    }
};
