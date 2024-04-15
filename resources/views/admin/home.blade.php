@extends('admin.layout.app')
@section('content')
<style>
    .my-card {
        position: absolute;
        left: 40%;
        top: -20px;
        border-radius: 50%;
    }
</style>

<div class="row m-t-n-md">
    <div class="col-lg-12">
        <div class="wrapper wrapper-content" style="min-height: 575px;">
            <div class="row">
                <div class="col-lg-4">
                    <div class="widget style1 navy-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> Today Users </span>
                                <h2 class="font-bold">{{$count->user}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> Today Vendors </span>
                                <h2 class="font-bold">{{$count->store}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="widget style1 yellow-bg">
                        <div class="row">
                            <div class="col-4">
                                <i class="fa fa-euro fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> Total Earnings </span>
                                <h2 class="font-bold">100</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <h2>Best Selling Product</h2>
                    </div>
                </div>
                <div class="table-responsive" style="margin-bottom: 20px">
                    <table class="table table-striped  table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vendor Image</th>
                                <th>Vendor Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Product Name</th>
                                <th>Total Sale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $value)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td><img src="{{$value->vendor_image}}" alt="" class=" circle-border" height="50" width="50"></td>
                                <td>{{$value->vendor_name}}</td>
                                <td>{{$value->email}}</td>
                                <td>{{$value->mobile}}</td>
                                <td>{{$value->product_name}}</td>
                                <td>{{$value->total_sales}}</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection