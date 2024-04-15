@extends('layouts.main')
@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Current Plan</div>
                <div class="card-body">
                    <ul class="list-group">

                        <li class="list-group-item clearfix">
                            <div class="pull-left">
                                <h4>Subscribed Plan : {{$data->is_trial?'Trial':'Premium'}}</h4>
                                @if($data->is_trial)
                                <h4>Remaining days: {{$data->is_trial?$data->remaining_days:''}}
                                </h4>
                                @endif
                                <h4>Plan status: {{$data->is_trial && $data->remaining_days==0?'Expired':'Active'}}</h4>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Plans</div>
                <div class="card-body">
                    <ul class="list-group">
                        
                        <li class="list-group-item clearfix">
                            <div class="pull-left">
                                <h5>{{ $plans->name }}</h5>
                                <h5>${{ number_format($plans->cost, 2) }} monthly</h5>
                                <h5>{{ $plans->description }}</h5>
                                @if($plans->isSubscribed)
                                {{-- <a href="{{ route('subscription.cancel')}}"
                                    class="btn btn-outline-dark pull-right">Cancel</a> --}}
                                @else
                                <a href="{{ route('plans.show', $plans->slug) }}"
                                    class="btn btn-outline-dark pull-right">Buy</a>
                                @endif
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection