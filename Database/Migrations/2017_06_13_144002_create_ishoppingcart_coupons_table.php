<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIshoppingcartCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ishoppingcart__coupons', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            // fields
            
            $table->string('name');
            $table->text('code');
            $table->string('type');
            $table->integer('cant')->unsigned();
            $table->integer('value')->unsigned();
            $table->timestamp('from');
            $table->timestamp('to');
            $table->string('email');
            $table->text('options')->default('')->nullable();
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
        Schema::dropIfExists('ishoppingcart__coupons');
    }
}
