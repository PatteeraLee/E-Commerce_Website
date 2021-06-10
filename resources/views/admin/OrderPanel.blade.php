@extends('layouts.admin')
@section('body')
@if($orders->count()>0)   
<div class="table-responsive">
    <h2>Order Panel</h2>
    <table class="table">
        <thead class="thead-dark">
            <tr>
            <th scope="col">OrderID</th>
            <th scope="col">Date</th>
            <th scope="col">Derivery</th>
            <th scope="col">Price</th>
            <th scope="col">Status</th>
            <th scope="col">UserID</th>
            <th scope="col">Order Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
            <th scope="row">{{$order->order_id}}</th>
            <th scope="row">{{$order->date}}</th>
            <th scope="row">{{$order->del_date}}</th>
            <th scope="row">{{number_format($order->price)}}</th>
            <th scope="row">
                <span class="
                            @if($order->status=='Not Paid')
                                badge badge-danger
                            @else
                                badge badge-success
                            @endif
                            ">{{$order->status}}</span>
            </th>
            <th scope="row">{{$order->user_id}}</th>
            <td>
                <a href="/admin/orders/detail/{{$order->order_id}}" onclick="" class="btn btn-info">Detail</a>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$orders->links()}}
</div>
@else
    <div class="alert alert-danger">
        <p>ไม่มีข้อมูลใบสั่งซื้อสินค้าในระบบ</p>
    </div>
@endif
@endsection