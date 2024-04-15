@extends('admin.layout.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h3><a href="{{route('admin.coupon.index')}}"> Coupon Codes </a>/ Edit Coupon Code </h3>
                </div>
                <div class="ibox">
                    <div class="ibox-content">
                        <form method="POST" action="{{route('admin.coupon.update',['coupon'=>encrypt($couponDetails->id)])}}" id="admin-coupon-code-form">
                            @csrf
                            @method('put')
                            <input type="hidden" name="language" value="{{Session::get('locale')}}">
                            <div class=" ml-4">
                                <div class="form-group row"><label
                                        class="col-sm-3 col-form-label"><b>Coupon Code :</b></label>

                                    <div class="col-sm-6">
                                        <input type="text" value="{{ $couponDetails->coupon_code }}" name="coupon_code" class="form-control" placeholder="Enter coupon code" oninput="this.value = this.value.toLocaleUpperCase()">
                                        @error('coupon_code')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row"><label
                                        class="col-sm-3 col-form-label"><b>Discount Type :</b></label>

                                    <div class="col-sm-6">
                                        <select class="form-control" name="discount_type" id="discount_type">
                                            <option value="">Select Discount Type</option>
                                            <option value="flat" @if($couponDetails->discount_type === 'flat') selected @endif>Flat</option>
                                            <option value="percentage" @if($couponDetails->discount_type === 'percentage') selected @endif>Percentage</option>
                                        </select>
                                        @error('product_name_es')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>Discount Value :</b></label>

                                    <div class="col-sm-6">
                                        <input type="text" value="{{ $couponDetails->discount_value }}" name="discount_value" class="form-control" placeholder="Enter discount value" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                        @error('discount_value')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>Minimum Cart Value :</b></label>

                                    <div class="col-sm-6">
                                        <input type="text" value="{{ $couponDetails->min_cart_value }}" name="min_cart_value" class="form-control" placeholder="Enter minimum cart value to apply coupon code">
                                        @error('min_cart_value')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>Maximum Discount Value : (Required if discount type is percentage)</b></label>

                                    <div class="col-sm-6">
                                        <input type="text" value="{{ $couponDetails->maximum_discount }}" name="maximum_discount"
                                            class="form-control" placeholder="Enter maximum discount value">
                                        @error('maximum_discount')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>Start Date :</b></label>

                                    <div class="col-sm-6">
                                        <input type="date" value="{{ $couponDetails->start_date }}" name="start_date" class="form-control" placeholder="Enter/Select coupon start date">
                                        @error('start_date')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>End Date :</b></label>

                                    <div class="col-sm-6">
                                        <input type="date" value="{{ $couponDetails->end_date }}" name="end_date" class="form-control" placeholder="Enter/Select coupon end date">
                                        @error('end_date')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>
                                </div>
                                
                                

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.description')}}</b></label>

                                    <div class="col-sm-6"><textarea name="description" class="form-control"
                                            placeholder="write here ...">{{ $couponDetails->description }}</textarea>
                                    </div>
                                </div>
                                <div class=" form-group row">
                                    <div class="col-sm-3">
                                        @error('images')
                                        <p class="text-danger" role="alert">
                                            <strong id="error">{{ $message }}</strong>
                                        </p>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-group  row">
                                    <div class="col-sm-12">
                                        <input type="submit" class=" btn btn-primary float-right" value="Update">
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection