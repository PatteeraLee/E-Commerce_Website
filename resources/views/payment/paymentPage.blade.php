@extends('layouts.index')
@section("content")
<section id="cart_items">
<div class="container">
    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Shopping Cart</li>
        </ol>
    </div>
        <div class="shopper-informations">
            <div class="row">
                <div class="col-sm-12 clearfix">
                    <div class="bill-to">
                        <p> Shipping/Bill To</p>
                        <div class="form-one">
                            <div class="total_area" style="padding:10px">
                                <ul>
                                    <li>Payment Status
                                        @if($payment_info['status']=='Not Paid')
                                            <span>ยังไม่ชำระเงิน</span>
                                        @endif
                                    </li>
                                    <li>Total
                                        <span>{{number_format($payment_info['price'])}}</span>
                                    </li>
                                    <li>
                                        <div class="summary summary-checkout">
                                            <h4 class="title-box">Payment Method</h4>
                                            <label class="payment-method">
                                                <input name="payment-method" type="radio" checked disabled> Cash on delivery</input>
                                                <h5>Order now pay on delivery</h5>  
                                            </label>
                                        </div>
                                    </li>
                                    <a href="/products/showThankyou" class="btn btn-default check_out">Place order now</a>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
</div>
</section>



@endsection
