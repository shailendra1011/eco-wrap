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
</style>
@section('style')
<link rel="stylesheet" href="{{asset('dropify/css/dropify.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="{{route('product')}}"> {{__('StaticWords.vendor.products')}} </a>/ {{__('StaticWords.vendor.add_new_product')}} </h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" action="{{route('product.add')}}" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <input type="hidden" name="language" value="{{\App\Language::where('country',auth()->user()->country)->first()->language_code}}">
                        <div class=" ml-4">
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_name_en')}}</b></label>

                                <div class="col-sm-6">
                                    <input type="text" value="{{old('product_name')}}" name="product_name"
                                        class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_product_name_en')}}">
                                    @error('product_name')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.sub_cat')}}</b></label>

                                <div class="col-sm-6">
                                    <select class="form-control m-b" name="category">
                                        <option value="">{{__('StaticWords.select_category')}}</option>
                                        @foreach ($subcategories as $subcat)
                                        <option value="{{$subcat->id}}">{{$subcat->subcategory_name}}</option>
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

                                <div class="col-sm-6"><input type="number" min="0" value="{{old('price')}}" name="price"
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
                                <div class="col-sm-6"><input type="number" min="0" max="2" value="{{old('discount')}}"
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
                                <div class="col-sm-6"><input type="number" min="0" value="{{old('delivery_charge')}}"
                                        name="delivery_charge" class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_delivery_charge')}}">
                                    @error('delivery_charge')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.sachet')}}</b></label>

                                <div class="col-sm-6">
                                    <input type="text" value="{{old('sachet_capsule')}}" name="sachet_capsule"
                                        class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_sachet')}}">
                                    @error('sachet_capsule')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.quantity')}}</b></label>
                                <div class="col-sm-6"><input type="number" value="{{old('quantity')}}" name="quantity"
                                        min="0" class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_quantity')}}">
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
                                    <input type="checkbox" name="product_status" class="js-switch1">
                                    @error('product_status')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.manufacturer_name_en')}}</b></label>

                                <div class="col-sm-6"><textarea name="manufacturer_name" class="form-control"
                                        placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{old('manufacturer_name')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.ingredients_en')}}</b></label>

                                <div class="col-sm-6"><textarea name="ingredients" class="form-control"
                                        placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{old('ingredients')}}</textarea>
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.direction_to_use_en')}}</b></label>

                                <div class="col-sm-6"><textarea name="direction_to_use" class="form-control"
                                        placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{old('direction_to_use')}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.description_en')}}</b></label>

                                <div class="col-sm-6"><textarea name="description" class="form-control"
                                        placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{old('description')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.other_info_en')}}</b></label>
                                    
                                    <div class="col-sm-6"><textarea name="other_info" class="form-control"
                                    placeholder="{{__('StaticWords.vendor.product.write_here_en')}}">{{old('other_info')}}</textarea>
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
                        </div>

                        <div class=" ml-4">
                            @if(\App\Language::where('country',auth()->user()->country)->first()->language_code == 'es')
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_name_es')}}</b></label>
                                    <div class="col-sm-6">
                                        <input type="text" value="{{old('product_name_es')}}" name="product_name_es"
                                            class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_product_name_es')}}">
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
                                            placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{old('description_es')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.other_info_es')}}</b></label>
                                    <div class="col-sm-6"><textarea name="other_info_es" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{old('other_info_es')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.manufacturer_name_es')}}</b></label>
                                    <div class="col-sm-6"><textarea name="manufacturer_name_es" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{old('manufacturer_name_es')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.ingredients_es')}}</b></label>
                                    <div class="col-sm-6"><textarea name="ingredients_es" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{old('ingredients_es')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.direction_to_use_es')}}</b></label>

                                    <div class="col-sm-6"><textarea name="direction_to_use_es" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_es')}}">{{old('direction_to_use_es')}}</textarea>
                                    </div>
                                </div>
                            @else
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.product_name_pt')}}</b></label>
                                    <div class="col-sm-6">
                                        <input type="text" value="{{old('product_name_pt')}}" name="product_name_pt"
                                            class="form-control" placeholder="{{__('StaticWords.vendor.product.enter_product_name_pt')}}">
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
                                            placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{old('description_pt')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.other_info_pt')}}</b></label>
                                    <div class="col-sm-6"><textarea name="other_info_pt" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{old('other_info_pt')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.manufacturer_name_pt')}}</b></label>
                                    <div class="col-sm-6"><textarea name="manufacturer_name_pt" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{old('manufacturer_name_pt')}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.ingredients_pt')}}</b></label>
                                    <div class="col-sm-6"><textarea name="ingredients_pt" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{old('ingredients_pt')}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label"><b>{{__('StaticWords.vendor.direction_to_use_pt')}}</b></label>

                                    <div class="col-sm-6"><textarea name="direction_to_use_pt" class="form-control"
                                            placeholder="{{__('StaticWords.vendor.product.write_here_pt')}}">{{old('direction_to_use_pt')}}</textarea>
                                    </div>
                                </div>
                            @endif

                        <div class="form-group  row">
                            <div class="col-sm-12">
                                <input type="submit" class=" btn btn-primary float-right" value="{{__('StaticWords.save')}}">
                            </div>
                        </div>
                    </form>
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
                    "images[]": {
                        required: true,
                    
                    },
                    
                    description: {
                        required: true,
                    
                    },
                    other_info: {
                        required: true,
                    
                    },
                    sachet_capsule: {
                        required: true,
                    
                    },
                    manufacturer_name: {
                        required: true,
                    
                    },
                    ingredients: {
                        required: true,
                    
                    }, 
                    direction_to_use: {
                        required: true,
                    
                    },
                    discount: {
                        number:true,
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
                "images[]": {
                    required: true,
                
                },
                
                other_info_es: {
                    required: true,
                
                },
                description_es: {
                    required: true,
                
                },
                sachet_capsule: {
                    required: true,
                
                },
                manufacturer_name_es: {
                        required: true,
                    
                    },
                    ingredients_es: {
                        required: true,
                    
                    }, 
                    direction_to_use_es: {
                        required: true,
                    
                    }, 
                    discount: {
                        number:true,
                        max:100

                    },                           
            },
            messages:{
                // product_name:{
                //     required:'Se requiere el nombre del producto.',
                // },
                product_name_es:{
                    required:'Se requiere el nombre del producto en español.',
                }
            }
        });
        }
    

});
var elem = document.querySelector('.js-switch1');
var switchery = new Switchery(elem, { color: '#1AB394' });
</script>
@endsection