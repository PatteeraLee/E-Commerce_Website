<?php
namespace App\Models;     //ตัวอื่นจะได้ import ได้
class Cart{
    
    public $items;   //Array  เพราะซื้อสินค้าหลายชิ้นในตะกร้า
    public $totalQuantity;   //จำนวนสินค้าในตะกร้า
    public $totalPrice;     //จำนวนราคารวมทั้งหมด 

    public function __construct($prevCart){          //object ตะกร้าสินค้า
        //ตะกร้าเก่า    $prevCart (ตะกร้าเก็บข้อมูลเดิมที่เคยซื้อไว้ และรอเอาตะกร้าใหม่มาอัพเดตมัน)
        if($prevCart!=null){     //ถ้าไม่เป็นค่าว่าง ดึงตะกร้าเก่ามาใช้งานด้วย
            $this->items=$prevCart->items;    //att ไม่ต้องใส่$ ,แต่ตัวแปรต้องใส่$
            $this->totalQuantity = $prevCart->totalQuantity;
            $this->totalPrice = $prevCart->totalPrice;
        }else{
            //ตะกร้าใหม่  (ไม่เคยมีสินค้า)   กำหนดค่าเริ่มต้น ให้ attribute 3 ตัว
            $this->items=[];
            $this->totalQuantity=0;
            $this->totalPrice=0;
        }
    }

    public function addItem($id,$product){   //add ข้อมูลลง array items
        $price=(int)($product->price);
        if(array_key_exists($id,$this->items)){   //คำสั่งเช็ค key(id)ซ้ำกันหรือเปล่า  (ตะกร้าเก่า)
            $productToAdd=$this->items[$id];      //เอาข้อมูลเดิมมาชี้ เตรียมอัพเดต Quantity
            $productToAdd['quantity']++;           //เพิ่ม Quantity id สินค้าย่อยนั้น
            $productToAdd['totalSinglePrice']=$productToAdd['quantity']*$price;
        }else{   //ตะกร้าใหม่
            $productToAdd=['quantity'=>1,'totalSinglePrice'=>$price,'data'=>$product];      //array เก็บข้อมูล att 3 รายการ $items $totalQuantity $totalPrice   //กำหนดค่าเริ่มต้นจำนวน 1 รายการสินค้า  //ราคาสินค้านั้นๆ เอาไว้ใช้คำนวณ   //ข้อมูลเอาไว้ใช้ทำตาราง
        }
        
        $this->items[$id]=$productToAdd;       //เอาก้อน $productToAdd มาใช้ assign value
        $this->totalQuantity++;                
        $this->totalPrice = $this->totalPrice + $price;     //ราคาใหม่ = เดิม(0) + ราคาสินค้านั้น
    }

    public function addQuantity($id,$product,$amount){   
        if($amount>0){
            $price=(int)($product->price);
            if(array_key_exists($id,$this->items)){         //(ตะกร้าเก่า) 
                $productToAdd=$this->items[$id];      
                $productToAdd['quantity']+=$amount;           //เพิ่ม Quantity id สินค้าย่อยนั้นตามจำนวน amount
                $productToAdd['totalSinglePrice']=$productToAdd['quantity']*$price;
            }else{    //ตะกร้าใหม่
                $productToAdd=['quantity'=>$amount,'totalSinglePrice'=>$price*$amount,'data'=>$product];     
            }
        }
        
        $this->items[$id]=$productToAdd;     
        $this->totalQuantity+=$amount;                
        $this->totalPrice = $this->totalPrice + $price;     
    }

    public function updatePriceQuantity(){
        $totalQuantity=0;   //reset local value
        $totalPrice=0;
        
        foreach($this->items as $item){     
            $totalQuantity=$totalQuantity+$item['quantity'];    //จำนวนสินค้ารวมในตะกร้า 
            $totalPrice=$totalPrice+$item['totalSinglePrice'];   //ราคาสินค้ารวม
        }
        $this->totalQuantity=$totalQuantity;    //เอาค่าเซ็ตลง att class cart
        $this->totalPrice=$totalPrice;
    }

}
?>