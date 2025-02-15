<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_approvals', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->nullable()->references('id')->on('transactions')->cascadeOnDelete();

            $table->tinyInteger('is_confirmed')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_approvals');
    }
};
