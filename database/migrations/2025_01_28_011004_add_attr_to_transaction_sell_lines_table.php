<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->integer('total_quantity_before_edit')->nullable()->default(0);
            $table->integer('remain_quantity_for_purchase')->virtualAs('total_quantity_before_edit - quantity');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->dropColumn('total_quantity_before_edit', 'remain_quantity_for_purchase');
        });
    }
};
