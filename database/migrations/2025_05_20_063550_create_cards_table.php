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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('card_id');
            $table->string('cardholder_name');
            $table->string('card_number');
            $table->string('credit_limit');
            $table->string('card_type');
            $table->string('expiry_date');
            $table->string('csc');
            $table->string('org_bank_id');
            $table->string('org_name');
            $table->string('org_company_id');
            $table->string('payment_status');
            $table->integer('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
