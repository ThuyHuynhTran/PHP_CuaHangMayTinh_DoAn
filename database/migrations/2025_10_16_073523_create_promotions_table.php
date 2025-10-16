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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ví dụ: "Giảm giá 30% mừng Quốc Khánh"
            $table->text('description')->nullable(); // Mô tả chi tiết về khuyến mãi
            $table->decimal('discount_percentage', 5, 2)->nullable(); // Tỷ lệ giảm giá, vd: 30.00
            $table->timestamp('start_date')->nullable(); // Ngày bắt đầu áp dụng
            $table->timestamp('end_date')->nullable(); // Ngày kết thúc
            $table->timestamps(); // Tự động tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};


   
