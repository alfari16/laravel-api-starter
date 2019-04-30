<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('transaction_id');
            $table->integer('qty');

            $table->foreign('product_id')
                    ->references('id')->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->foreign('transaction_id')
                    ->references('id')->on('transactions')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
}
