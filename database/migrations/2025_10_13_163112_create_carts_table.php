<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Thêm khóa ngoại cho user_id
            $table->unsignedBigInteger('user_id'); // sử dụng unsignedBigInteger thay cho foreignId
            // Thêm khóa ngoại cho product_id
            $table->unsignedBigInteger('product_id'); // sử dụng unsignedBigInteger thay cho foreignId
            $table->integer('quantity')->default(1); // Số lượng sản phẩm
            $table->timestamps();

            // Thêm các ràng buộc khóa ngoại thủ công
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('dien_thoais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
