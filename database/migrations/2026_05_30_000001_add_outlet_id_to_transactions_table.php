<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('outlet_id')
                ->nullable()
                ->after('station_id')
                ->constrained('outlets')
                ->nullOnDelete();
        });

        DB::table('transactions')
            ->select('transactions.id', 'users.outlet_id')
            ->leftJoin('users', 'transactions.station_id', '=', 'users.id')
            ->orderBy('transactions.id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    if ($row->outlet_id) {
                        DB::table('transactions')
                            ->where('id', $row->id)
                            ->update(['outlet_id' => $row->outlet_id]);
                    }
                }
            }, 'transactions.id');
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('outlet_id');
        });
    }
};