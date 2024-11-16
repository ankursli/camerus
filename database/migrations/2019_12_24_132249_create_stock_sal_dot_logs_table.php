<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockSalDotLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sal_dot_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_salon');
            $table->string('id_dotation');
            $table->string('slug_type');
            $table->string('title_dotation');
            $table->float('stock');
            $table->float('old_stock');
            $table->integer('order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_sal_dot_logs');
    }
}
