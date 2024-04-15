@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="{{route('pharmacy.orders')}}"> Order management </a>/Order Details </h3>
            </div>
            <div class="wrapper wrapper-content">
                <div class="row animated fadeInRight">
                    <div class="col-md-4">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Customer Details</h5>
                            </div>
                            <div class="ibox-content border-left-right">
                                <img alt="image" class="img-fluid" src="{{asset('admin/userprofile.png')}}" width="150"
                                    height="150">
                            </div>
                            <div class="ibox-content profile-content">

                                <h4>Name : <strong>{{$orderDetails->userDetails->name}}</strong></h4>
                                <h4>Mobile No. :
                                    <strong>{{$orderDetails->userDetails->country_code}}{{$orderDetails->userDetails->mobile}}</strong>
                                </h4>
                                <h4>Email : <strong>{{$orderDetails->userDetails->email}}</strong></h4>
                                <h4>Address : <strong>{{$orderDetails->userDetails->user_address}}</strong></h4>



                            </div>
                        </div>
                        @if($orderDetails->driverOrder && $orderDetails->driverOrder->driverDetails)
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Driver Details</h5>
                            </div>
                            <div class="ibox-content border-left-right">
                                <img alt="image" class="img-fluid"
                                    src="{{$orderDetails->driverOrder->driverDetails->driver_image!=null?$orderDetails->driverOrder->driverDetails->driver_image:asset('admin/userprofile.png')}}"
                                    width="150" height="150">
                            </div>
                            <div class="ibox-content profile-content">

                                <h4>Name : <strong>{{$orderDetails->driverOrder->driverDetails->driver_name}}</strong>
                                </h4>
                                <h4>Mobile No. :
                                    <strong>{{$orderDetails->driverOrder->driverDetails->driver_country_code}}{{$orderDetails->driverOrder->driverDetails->driver_mobile}}</strong>
                                </h4>
                                <h4>Email : <strong>{{$orderDetails->driverOrder->driverDetails->driver_email}}</strong>
                                </h4>


                            </div>

                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox">
                                    <div class="ibox-title">
                                        <h5>Product Details</h5>
                                        <h5 class=" float-right">Order no : {{$orderDetails->order_no}}</h5>
                                    </div>
                                    <div class="ibox-content product-box">
                                        <div class="product-desc">
                                            <h3><label for="">Sub Total </label> <label for="" class=" float-right">
                                                    {{$orderDetails->total_price-$orderDetails->shipping_charge}}</label>
                                            </h3>
                                            <h3><label for="">Shiping charge </label> <label for=""
                                                    class=" float-right"> {{$orderDetails->shipping_charge}}</label>
                                            </h3>
                                            <hr>
                                            <h3><label for="">Total price </label> <label for="" class=" float-right">
                                                    {{$orderDetails->total_price}}</label>
                                            </h3> <br><br>
                                            <hr>

                                            <div class="row">
                                                <div class=" col-12">
                                                    <h3><b> Status</b></h3>
                                                    <hr>
                                                </div>
                                                <div class=" col-12">
                                                    <h4>Request Placed
                                                        <strong
                                                            class=" float-right">{{$orderDetails->created_at->format('d
                                                            M Y H:i:s
                                                            a')}}</strong>
                                                    </h4>

                                                </div>
                                                @if($orderDetails->driverOrder)
                                                <div class=" col-12">
                                                    @if($orderDetails->driverOrder->accepted_at) <h4>Order is in process

                                                        <strong
                                                            class=" float-right">{{Carbon\Carbon::parse($orderDetails->driverOrder->accepted_at)->format('d
                                                            M Y H:i:s a')}}</strong>
                                                    </h4>
                                                    @endif
                                                </div>
                                                <div class=" col-12">
                                                    @if($orderDetails->driverOrder->shiped_at) <h4>Order out for
                                                        delivery
                                                        <strong
                                                            class=" float-right">{{Carbon\Carbon::parse($orderDetails->driverOrder->shiped_at)->format('d
                                                            M Y H:i:s a')}}</strong>
                                                    </h4>
                                                    @endif
                                                </div>
                                                <div class=" col-12">
                                                    @if($orderDetails->driverOrder->delivered_at) <h4>Order Completed
                                                        <strong
                                                            class=" float-right">{{Carbon\Carbon::parse($orderDetails->driverOrder->delivered_at)->format('d
                                                            M Y
                                                            H:i:s a')}}</strong>
                                                    </h4>
                                                    @endif
                                                </div>
                                                @endif
                                                <div class=" col-12">
                                                    @if($orderDetails->order_status==5) <h4>Order Completed
                                                        <strong
                                                            class=" float-right">{{Carbon\Carbon::parse($orderDetails->cancelled_at)->format('d
                                                            M Y
                                                            H:i:s a')}}</strong>
                                                    </h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @if ($orderDetails->orderProduct && count($orderDetails->orderProduct))
                            @foreach($orderDetails->orderProduct as $key => $product)


                            <div class="col-md-6">
                                <div class="ibox">
                                    <div class="ibox-content product-box">
                                        <div class="product-imitation">
                                            <div id="carouselExampleControls" class="carousel slide"
                                                data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @if($product->productDetails->productImages &&
                                                    count($product->productDetails->productImages))
                                                    @foreach ($product->productDetails->productImages as $k=> $image)
                                                    <div class="carousel-item {{$k==0?'active':''}}">
                                                        <img class="d-block w-100" src="{{$image->product_image}}"
                                                            alt="First slide">
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                                <a class="carousel-control-prev" href="#carouselExampleControls"
                                                    role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carouselExampleControls"
                                                    role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-desc">
                                            <span class="product-price">
                                                $
                                                {{$product->productDetails->price-($product->productDetails->price*$product->productDetails->discount/100)}}
                                            </span>

                                            <div class="row">
                                                <div class=" col-12">
                                                    <strong>Product name : </strong>
                                                    {{$product->productDetails->product_name}}</strong>


                                                </div>
                                                <div class=" col-12">
                                                    <strong>Quantity : </strong> {{$product->quantity}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection