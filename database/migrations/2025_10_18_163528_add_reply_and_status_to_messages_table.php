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
                // Thêm cột để lưu nội dung admin phản hồi
                $table->text('admin_reply')->nullable()->after('message');
                // Thêm cột để quản lý trạng thái tin nhắn
                $table->string('status')->default('chua_doc')->after('admin_reply');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropColumn('admin_reply');
                $table->dropColumn('status');
            });
        }
    };


