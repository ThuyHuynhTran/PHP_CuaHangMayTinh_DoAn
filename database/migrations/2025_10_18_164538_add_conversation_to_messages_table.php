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
        Schema::table('messages', function (Blueprint $table) {
            // Thêm cột conversation kiểu JSON sau cột 'message'
            $table->json('conversation')->nullable()->after('message');
            // Xóa cột admin_reply cũ vì không còn cần thiết
            $table->dropColumn('admin_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('conversation');
            $table->text('admin_reply')->nullable();
        });
    }
};
