<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

class CategoryController extends Controller
{
    public function index(){      //แสดงแบบปอร์มบันทึกข้อมูล
        // $categories = Category::all(); //1 ใช้ model Category ที่ผูกกับตาราง categories ดึงข้อมูลตารางเก็บลง $categories
        // return view('admin.CategoryForm', compact("categories"));      //2
        // return view('admin.CategoryForm')->with('categories',Category::all(););     //1+2
        Paginator::useBootstrap();
        return view('admin.CategoryForm')->with('categories',Category::paginate(5));
    }

    public function store(Request $request){           //รับ Request จากแบบปอร์มบันทึกข้อมูล หลังจากกดปุ่ม submit
        //dd($request->name);                          //เอาเฉพาะข้อมูลชื่อ name ใน $request
        $request->validate([
            'name' => 'required|unique:categories',    //ไม่ให้ชื่อประเภทซ้ำ //categories = ชื่อตาราง
        ]);
        //Insert Data to Table
        $category = new Category;
        $category->name = $request->name;
        $category->save();                             //บันทึกข้อมูล
        //flash message
        Session()->flash("success","บันทึกข้อมูลเรียบร้อยแล้ว!");
        return redirect('/admin/createCategory');      //กลับหน้าเดิม
    }

    public function edit($id){
        //dd($id);   //เช็ค id ตอนกด edit
        $category = Category::find($id);
        // dd($category);  //ข้อมูลประเภท array
        return view('admin.EditCategoryForm', ['category'=>$category]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|unique:categories',     
        ]);
        $category = Category::find($id);
        $category->name=$request->name;    //เอาข้อมูลใหม่มาอัพเดต
        $category->save();
        //flash message
        Session()->flash("success","อัพเดตข้อมูลเรียบร้อยแล้ว!");
        return redirect('/admin/createCategory');
    }

    public function delete($id){
        $category=Category::find($id);           //สร้าง object $category จาก model
        if($category->products->count()>0){      //ถ้าหมวดหมู่สินค้า มีการผูกกับสินค้าตั้งแต่ 1 ชิ้น 
            //flash message
            Session()->flash("warning","ไม่สามารถลบหมวดหมู่ได้ เพราะมีสินค้าในหมวดหมู่นี้!");
            return redirect()->back();           //กลับไปหน้าเดิม ไม่ลบ
        }
        $category::destroy($id);               //ทำลายแถวที่มี id ที่กำหนด
        //flash message
        Session()->flash("success","ลบข้อมูลเรียบร้อยแล้ว!");
        return redirect('/admin/createCategory');
    }
}
