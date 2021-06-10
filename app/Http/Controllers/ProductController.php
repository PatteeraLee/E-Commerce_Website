<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;   //ให้หน้า views ตะกร้า หา session เจอ
use Illuminate\Support\Facades\Auth;       //ใช้ดึง User_id    ,ดึงชื่อได้
use Illuminate\Support\Facades\DB;         //เชื่อมต่อฐานข้อมูล เพิ่มลบแก้ไขข้อมูล

class ProductController extends Controller
{
    public function index(){
        Paginator::useBootstrap();
        $cart=Session::get('cart');  //ดึงข้อมูลตะกร้าสินค้า
        return view("products.showProduct",['cartItems'=>$cart])->with("products",Product::paginate(6))  //โยนค่ากลับไปใช้งานด้วย
                                           ->with("categories",Category::all()->sortBy('name'));  //sortByDesc('') เรียงจากมากไปน้อย
    }

    public function findCategory($id){
        Paginator::useBootstrap();
        $category = Category::find($id);
        //dd($category->products);
        return view("products.showCategory")->with("categories",Category::all()->sortBy('name'))
                                            ->with("products",$category->products()->paginate(3))    //โยนมาเฉพาะ product ในหมวดหมู่นั้น
                                            ->with("feature",$category->name);    
    }

    public function details($id){
        $product = Product::find($id);
        //dd($product);
        return view("products.showProductDetails")->with("product",$product)
                                                  ->with("categories",Category::all()->sortBy('name'));
    }

    public function addProductToCart(Request $request, $id){     //ถ้ายังไม่ปิด browser รายการในตะกร้าสินค้าจะยังคงอยู่
        // $request->session()->forget('cart');         //refresh session 'cart'
        $product = Product::find($id);                  //ค้นข้อมูลสินค้า
        $prevCart = $request->session()->get('cart');   //จอง session 'cart' เข้าไปทำงาน    //ระบุ $prevCart ให้ constructure เพื่อกำหนดค่าเริ่มต้นให้ att ในตะกร้าสินค้า
        $cart = new Cart($prevCart);                    //เวลาสร้าง object cart ใหม่ ต้องส่ง$prevCart ให้ class Cart ไปทำงาน กำหนดเป็นตะกร้าก้อนใหม่ 
        $cart->addItem($id,$product);                   // object เรียกใช้งาน function addItem
        $cart->updatePriceQuantity();
        //update ตะกร้า      โดยputที่session 
        $request->session()->put('cart',$cart);   
        //dump($cart);
        return redirect('/products');
    }

    public function addQuantityToCart(Request $request){
        $id=$request->_id;
        $quantity=$request->quantity;

        $product = Product::find($id);
        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);
        $cart->addQuantity($id,$product,$quantity);   //เพิ่มสินค้าทีตาม $quantity
        //แก้ไข ราคาสินค้ารวม ไม่ถูกต้อง
        $cart->updatePriceQuantity();
        //update ตะกร้า      
        $request->session()->put('cart',$cart);
        return redirect('/products/cart');
    }

    public function showCart(){
        $cart=Session::get('cart');     //ดึงข้อมูลใน session 'cart' มาใช้
        if($cart){
            return view('products.showCart',['cartItems'=>$cart]);   //แนบ array cartItems : $items $totalQuantity $totalPrice
        }else{
            return redirect('/products');
        }
    }

    public function deleteFromCart(Request $request, $id){
        $cart = $request->session()->get('cart');     //หรือ $cart=Session::get('cart');
        if(array_key_exists($id,$cart->items)){       //ถ้าเจอ $id = 13 ใน $cart->items = 13 14 15  
            //ลบสินค้าออกจากตะกร้า
            unset($cart->items[$id]);    //ทำลายเฉพาะ array ย่อย นั้น
        }
        //สินค้าคงเหลือ
        $afterCart = $request->session()->get('cart');   //cart after delete
        $updateCart = new Cart($afterCart);              //สร้าง object cart ใหม่ พร้อมค่าเริ่มต้นจากตะกร้าสินค้าเก่า อัพเดตค่าต่างๆในตะกร้าหลังลบสินค้า
        $updateCart->updatePriceQuantity();  
        //update session
        $request->session()->put('cart',$updateCart); 
        return redirect('/products/cart');           
    }

    public function incrementCart(Request $request, $id){
        $product = Product::find($id);                
        $prevCart = $request->session()->get('cart');   
        $cart = new Cart($prevCart);                    
        $cart->addItem($id,$product);               
        //update ตะกร้า      โดยputที่session 
        $request->session()->put('cart',$cart);   
        return redirect('/products/cart');
    }

    public function decrementCart(Request $request, $id){
        //key array = 13 
        $product = Product::find($id);                
        $prevCart = $request->session()->get('cart'); 
        $cart = new Cart($prevCart);                   //สร้างก้อนตะกร้าสินค้าใหม่ พร้อมก้อนข้อมูลเก่า เอามาเช็ค
        //เข้าถึง quantity สินค้าที่เลือก
        if($cart->items[$id]['quantity']>1){
            $cart->items[$id]['quantity']=$cart->items[$id]['quantity']-1;
            //คำนวนราคารวมสินค้าใหม่
            $cart->items[$id]['totalSinglePrice']=$cart->items[$id]['quantity']*$product['price'];
            //update ตะกร้า
            $cart->updatePriceQuantity();
            $request->session()->put('cart',$cart);
            return redirect('/products/cart');
        }else{
            //flash message
            Session()->flash("warning","ต้องมีสินค้าอย่างน้อย 1 รายการ!");
        }
        return redirect('/products/cart');
    }

    public function searchProduct(Request $request){
        //dd($request->search);
        Paginator::useBootstrap();
        $name = $request->search;
        $products=Product::where('name',"LIKE","%{$name}%")->paginate(2);     //ระบุ keyword SQL
        return view("products.searchProduct")->with("products",$products)
                                             ->with("categories",Category::all()->sortBy('name'));
    }

    public function searchProductPrice(Request $request){
        Paginator::useBootstrap();                 
        $arrPrice=explode(",",$request->price);        //เปลี่ยนช่วง string เป็น array   //dd($request->price);  //"2385,10000"                        
        $products=Product::whereBetween('price',$arrPrice);     //ค้นหาสินค้าในcolumn 'price' ช่วง $arrPrice   //print_r($arrPrice);     //Array ( [0] => 3485 [1] => 6875 )
        return view('products.showProduct')    
                ->with("products",$products->paginate(3))          
                ->with("categories",Category::all()->sortBy('price'));
    }

    public function checkout(){
        return view('products.checkoutPage');
    }

    public function createOrder(Request $request){
        $cart=Session::get('cart');

        $email=$request->email;
        $fname=$request->fname;
        $lname=$request->lname;
        $address=$request->address;
        $zip=$request->zip;
        $phone=$request->phone;

        $user_id=Auth::id();

        if($cart){    //ถ้ามีสินค้าในตะกร้า
        //orders table
            $date=date("Y-m-d H:i:s");
            //Data
            $newOrder=array("date"=>$date,     
                            "price"=>$cart->totalPrice,
                            "status"=>"Not Paid",
                            "del_date"=>$date,
                            "fname"=>$fname,
                            "lname"=>$lname,
                            "address"=>$address,
                            "email"=>$email,
                            "zip"=>$zip,
                            "phone"=>$phone,
                            "user_id"=>$user_id,
                            );
            //Insert orders Data
             $creare_Order=DB::table('orders')->insert($newOrder);      //ชี้ไปที่ตาราง orders ป้อน array data
             $order_id=DB::getPDO()->lastInsertId();        //PK orders table เอาไว้ใส่เป็น FK ใน field order_id ตาราง orderitems  

        //orderitems table             //ถ้าแก้ column ให้ ดึงข้อมูลสินค้า จาก product_id แทน
            foreach($cart->items as $item){
                $item_id=$item['data']['id'];
                $item_name=$item['data']['name'];
                $item_price=$item['data']['price']; 
                $item_amount=$item['quantity'];              //** 
                //Data
                $newOrderItem=array("item_id"=>$item_id,
                                    "order_id"=>$order_id,
                                    "item_name"=>$item_name,
                                    "item_price"=>$item_price,
                                    "item_amount"=>$item_amount          //*
                                    );
                //Insert orderitems Data
                $creare_orderItem=DB::table('orderitems')->insert($newOrderItem); 
            }
            //ล้างตะกร้า
            Session::forget("cart");
            $payment_info=$newOrder;     //หลังจากสร้างใบสั่งซื้อต้องจ่ายเงินจำนวนตามorder
            $payment_info["order_id"]=$order_id;     //ใส่ข้อมูล $order_id อ้างอิงตาม ["order_id"]
            $request->session()->put("payment_info",$payment_info);   //สร้าง session ใหม่   //จะเอาข้อมูลราคารวมที่ต้องจ่ายมาใช้
            return redirect('/products/showPayment');
        }else{
            return redirect('/products');
        }
       
    }

    public function showPayment(){
        $payment_info=Session::get('payment_info');       //ดึงข้อมูลไว้เตรียมแสดงหน้า view paymentPage.blade.php
        if($payment_info['status']=='Not Paid'){
            return view("payment.paymentPage",["payment_info"=>$payment_info]);
        }else{
            return redirect('/products');
        }
    }

    public function showThankyou(){
        return view("payment.thankyou");
    }
}


