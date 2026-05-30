<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // ID Unik (Cth: TRX-8821)
            $table->foreignId('station_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('printfile_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->integer('amount'); // Total Harga
            $table->string('status')->default('pending'); // pending, completed, rejected
            $table->string('filename_snapshot')->nullable(); // Simpan nama file disini
            // Simpan settingan print 
            $table->json('print_config');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
