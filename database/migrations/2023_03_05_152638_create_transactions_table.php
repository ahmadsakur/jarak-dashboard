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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_id')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->integer('table_number');
            $table->integer('total_price');
            $table->string('payment_method');
            $table->enum('payment_status', ['UNPAID', 'PAID', 'REFUND', 'EXPIRED', 'FAILED']);
            $table->enum('transaction_status', ['INITIAL','CONFIRMED', 'PROCESSED', 'COMPLETED', 'CANCELLED']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
