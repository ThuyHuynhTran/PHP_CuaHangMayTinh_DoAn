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
    Schema::table('promotion_subscribers', function (Blueprint $table) {
        $table->boolean('is_notified')->default(false);  // Trạng thái thông báo
    });
}

public function down()
{
    Schema::table('promotion_subscribers', function (Blueprint $table) {
        $table->dropColumn('is_notified');
    });
}

};
