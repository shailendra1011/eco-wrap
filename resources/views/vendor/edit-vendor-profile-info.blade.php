@extends('layouts.main')

@section('content')
@section('style')
<style>
    .img-wrap {
        position: relative;

    }

    .img-wrap .close {
        position: absolute;
        top: 18px;
        right: 48px;
        z-index: 100;
        color: orangered;

    }

    .close {
        float: right;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }
</style>
<link rel="stylesheet" href="{{asset('dropify/css/dropify.min.css')}}">
@endsection

<div class="row m-t-n-md">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h3><a href="{{route('home')}}"> {{__('StaticWords.home')}} </a>/
                            {{__('StaticWords.edit_profile')}} </h3>
                    </div>
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="col-md-12">
                                <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data"
                                    id="updateProfile">
                                    @csrf
                                    <input type="hidden" name="deletedImageId" id="deletedImageId">
                                    <input type="hidden" name="store_id" id="store_id" value="">
                                    <input type="hidden" name="language" id="language"
                                        value="{{Session::get('locale')}}">

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">GST No.</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="gst_no" class="form-control" placeholder="Enter GST No.">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Aadhar Upload</label>
                                        <div class="col-sm-3">
                                            <input type="file" name="aadhar_upload" class="dropify" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Payment Receiving Mode</label>
                                        <div class="col-sm-6">
                                            <select name="payment_mode" class="form-control">
                                                <option value="bank">Bank Transfer</option>
                                                <option value="upi">UPI</option>
                                                <!-- Add other payment modes as needed -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Bank Name</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="bank_name" class="form-control" placeholder="Enter Bank Name">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">IFSC Code</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="ifsc_code" class="form-control" placeholder="Enter IFSC Code">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Account No.</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="account_no" class="form-control" placeholder="Enter Account No.">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cancelled Cheque Image</label>
                                        <div class="col-sm-3">
                                            <input type="file" name="cancelled_cheque" class="dropify" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">UPI ID</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="upi_id" class="form-control" placeholder="Enter UPI ID">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-11">
                                            <input type="submit" class=" btn btn-primary float-right" value="Update">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('dropify/js/dropify.min.js')}}"></script>
<script>
    $(document).ready(function(){
        // Initialize dropify for file inputs
        $('.dropify').dropify();
    });
</script>
@endsection
