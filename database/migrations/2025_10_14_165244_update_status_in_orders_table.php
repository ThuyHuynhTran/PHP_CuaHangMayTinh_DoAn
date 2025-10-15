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
    Schema::table('orders', function (Blueprint $table) {
        $table->enum('status', [
            'cho_xac_nhan',    // Chờ xác nhận
            'cho_lay_hang',    // Chờ lấy hàng
            'cho_giao_hang',   // Chờ giao hàng
            'da_giao',         // Đã giao
            'tra_hang',        // Trả hàng / Hoàn tiền
            'da_huy'           // Đã hủy
        ])->default('cho_xac_nhan')->change();
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
