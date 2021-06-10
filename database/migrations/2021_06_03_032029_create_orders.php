<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            //ข้อมูลพื้นฐานใบสั่งซื้อ
            $table->bigIncrements('order_id');       //รหัสใบสั่งซื้อ   //$table->id();   //เชื่อมหาตาราง orderitems
            $table->date("date");                    //วันที่ชำระเงิน(วันสั่งซื้อ)
            $table->decimal("price",8,2);
            $table->text('status');                  //สถานะการจ่ายเงิน
            $table->date('del_date');                //วันที่จัดส่งสินค้า

            //ข้อมูลพื้นฐาน user
            $table->text('fname');
            $table->text('lname');
            $table->text('address');
            $table->text('phone');
            $table->text('zip');
            $table->text('email');
            $table->integer('user_id');        //FK => users table

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
        Schema::dropIfExists('orders');
    }
}
