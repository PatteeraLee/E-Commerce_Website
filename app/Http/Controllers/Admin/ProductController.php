<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use Illuminate\Pagination\Paginator;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware("verifyIsCategory")->only(['create','store']);  //ถ้าจะแสดงแบบปอร์มบันทึกข้อมูล บันทึกข้อมูลสินค้า ต้องเช็คก่อนว่ามีหมวดหมู่สินค้ารึเปล่า
    }

    public function index(){       //เอาไว้แสดงหน้าสินค้ารวมในฐานข้อมูล ทั้งหมด
        Paginator::useBootstrap();
        return view('admin.ProductDashboard')->with('products',Product::paginate(3));   //ใช้ model Product โยนค่าproductsมาแสดงผลที่Dashboard
    }

    public function create(){      //แสดงแบบปอร์มบันทึกข้อมูล
        return view('admin.ProductForm')->with('categories',Category::all());    //ใช้ model Category โยนค่าcategoriesมาแสดงผลที่แบบฟอร์มProductForm
    }

    public function store(Request $request){
        //validate
        // dd($request->name);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',      //เผื่ออนาคตทำเป็น text fill
            'price' => 'required|numeric',
            'image' => 'required|file|image|mimes:jpeg,png,jpg|max:5000',    
        ]);
        //convert image name
        $stringImageReFormat=base64_encode('_'.time());     //"XzE2MjIyODMzODE="
        $ext = $request->file('image')->getClientOriginalExtension();     //นามสกุลภาพ jpeg png jpg
        $imageName=$stringImageReFormat.".".$ext;        //"XzE2MjIyODM3ODU=.jpg"
        $imageEncoded=File::get($request->image);                //เอาตัวภาพ ที่อัพโหลด มาเก็บที่ตัวแปร $ imageEncoded temp
        
        //upload & insert
        Storage::disk('local')->put('public/product_image/'.$imageName, $imageEncoded);   //ระบุตำแหน่งปลายทางที่จะเอารูปไปเก็บ public/product_image 
        //insert
        $product = new Product; //ใช้ model Product ที่ผูกกับตาราง Products ดึงข้อมูลตารางเก็บลง $Product
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->price = $request->price;
        $product->image = $imageName;    //เก็บเฉพาะชื่อรูปภาพในdatabase
        $product->save();
        //flash message
        Session()->flash("success","บันทึกข้อมูลเรียบร้อยแล้ว!");
        return redirect('/admin/dashboard');
    }
    //edit product
    public function edit($id){      
        $product = Product::find($id);
        $categories = Category::all();
        //dd($product);
        return view('admin.editProductForm')->with('product',$product)    //ใช้ model Product โยนค่าproduct id นั้น มาแสดงผลที่แบบฟอร์ม editProductForm
                                            ->with('categories',$categories);   //โยนไปหน้า editProductForm
    }
    
    public function update(Request $request, $id){  
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',      //เผื่ออนาคตทำเป็น text fill  
        ]);
        
        $product = Product::find($id); //ใช้ model Product ที่ผูกกับตาราง Products ค้นหาข้อมูลid ที่จะupdate เก็บลง $Product
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if($request->category){
            $product->category_id = $request->category;
        }
        $product->save();
        //flash message
        Session()->flash("success","อัพเดตข้อมูลเรียบร้อยแล้ว!");
       return redirect('/admin/dashboard');
    }
    //edit image product
    public function editImage($id){  
        //$product = Product::find($id);
        return view('admin.editProductImage')->with('product',Product::find($id));    //model Product โยนค่าproduct id นั้น มาแสดงผลที่แบบฟอร์ม editProductForm
    }

    public function updateImage(Request $request, $id){
        
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png,jpg|max:5000',    
        ]);
        
        if($request->hasFile("image")){
            $product=Product::find($id);
            $exists=Storage::disk('local')->exists("public/product_image/".$product->image);     //ถ้าเจอภาพที่ชื่อตรงกับ id ชื่อภาพที่ค้น จะเอาตัวภาพใหม่ไปแทนภาพเก่า แต่ใช้ชื่อเดิม
            if($exists){
                Storage::delete("public/product_image/".$product->image);    //ลบตัวภาพเก่า
            }
            $request->image->storeAs("public/product_image/",$product->image);    //แทนที่
            return redirect('/admin/dashboard');
        }
    }

    public function delete($id){
        $product=Product::find($id);
        $exists=Storage::disk('local')->exists("public/product_image/".$product->image);     //หาภาพที่ชื่อตรงกับ id ชื่อภาพที่ค้น 
            if($exists){
                Storage::delete("public/product_image/".$product->image);  //ลบตัวภาพใน local
            }
        Product::destroy($id);        //ลบชื่อภาพสินค้าออกจากบนฐานข้อมูล
        //flash message
        Session()->flash("success","ลบข้อมูลเรียบร้อยแล้ว!");
        return redirect('/admin/dashboard');
    }
}
