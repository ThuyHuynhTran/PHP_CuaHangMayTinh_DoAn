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
    Schema::table('orders', function (Blueprint $table) {
        // Lưu ID của promotion đã dùng, có thể null nếu không dùng
        $table->foreignId('promotion_id')->nullable()->constrained('promotions')->after('status');
        // Lưu số tiền đã được giảm
        $table->decimal('discount_amount', 15, 2)->default(0)->after('promotion_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
