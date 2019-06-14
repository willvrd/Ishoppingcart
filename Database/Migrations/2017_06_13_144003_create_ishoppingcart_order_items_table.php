<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIshoppingcartOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ishoppingcart__order__items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            // fields
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->text('product_type');
            $table->float('price', 8, 2)->default(0);
            $table->float('tax', 8, 2)->default(0);
            $table->text('options')->default('')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('ishoppingcart__orders')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ishoppingcart__order__items');
    }
}
