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
    Schema::create('promotions', function (Blueprint $table) {
        $table->id();
        $table->string('title');  // Tiêu đề khuyến mãi
        $table->text('content');  // Nội dung chi tiết khuyến mãi
        $table->timestamps();     // Thời gian tạo và cập nhật
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
