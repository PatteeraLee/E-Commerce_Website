@extends('layouts.index')
@section("content")
<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="/products">Home</a></li>
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
            @if(Session()->has('warning'))
            <div class="alert alert-danger" role="alert">
                {{Session()->get('warning')}}
            </div>
            @endif
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description">Description</td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
                        @foreach($cartItems->items as $item)
						<tr>
							<td class="cart_product">
								<a href="/products/details/{{$item['data']['id']}}"><img src="{{asset('storage')}}/product_image/{{$item['data']['image']}}" alt="" width="70px" heigth="70px"></a>
							</td>
							<td class="cart_description">
								<h4><a href="/products/details/{{$item['data']['id']}}">{{Str::limit($item['data']['name'],30)}}</a></h4>
								<p>{{Str::limit($item['data']['description'],50)}}</p>
							</td>
							<td class="cart_price">
								<p>{{number_format($item['data']['price'],2)}}</p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<a class="cart_quantity_up" href="/products/cart/incrementCart/{{$item['data']['id']}}"> + </a>
									<input class="cart_quantity_input" type="text" name="quantity" value="{{$item['quantity']}}" autocomplete="off" size="2">
									<a class="cart_quantity_down" href="/products/cart/decrementCart/{{$item['data']['id']}}"> - </a>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price">{{number_format($item['totalSinglePrice'])}}</p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" onclick="return confirm('คุณต้องการลบสินค้าออกจากตะกร้าหรือไม่ ?')" href="/products/cart/deleteFromCart/{{$item['data']['id']}}"><i class="fa fa-times"></i></a>
							</td>
						</tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</section> <!--/#cart_items-->

	<section id="do_action">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="total_area">
						<ul>
							<li>จำนวนสินค้ารวมทั้งหมด <span>{{$cartItems->totalQuantity}}</span></li>
							<li>ราคาสินค้ารวมทั้งหมด <span>{{number_format($cartItems->totalPrice)}}</span></li>
						</ul>
							<!-- <a class="btn btn-default update" href="">Update</a> -->
							<a class="btn btn-default check_out" href="/products/checkout" >Check Out</a>
					</div>
				</div>
			</div>
		</div>
	</section><!--/#do_action-->
@endsection
