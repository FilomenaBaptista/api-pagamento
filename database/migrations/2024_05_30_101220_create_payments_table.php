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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->string('status')->default('pending'); // status: pending, completed, failed
            $table->string('payment_method')->nullable(); // Método de pagamento (cartão, transferência, etc)
            $table->string('customer_email')->nullable(); // Email do cliente
            $table->text('description')->nullable(); // Descrição do pagamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
