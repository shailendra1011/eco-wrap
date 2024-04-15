@extends('admin.layout.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Vendor Plan Management</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-10 float-left">
                            <div class="ibox float-e-margins no-border">
                                <div class="ibox-content" style="border: none;">
                                    <h2 class="no-margins" style="color:#1ab394;">Total Plans : {{$plans->count()}}<span
                                            id="data_count"></span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if($plans->count()<0) <div class="col-md-4">
                            <div class="card-body">
                                <form action="{{route('plan.save')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="plan name">Plan Name:</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Enter Plan Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cost">Cost:</label>
                                        <input type="text" class="form-control" name="cost" placeholder="Enter Cost"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cost">Plan Description:</label>
                                        <input type="text" class="form-control" name="description"
                                            placeholder="Enter Description" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                                </form>
                            </div>
                    </div>
                    @endif
                    <div class="col-8">
                        <div class="row">
                            @if($plans->count())
                            @foreach ($plans as $plan)
                            <div class="col-lg-6">
                                <div class="widget style1 navy-bg">
                                    <div class="row">
                                        <div class="col-3">
                                            <i class="fa fa-product-hunt fa-5x"></i>
                                        </div>
                                        <div class="col-9 text-right">
                                            <div class="row">
                                                <div class="col-6">
                                                    Plan name :
                                                </div>
                                                <div class="col-6">
                                                    {{$plan->name}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    Plan price :
                                                </div>
                                                <div class="col-6">
                                                    ${{$plan->cost}}
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