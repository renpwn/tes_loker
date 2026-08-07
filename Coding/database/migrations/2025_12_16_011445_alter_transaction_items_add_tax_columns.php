<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Kolom tax_code bisa null
            $table->string('tax_code')->nullable()->change();

            // Kolom numeric untuk rate & amount, default 0
            $table->decimal('tax_rate', 8, 2)->default(0)->change();
            $table->decimal('tax_amount', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('tax_code')->nullable(false)->change();
            $table->decimal('tax_rate', 8, 2)->change();
            $table->decimal('tax_amount', 15, 2)->change();
        });
    }
};
