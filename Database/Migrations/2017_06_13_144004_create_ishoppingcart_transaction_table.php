<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIshoppingcartTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ishoppingcart__transaction', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            // fields

            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('payment_id')->unsigned()->nullable();
            $table->integer('status')->default(0)->unsigned();
            $table->float('amount', 8, 2)->default(0);
            $table->text('options')->default('')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('ishoppingcart__orders')->onDelete('restrict');
            $table->foreign('payment_id')->references('id')->on('ishoppingcart__payment')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ishoppingcart__transaction');
    }
}
