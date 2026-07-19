<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0)->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->default(0)->change();
            $table->decimal('dp_amount', 15, 2)->default(0)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->change();
            $table->decimal('dp_amount', 10, 2)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });
    }
};
