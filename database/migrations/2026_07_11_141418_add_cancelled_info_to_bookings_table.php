<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('cancelled_reason')->nullable()->after('notes');
            $table->string('cancelled_by')->nullable()->after('cancelled_reason'); // 'user', 'admin', 'system'
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['cancelled_reason', 'cancelled_by']);
        });
    }
};
