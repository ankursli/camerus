<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockSalDotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sal_dots', function (Blueprint $table) {
            $table->bigInteger('id', true, true)->unique();
            $table->string('id_salon');
            $table->string('id_dotation');
            $table->string('slug_type');
            $table->primary(array('id_salon', 'id_dotation', 'slug_type'));
            $table->string('title_dotation');
            $table->float('stock');
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
        Schema::dropIfExists('stock_sal_dots');
    }
}
