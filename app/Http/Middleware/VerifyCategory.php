<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Category;

class VerifyCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Category::all()->count()==0){
            Session()->flash('warning',"ต้องมีหมวดหมู่สินค้าอย่างน้อย 1 รายการ!");
            return redirect("/admin/createCategory");
        }
        return $next($request);
    }
}
