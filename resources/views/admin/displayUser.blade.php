@extends('layouts.admin')
@section('body')
@if($users->count()>0)   
<div class="table-responsive">
    <h2>Users Panel</h2>
    <table class="table">
        <thead class="thead-dark">
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
            <th scope="row">{{$user->id}}</th>
            <th scope="row">{{$user->name}}</th>
            <th scope="row">{{$user->email}}</th>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$users->links()}}
</div>
@else
    <div class="alert alert-danger">
        <p>ไม่มีข้อมูลผู้ใช้ในระบบ</p>
    </div>
@endif
@endsection