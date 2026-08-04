<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->enum('payment_method', ['Tunai', 'Transfer', 'QRIS'])->default('Tunai');
            $table->enum('payment_status', ['Belum Bayar', 'DP', 'Lunas'])->default('Belum Bayar');
            $table->dateTime('transaction_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
