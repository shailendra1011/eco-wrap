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
                                <div class="form-group  row">
                                    @if(count($store->store_images))
                                    @foreach($store->store_images as $image)
                                    <div class="col-sm-3" id="oldImg{{$image->id}}">
                                        <div class="img-wrap">
                                            <span class="close" onclick="getImgId({{$image->id}})">&times;</span>
                                            <img src="{{$image->store_image}}" class="circle-border" height="200"
                                                width="200">
                                        </div>
                                        {{-- <div class="gallery_old">
                                            <img src="{{$image->store_image}}" alt="img" class="circle-border"
                                                height="200" width="200">
                                            <button type="button" class="btn btn-danger mt-2 mb-4"
                                                onclick="getImgId({{$image->id}})">Remove</button>
                                        </div> --}}
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data"
                                    id="updateProfile">
                                    @csrf
                                    <input type="hidden" name="deletedImageId" id="deletedImageId">
                                    <input type="hidden" name="store_id" id="store_id" value="{{$store->id}}">
                                    <input type="hidden" name="language" id="language"
                                        value="{{Session::get('locale')}}">

                                    <div class="form-group  row"><label
                                            class="col-sm-3 col-form-label">{{__('StaticWords.vendor.upload_store_image')}}</label>

                                        <div class="col-sm-3">
                                            <label for="input-file-now"></label>
                                            <input type="file" name="images[]" id="input-file-now" class="dropify" />

                                        </div>
                                        <div class="col-sm-3">
                                            <label for="input-file-now"></label>
                                            <input type="file" name="images[]" id="input-file-now" class="dropify" />

                                        </div>
                                        <div class="col-sm-3">
                                            <label for="input-file-now"></label>
                                            <input type="file" name="images[]" id="input-file-now" class="dropify" />

                                        </div>
                                    </div>
                                    <div class="form-group  row"><label
                                            class="col-sm-4 col-form-label">{{__('StaticWords.vendor.store_name')}}</label>
                                        <div class="col-sm-7"><input type="text" value="{{$store->store_name}}"
                                                name="store_name" class="form-control" placeholder="Enter store name">
                                            @error('store_name')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  row"><label
                                            class="col-sm-4 col-form-label">{{__('StaticWords.vendor.store_name_es')}}</label>
                                        <div class="col-sm-7"><input type="text" value="{{$store->store_name_es}}"
                                                name="store_name_es" class="form-control"
                                                placeholder="Enter store name in spanish">
                                            @error('store_name_es')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  row"><label
                                            class="col-sm-4 col-form-label">{{__('StaticWords.vendor.store_mobile')}}</label>
                                        <div class="col-sm-1"><input type="text" value="{{$store->store_country_code}}"
                                                name="store_country_code" class="form-control"
                                                placeholder="Enter your mobile no">
                                            @error('store_country_code')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6"><input type="text" minlength="10" maxlength="10"
                                                value="{{$store->store_mobile}}" name="store_mobile"
                                                class="form-control" placeholder="Enter your mobile no">
                                            @error('store_mobile')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  row">
                                        <div class="col-sm-11">
                                            <input type="submit" class=" btn btn-primary float-right" value="Update">
                                        </div>
                                    </div>
                                </form>

                                <hr>
                                <form action="{{route('password.update')}}" method="POST" id="updatePassword">
                                    @csrf
                                    <input type="hidden" name="language" id="language"
                                        value="{{Session::get('locale')}}">
                                    <div class="form-group  row"><label
                                            class="col-sm-4 col-form-label">{{__('StaticWords.old_password')}}
                                        </label>
                                        <div class="col-sm-7"><input type="password" minlength="8"
                                                value="{{old('old_password')}}" name="old_password" class="form-control"
                                                placeholder="Enter your old password">
                                            @error('old_password')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  row"><label
                                            class="col-sm-4 col-form-label">{{__('StaticWords.new_password')}}
                                        </label>
                                        <div class="col-sm-7"><input type="password" minlength="8"
                                                value="{{old('password')}}" name="password" id="password"
                                                class="form-control" placeholder="Enter your new password">
                                            @error('password')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="form-group  row"><label
                                            class="col-sm-4 col-form-label">{{__('StaticWords.cnf_password')}}
                                        </label>
                                        <div class="col-sm-7"><input type="password" minlength="8"
                                                value="{{old('password_confirmation')}}" name="password_confirmation"
                                                class="form-control" placeholder="Enter your confirm password">
                                            @error('password_confirmation')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="form-group  row">
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
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove:  'Supprimer',
                error:   'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element){
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element){
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element){
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e){
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });

    var deletedImageId=[];
function getImgId(id)
{
    if (deletedImageId.indexOf(id) == -1) {
        deletedImageId.push(id);
    }
    $('#oldImg'+id).css('display','none');
    $('#deletedImageId').val(deletedImageId);
}



//validation for profile
$(document).ready(function () {  
    if("{{Session::get('locale')}}"=='en')
    {
        $('#updateProfile').validate({ // initialize the plugin
            rules: {
                store_name: {
                    required: true,
                
                },                    
                store_country_code: {
                    required: true,
                
                },                    
                store_mobile: {
                    required: true,
                
                }
            }
        });

    }else{
        $('#updateProfile').validate({ // initialize the plugin
            rules: {
                store_name_es: {
                    required: true,
                
                },                    
                store_country_code: {
                    required: true,
                
                },                    
                store_mobile: {
                    required: true,
                
                }
            }
        });
    }
});

//validation for password
$(document).ready(function () {  
    if("{{Session::get('locale')}}"=='en')
    {
        $('#updatePassword').validate({ // initialize the plugin
            rules: {
                old_password: {
                    required: true,
                
                },                    
                password: {
                    required: true,
                
                },                    
                password_confirmation: {
                    required: true,
                    equalTo : "#password"
                
                }
            }
        });

    }else{
        $('#updatePassword').validate({ // initialize the plugin
            rules: {
                old_password: {
                    required: true,
                
                },                    
                password: {
                    required: true,
                
                },                    
                password_confirmation: {
                    required: true,
                    equalTo : "#password"
                
                }
            }
        });
    }
});
</script>
@endsection