@extends('layouts.main')
<style>
    .gallery {

        margin-left: 12rem !important;
    }

    .gallery>img {
        height: 200px;
        width: 200px;
        margin-right: 5px;

    }

    .gallery_old {

        margin-left: 12rem !important;
    }

    .gallery_old>img {
        height: 200px;
        width: 230px;
        /* margin-right: 5px; */

    }
</style>
@section('style')
<link rel="stylesheet" href="{{asset('dropify/css/dropify.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="{{route('product')}}"> {{__('StaticWords.vendor.products')}} </a>/ {{__('StaticWords.vendor.edit_product')}} </h3>
            </div>

            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" action="{{route('product.edit')}}" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <input type="hidden" name="language" value="{{\App\Language::where('country',auth()->user()->country)->first()->language_code}}">
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <input type="hidden" name="deletedImageId" id="deletedImageId">
                        <div class=" ml-4">
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_name_en')}}</b></label>

                                <div class="col-sm-6">
                                    <input type="text"
                                        value="{{$product->product_name?$product->product_name:old('product_name')}}"
                                        name="product_name" class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_product_name_en')}}">
                                    @error('product_name')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.sub_cat')}}</b></label>
                                <div class="col-sm-6">
                                    <select class="form-control m-b" name="category">
                                        <option value="">{{__('StaticWords.select_category')}}</option>
                                        @foreach ($subcategories as $subcat)
                                        <option value="{{$subcat->id}}" {{$subcat->
                                            id==$product->sub_category->id?'selected':''}}>
                                            {{$subcat->subcategory_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.price')}}</b></label>

                                <div class="col-sm-6"><input type="number" min="0"
                                        value="{{$product->price?$product->price:old('price')}}" name="price"
                                        class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_price')}}">
                                    @error('price')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.discount')}}</b></label>
                                <div class="col-sm-6"><input type="number" min="0" value="{{$product->discount?$product->discount:old('discount')}}"
                                        name="discount" class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_discount')}}">
                                    @error('discount')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.delivery_charge')}}</b></label>
                                <div class="col-sm-6"><input type="number" min="0" value="{{$product->delivery_charge?$product->delivery_charge:old('delivery_charge')}}"
                                        name="delivery_charge" class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_delivery_charge')}}">
                                    @error('delivery_charge')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.size')}}</b></label>

                                <div class="col-sm-6">
                                    <input type="text" value="{{$product->size?$product->size:old('size')}}" name="size"
                                        class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_size')}}">
                                    @error('size')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.quantity')}}</b></label>
                                <div class="col-sm-6"><input type="number"
                                        value="{{$product->quantity?$product->quantity:old('quantity')}}"
                                        name="quantity" min="0" class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_quantity')}}">
                                    @error('quantity')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_status')}}</b></label>
                                <div class="col-sm-6">
                                    <input type="checkbox" name="product_status" class="js-switch1"
                                        {{$product->product_status==1?'checked':''}}>
                                    @error('product_status')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.description')}}</b></label>

                                <div class="col-sm-6"><textarea name="description" class="form-control"
                                        placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{$product->description?$product->description:old('description')}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.other_info')}}</b></label>

                                <div class="col-sm-6"><textarea name="other_info" class="form-control"
                                        placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{$product->other_info?$product->other_info:old('other_info')}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_image')}}</b></label>

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
                                <div class="col-sm-3">

                                </div>
                                <div class="col-sm-3">
                                    <label for="input-file-now"></label>
                                    <input type="file" name="images[]" id="input-file-now" class="dropify" />

                                </div>
                                <div class="col-sm-3">
                                    <label for="input-file-now"></label>
                                    <input type="file" name="images[]" id="input-file-now" class="dropify" />

                                </div>
                                @error('images')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>

                            <div class="form-group  row">
                                @if(count($product->productImages))
                                @foreach($product->productImages as $image)
                                <div class="col-sm-3" id="oldImg{{$image->id}}">
                                    <div class="gallery_old">
                                        <img src="{{$image->product_image}}" alt="img">
                                        <button type="button" class="btn btn-danger mt-2 mb-4"
                                            onclick="getImgId({{$image->id}})">Remove</button>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <hr>
                            <div class="ml-4">
                                @if(\App\Language::where('country',auth()->user()->country)->first()->language_code == 'es')
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_name_es')}}</b></label>
                                        <div class="col-sm-6">
                                            <input type="text"
                                                value="{{$product->product_name_es?$product->product_name_es:old('product_name_es')}}"
                                                name="product_name_es" class="form-control"
                                                placeholder="{{__('StaticWords.vendor.product.enter_product_name_es')}}">
                                            @error('product_name_es')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.description_es')}}</b></label>

                                        <div class="col-sm-6"><textarea name="description_es" class="form-control"
                                                placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{$product->description_es?$product->description_es:old('description_es')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.other_info_es')}}</b></label>

                                        <div class="col-sm-6"><textarea name="other_info_es" class="form-control"
                                                placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{$product->other_info_es?$product->other_info_es:old('other_info_es')}}</textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_name_pt')}}</b></label>
                                        <div class="col-sm-6">
                                            <input type="text"
                                                value="{{$product->product_name_pt?$product->product_name_pt:old('product_name_pt')}}"
                                                name="product_name_pt" class="form-control"
                                                placeholder="{{__('StaticWords.vendor.product.enter_product_name_pt')}}">
                                            @error('product_name_pt')
                                            <p class="text-danger" role="alert">
                                                <strong id="error">{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.description_pt')}}</b></label>

                                        <div class="col-sm-6"><textarea name="description_pt" class="form-control"
                                                placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{$product->description_pt?$product->description_pt:old('description_pt')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.other_info_pt')}}</b></label>

                                        <div class="col-sm-6"><textarea name="other_info_pt" class="form-control"
                                                placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{$product->other_info_pt?$product->other_info_pt:old('other_info_pt')}}</textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group  row">
                                <div class="col-sm-12">
                                    <input type="submit" class=" btn btn-primary float-right" value="{{__('StaticWords.update')}}">
                                </div>
                            </div>
                        </div>
                </div>

                </form>
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
</script>
<script>
    //validation
$(document).ready(function () {  
        if("{{Session::get('locale')}}"=='en')
        {
            $('#myForm').validate({ // initialize the plugin            
                rules: {
                    product_name: {
                        required: true,
                    
                    }, 
                    // product_name_es: {
                    //     required: true,
                    
                    // }, 
                    category: {
                        required: true,
                    
                    },
                    
                    quantity: {
                        required: true,
                    
                    },
                
                    price: {
                        required: true,
                    
                    },                  
                    
                    description: {
                        required: true,
                    
                    },
                    other_info: {
                        required: true,
                    
                    },
                    size: {
                        required: true,
                    
                    },
                    discount: {
                        number: true,
                        max:100
                    
                    },                           
                },messages:{
                    product_name:{
                        required:'Product name is required',
                    },
                    // product_name_es:'Product name in spanish is required'
                }
            });
        }else{
            $('#myForm').validate({ // initialize the plugin
            rules: {
                // product_name: {
                //     required: true,
                
                // }, 
                product_name_es: {
                    required: true,
                
                }, 
                category: {
                    required: true,
                
                },
                
                quantity: {
                    required: true,
                
                },
            
                price: {
                    required: true,
                
                },
                discount: {
                        number: true,
                        max:100
                    
                },             
                
                other_info_es: {
                    required: true,
                
                },
                description_es: {
                    required: true,
                
                },
                size: {
                    required: true,
                
                },                          
            },
            messages:{                
                product_name_es:{
                    required:'Se requiere el nombre del producto en español.',
                },
                category:{
                    required:'Este campo es requerido',
                },
                quantity:{
                    required:'Este campo es requerido',
                },
                price:{
                    required:'Este campo es requerido',
                },                
                description_es:{
                    required:'Este campo es requerido',
                },
                description_es:{
                    required:'Este campo es requerido',
                },
                size:{
                    required:'Este campo es requerido',
                }
            }
        });
        }
    

});
var elem = document.querySelector('.js-switch1');
var switchery = new Switchery(elem, { color: '#1AB394' });

var deletedImageId=[];
function getImgId(id)
{
    if (deletedImageId.indexOf(id) == -1) {
        deletedImageId.push(id);
    }
    $('#oldImg'+id).css('display','none');
    $('#deletedImageId').val(deletedImageId);
}
</script>
@endsection