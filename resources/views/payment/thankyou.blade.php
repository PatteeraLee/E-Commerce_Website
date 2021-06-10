@extends('layouts.index')
@section("content")
<section id="cart_items">
<div class="container">
    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">THANK YOU</li>
        </ol>
    </div>
    <div class="container pb-60">
        <div class="row">
            <div class="col-md-12 text-center" style="padding:10px">
                <h2>Thank you for your order</h2>
                <a href="/products" class="btn btn-default check_out">Continue Shopping</a>
            </div>
        </div>
    </div>
    <br><br><br> 
</div>
</section>
@endsection