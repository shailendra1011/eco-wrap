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
                <h3><a href="{{route('product')}}"> Product </a>/ Add New Product </h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" action="{{route('product.add')}}" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <div class=" ml-4">
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>Product Name</b></label>

                                <div class="col-sm-6">
                                    <input type="text" value="{{old('product_name')}}" name="product_name"
                                        class="form-control" placeholder="Enter Name Here...">
                                    @error('product_name')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>Material</b></label>

                                <div class="col-sm-6"><input type="text" value="{{old('material')}}" name="material"
                                        class="form-control" placeholder="Write Here...">
                                    @error('material')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>Quantity</b></label>
                                <div class="col-sm-6"><input type="number" min="0" max="2" value="{{old('minimum_quantity')}}"
                                        name="minimum_quantity" class="form-control" placeholder="Add Quantity">
                                    @error('minimum_quantity')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>price</b></label>
                                <div class="col-sm-6"><input type="number" min="0" value="{{old('price')}}"
                                        name="price" class="form-control" placeholder="Enter Price Here...">
                                    @error('price')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Contact Info</b></label>
                                <div class="col-sm-6"><input type="number" min="0" value="{{old('contact')}}"
                                        name="contact" class="form-control" placeholder="Write Here...">
                                    @error('contact')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"><b>Address</b></label>
                                <div class="col-sm-6"><textarea name="address" class="form-control"
                                        placeholder="Enter Description Here...">{{old('address')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"><b>Product Image</b></label>
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

                            <div class="form-group row"><label
                                class="col-sm-3 col-form-label"><b>Shipment Time</b></label>
                                <div class="col-sm-6">
                                    <input type="time" value="{{old('delivery_time')}}" name="size" class="form-control">
                                    @error('delivery_time')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                      

                        <div class=" ml-4">                         
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