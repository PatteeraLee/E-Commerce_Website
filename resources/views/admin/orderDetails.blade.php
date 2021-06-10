@extends('layouts.admin')
@section('body')
@if($orderitems->count()>0)   
<div class="table-responsive">
    <h2>Order Details</h2>
    <table class="table">
        <thead class="thead-dark">
            <tr>
            <th scope="col">Item ID</th>
            <th scope="col">Item Name</th>
            <th scope="col">Item Price</th>
            <th scope="col">Item Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderitems as $orderitem)
            <tr>
            <th scope="row">{{$orderitem->item_id}}</th>
            <th scope="row">{{$orderitem->item_name}}</th>
            <th scope="row">{{number_format($orderitem->item_price,2)}}</th>
            <th scope="row">{{number_format($orderitem->item_amount)}}</th>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="/admin/orders" class="btn btn-primary">Back</a>
</div>
@else
    <div class="alert alert-danger">
        <p>ไม่มีข้อมูลสินค้าในใบสั่งซื้อ</p>
    </div>
@endif
@endsection