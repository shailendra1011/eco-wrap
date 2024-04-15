@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('StaticWords.register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <input type="hidden" name="language"
                            value="{{Session::has('locale')?Session::get('locale'):'en'}}">
                        <div class="form-group row">
                            <label for="store_image"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.vendor.upload_store_image') }}</label>

                            <div class="col-md-6">
                                <input id="store_image" type="file"
                                    class="form-control @error('store_image') is-invalid @enderror" name="store_image"
                                    accept=".png, .jpg, .jpeg" value="{{ old('store_image') }}"
                                    autocomplete="store_image" autofocus placeholder="'hi">

                                @error('store_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="store_name"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.vendor.store_name_en') }}</label>

                            <div class="col-md-6">
                                <input id="store_name" type="text"
                                    class="form-control @error('store_name') is-invalid @enderror" name="store_name"
                                    value="{{ old('store_name') }}" autocomplete="store_name" autofocus>

                                @error('store_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for=""
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.vendor.store_mobile') }}</label>

                            <div class="col-md-2">
                                <input id="store_country_code" type="text"
                                    class="form-control @error('store_country_code') is-invalid @enderror"
                                    name="store_country_code" value="{{ old('store_country_code') }}"
                                    autocomplete="store_country_code" autofocus>

                                @error('store_country_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input id="store_mobile" type="text"
                                    class="form-control @error('store_mobile') is-invalid @enderror" name="store_mobile"
                                    value="{{ old('store_mobile') }}" autocomplete="store_mobile" autofocus>

                                @error('store_mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="store_url"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.vendor.store_url') }}</label>

                            <div class="col-md-6">
                                <input id="store_url" type="text"
                                    class="form-control @error('store_url') is-invalid @enderror" name="store_url"
                                    value="{{ old('store_url') }}" autocomplete="store_url" autofocus>

                                @error('store_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.email_address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current_location"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.current_location') }}</label>

                            <div class="col-md-6">
                                <input id="current_location" type="text" readonly
                                    class="form-control pickup_country @error('current_location') is-invalid @enderror"
                                    name="current_location" value="{{ old('current_location') }}"
                                    autocomplete="current_location">

                                @error('current_location')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <input type="hidden" name="store_latitude" id="latitude"
                                    value="{{ old('store_latitude') }}">
                                <input type="hidden" name="store_longitude" id="longitude"
                                    value="{{ old('store_longitude') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="country"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.select_country') }}</label>
                            @php
                                $countries=\App\Language::select('country')->get();
                            @endphp
                            <div class="col-md-6">
                                <select name="country" id="country"
                                    class="form-control @error('country') is-invalid @enderror">
                                    <option value="">{{__('StaticWords.select_country')}}</option>
                                    @foreach ($countries as $language)
                                    <option value="{{$language->country}}">{{$language->country}}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.select_category') }}</label>
                            @php
                            $categories=\App\Models\Admin\Category::get();
                            @endphp
                            <div class="col-md-6">
                                <select name="category" id="category"
                                    class="form-control @error('category') is-invalid @enderror">
                                    <option value="">{{__('StaticWords.select_category')}}</option>
                                    @if(Session::get('locale')=='en')
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                    @endforeach
                                    @else
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category_name_es}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm"
                                class="col-md-4 col-form-label text-md-right">{{ __('StaticWords.c_password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('StaticWords.register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/googlemap.js')}}"></script>
<script>
    $(".pickup_country").PlacePicker({
        btnClass: "btn btn-secondary btn-sm",
        key: "AIzaSyAaF3rLQhTxzUBiUhJqokkZUeAVUczrQK8",
        center: {
        lat: 36.778259,
        lng: -119.417931
        },
        title: "Select Location",
        success: function (data, address, showId) {    

            $("#latitude" + showId).val(data.latitude);
            $("#longitude" + showId).val(data.longitude);
            $("#country" + showId).val(data.country);
            $("#state" + showId).val(data.administrative_area_level_1)
            $("#city" + showId).val(data.locality);
            $("#postal_code" + showId).val(data.postal_code);
            $("#" + showId).val(data.formatted_address);
        

            $('#latitude').val(data.latitude);
            $('#longitude').val(data.longitude);
            $('#current_location').val(data.formatted_address);
            



        }
});


//validation
$(document).ready(function () {  
        if("{{Session::get('locale')}}"=='en')
        {
            $('#myForm').validate({ // initialize the plugin
                rules: {
                    store_image: {
                        required: true,
                    
                    },
                     
                    store_name: {
                        required: true,
                    
                    },
                    
                    store_country_code: {
                        required: false,
                    
                    },
                
                    store_mobile: {
                        required: true,
                    
                    },
                   
                    
                    email: {
                        required: true,
                    
                    },
                //    current_location: {
                //         required: true,
                    
                //     },
                   
                    category: {
                        required: true,
                    
                    },
                    password:{
                        required:true
                    } ,
                    password_confirmation:{
                        required:true,
                        equalTo: "#password"
                    }                       
                }
            });
        }else{
            $('#myForm').validate({ // initialize the plugin
                rules: {
                    store_image: {
                        required: true,
                    
                    },
                     
                    store_name: {
                        required: true,
                    
                    },
                    
                    store_country_code: {
                        required: true,
                    
                    },
                
                    store_mobile: {
                        required: true,
                    
                    },
                   
                    
                    email: {
                        required: true,
                    
                    },
                //    current_location: {
                //         required: true,
                    
                //     },
                   
                    category: {
                        required: true,
                    
                    },
                    password:{
                        required:true
                    } ,
                    password_confirmation:{
                        required:true,
                        equalTo: "#password"
                    }                       
                },
            messages:{
               
                store_image:{
                    required:'Este campo es obligatorio',
                },
                store_name:{
                    required:'Este campo es obligatorio',
                },
                email:{
                    required:'Este campo es obligatorio',
                },
                store_country_code:{
                    required:'Este campo es obligatorio',
                },
                store_mobile:{
                    required:'Este campo es obligatorio',
                },
                category:{
                    required:'Este campo es obligatorio',
                },
                password:{
                    required:'Este campo es obligatorio',
                },
                password_confirmation:{
                    required:'Este campo es obligatorio',
                },
            }
        });
        }
    

});
</script>
@endsection