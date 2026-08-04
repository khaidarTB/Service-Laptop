<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('laptop_brand');
            $table->string('laptop_type');
            $table->string('serial_number')->nullable();
            $table->text('equipment')->nullable();
            $table->text('complaint');
            $table->text('diagnosis')->nullable();
            $table->decimal('service_fee', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('estimated_cost', 15, 2)->default(0);
            $table->date('estimated_finish')->nullable();
            $table->enum('status', ['antrean', 'pemeriksaan', 'menunggu_persetujuan', 'perbaikan', 'selesai', 'diambil', 'batal'])->default('antrean');
            $table->dateTime('date_received');
            $table->dateTime('date_completed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
