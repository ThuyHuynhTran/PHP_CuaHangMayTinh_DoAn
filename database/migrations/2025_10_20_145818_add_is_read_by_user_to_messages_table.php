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
                // Thêm cột để theo dõi trạng thái đã đọc từ phía người dùng
                // Mặc định là true vì khi user tạo, họ đã đọc tin nhắn của chính mình
                $table->boolean('is_read_by_user')->default(true)->after('status');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropColumn('is_read_by_user');
            });
        }
    };
