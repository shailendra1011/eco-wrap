@extends('admin.layout.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Vendors Plan</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6 float-left">
                            <div class="ibox float-e-margins no-border">
                                <div class="ibox-content" style="border: none;">
                                    <h2 class="no-margins" style="color:#1ab394;">Total subscribed vendor:
                                        {{count($data)}}<span id="data_count"></span></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 float-left">
                            <div class="ibox float-e-margins no-border">
                                <div class="ibox-content" style="border: none;">
                                    <h2 class="no-margins" style="color:#1ab394;">Total Earning:
                                        $ {{$earning}} <span id="data_count"></span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 20px">
                        <table class="table table-striped table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Driver name</th>
                                    <th>Plan</th>
                                    {{-- <th>Remaining days</th> --}}
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data))
                                @foreach ($data as $key=>$value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value->name}}</td>
                                    @if($value->is_trial)<td>Trial</td>@endif
                                    @if(!$value->is_trial)<td>Premium</td>@endif
                                    <td class="text-success">{{$value->status?'Active':'Inactive'}}</td>
                                </tr>

                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection