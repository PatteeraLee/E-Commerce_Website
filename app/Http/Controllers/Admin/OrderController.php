<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;      //connect database
use Illuminate\Pagination\Paginator;

class OrderController extends Controller
{
    public function orderPanel(){       //query ข้อมูลจากตาราง orders
        Paginator::useBootstrap();
        $orders=DB::table('orders')->paginate(10);
        return view('admin.OrderPanel',["orders"=>$orders]);       //โยนค่า array
    }

    public function showOrderDetail($id){
        $orderitems=DB::table('orders')            //query ข้อมูลตาราง orders กับ orderitems
                        ->join('orderitems','orders.order_id','=','orderitems.order_id')   //โดยเปรียบเทียบคอลัม order_id ตรงกัน
                        ->where('orders.order_id',$id)         // order_id = id ที่ส่งค่ามากับ uri
                        ->get();             //get ข้อมูลไปใช้
        return view('admin.orderDetails',["orderitems"=>$orderitems]);
    }
}
