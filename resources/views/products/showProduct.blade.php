@extends('layouts.index')
@section("content")
<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Category</h2>
						<div class="panel-group category-products" id="accordian">
            			<!--category-productsr-->
							@foreach($categories as $category)
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a href="/products/category/{{$category->id}}">{{$category->name}}</a></h4>
								</div>
							</div>
							@endforeach
						</div><!--/category-products-->

						<div class="price-range"><!--price-range-->
							<h2>Price Range</h2>
							<div class="well text-center">
								 <form action="/products/priceRange" class="" method="get">
									<input type="text" class="span2" value="" data-slider-min="0" data-slider-max="3000" data-slider-step="5" data-slider-value="[0,3000]" id="sl2" name="price"><br />
									<b class="pull-left">0</b> <b class="pull-right">3,000</b>
									<input type="submit" name="" value="ค้นหา" class="btn btn-primary">
								 </form>
							</div>
						</div><!--/price-range-->
					</div>
				</div>

				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center">Features Items</h2>
						@foreach($products as $product)
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
										<div class="productinfo text-center">
											<img src="{{asset('storage')}}/product_image/{{$product->image}}" alt="" width="250px" height="350" />
											<h2>{{number_format($product->price)}}</h2>
											<p>{{$product->name}}</p>
											<a href="/products/addToCart/{{$product->id}}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
										</div>
										<div class="product-overlay">
											<div class="overlay-content">
												<h2>{{number_format($product->price)}}</h2>
												<p>{{$product->name}}</p>
												<a href="/products/addToCart/{{$product->id}}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											</div>
										</div>
								</div>
								<div class="choose">
    									<ul class="nav nav-pills nav-justified">
	    										<li><a href="/products/details/{{$product->id}}"><i class="fa fa-info-circle"></i>Product Detail</a></li>
	    										<li><a href="/products/addToCart/{{$product->id}}"><i class="fa fa-shopping-cart"></i>Add to cart</a></li>
    									</ul>
								   </div>
							</div>
						</div>
						@endforeach
					</div><!--features_items-->
					{{$products->appends(['price'=>request()->query('price')])->links()}}
				</div>
			</div>
		</div>
	</section>
@endsection



