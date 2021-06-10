@extends('layouts.admin')
@section('body')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="table-responsive my-2">
    <h2>Create New Category</h2>
    <form action="/admin/createCategory" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Category Name" autofocus>
        </div>
        <button type="submit" name="submit" class="btn btn-success">Submit</button>
    </form>
</div>

@if($categories->count()>0)   
<div class="table-responsive">
    <table class="table">
        <thead class="thead-dark">
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Category Name</th>
            <th scope="col">Product Count</th>
            <th scope="col">Edit</th>
            <th scope="col">Remove</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
            <th scope="row">{{$category->id}}</th>
            <td>{{$category->name}}</td>
            <td>{{$category->products->count()}}</td>
            <td>
                <a href="/admin/editCategory/{{$category->id}}" class="btn btn-primary">Edit</a>
            </td>
            <td>
                <a href="/admin/deleteCategory/{{$category->id}}" onclick="return confirm('คุณต้องการลบข้อมูลหรือไม่ ?')" class="btn btn-danger">Remove</a>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
{{$categories->links()}}
</div>
@endif
@endsection