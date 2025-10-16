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
        Schema::table('promotions', function (Blueprint $table) {
            // SỬA LẠI DÒNG NÀY: Dùng decimal() rồi đến unsigned()
            $table->decimal('discount_percent', 5, 2)->unsigned()->default(0)->after('content');

            // Thêm ngày bắt đầu hiệu lực
            $table->timestamp('start_date')->nullable()->after('discount_percent');
            // Thêm ngày kết thúc hiệu lực
            $table->timestamp('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['discount_percent', 'start_date', 'end_date']);
        });
    }
};
