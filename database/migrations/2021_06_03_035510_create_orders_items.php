<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderitems', function (Blueprint $table) {       //ตารางเก็บข้อมูลในตะกร้าสินค้า
            $table->bigIncrements('id');           // $table->id();
            $table->integer('item_id');         //รหัสสินค้า             //FK => products table??  
            $table->integer('order_id');      //FK => orders table  isyl.[lyj':nhv]
            $table->text('item_name');         //ชื่อสินค้าในใบสั่งซื้อ
            $table->decimal('item_price',8,2);      //ราคาสินค้า 
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
        Schema::dropIfExists('orderitems');
    }
}
