@extends('layouts.main')
@section('content')
<div class="row m-t-n-md">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3><a href="{{route('home')}}"> {{__('StaticWords.home')}} </a>/
                            Your Business Account </h3>

                            <span>To accept online payment</span>
                    </div>
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="col-md-12">
                                <div class="form-group  row">

                                    <div class="col-sm-12">
                                        @if ($errors->any())
                                        <h4 class="text-danger"> {{ $errors->first() }} </h4>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <img alt="image" class="rounded-circle"
                                                        src="{{Auth::user() && count(App\Models\StoreImage::where('store_id',Auth::user()->id)->get())?App\Models\StoreImage::where('store_id',Auth::user()->id)->inRandomOrder()->first()->store_image:asset('admin/userprofile.png')}}"
                                                        height="60" width="60" />
                                                </div>
                                                <div class="text-center">
                                                    <div class="py-2">
                                                        <h3 class="font-bold ml-2"> {{ $seller->store_name }} </h3>

                                                        <br>
                                                        @if (!$seller->completed_stripe_onboarding)
                                                        <p class="inline-flex text-center ">
                                                            Not Connected </p>
                                                        <br />
                                                        <p class="font-semibold text-yellow-500 "> Please connect
                                                            your Stripe account </p>
                                                        @else
                                                        <p class="inline-flex text-center ">
                                                            Connected </p>
                                                        <h1 href="#" class="font-semibold text-xl text-indigo-500 ">
                                                            £{{
                                                            $balance }} </h1>

                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <a type="button"
                                                        href="{{ route('redirect.stripe', ['id' => $seller->id]) }}"
                                                        class=" btn btn-primary rounded">
                                                        <i class="fa fa-external-link" aria-hidden="true"></i>
                                                        &nbsp;
                                                        @if ($seller->completed_stripe_onboarding)
                                                        View Stripe Account
                                                        @else
                                                        Connect Stripe Account
                                                        @endif
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection