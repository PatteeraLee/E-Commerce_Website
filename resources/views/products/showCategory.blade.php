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

					</div>
				</div>

				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center">{{$feature}}</h2>
						@forelse($products as $product)
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
                        @empty
                            <div class="alert alert-danger">
                                <p>ยังไม่มีสินค้าในหมวดหมู่ <strong>{{$feature}}</strong></p>
                            </div>
						@endforelse
					</div><!--features_items-->
					{{$products->links()}}
				</div>
			</div>
		</div>
	</section>
@endsection
