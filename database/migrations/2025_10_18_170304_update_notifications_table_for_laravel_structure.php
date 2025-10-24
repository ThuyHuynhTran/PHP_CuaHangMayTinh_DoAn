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
        Schema::table('notifications', function (Blueprint $table) {
            // Kiểm tra và thêm cột 'type' nếu chưa có
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->after('id');
            }

            // Kiểm tra và thêm các cột cho mối quan hệ đa hình (morphs)
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->string('notifiable_type')->after('type');
            }
            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->unsignedBigInteger('notifiable_id')->after('notifiable_type');
            }

            // THÊM MỚI: Kiểm tra và thêm cột 'data' nếu chưa có
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->text('data')->after('notifiable_id');
            }

            // Thêm index để cải thiện hiệu suất truy vấn
            if (!collect(Schema::getIndexes('notifications'))->pluck('name')->contains('notifications_notifiable_type_notifiable_id_index')) {
                $table->index(['notifiable_type', 'notifiable_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Xóa index trước khi xóa cột
            $table->dropIndex(['notifiable_type', 'notifiable_id']);
            
            // Xóa các cột đã thêm
            $table->dropColumn(['type', 'notifiable_type', 'notifiable_id', 'data']);
        });
    }
};
