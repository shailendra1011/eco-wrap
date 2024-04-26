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
                                    class="col-sm-3 col-form-label"><b>Service Name</b></label>

                                <div class="col-sm-6">
                                    <input type="text" value="{{old('service_name')}}" name="service_name"
                                        class="form-control" placeholder="Enter Service Name">
                                    @error('service_name')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"><b>Description</b></label>
                                <div class="col-sm-6"><textarea name="product_description" class="form-control" placeholder="Enter Description Here...">{{old('product_description')}}</textarea>
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>Example</b></label>
                                <div class="col-sm-6"><input type="text" min="0" value="{{old('example')}}" name="example"
                                        class="form-control" placeholder="Write Example Here...">
                                    @error('example')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>Price</b></label>
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
                                    class="col-sm-3 col-form-label"><b>On Demand Design</b></label>
                                <div class="col-sm-6"><input type="text"  value="{{old('on_demand_design')}}"
                                        name="on_demand_design" class="form-control" placeholder="Write Here...">
                                    @error('on_demand_design')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>Delivery Time</b></label>

                                <div class="col-sm-6">
                                    <input type="time" value="{{old('delivery_time')}}" name="size" class="form-control">
                                    @error('delivery_time')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
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
                    "images[]": {
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
                "images[]": {
                    required: true,
                
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
                size:{
                    required:'Este campo es requerido',
                }
            }
        });
        }
    

});
var elem = document.querySelector('.js-switch1');
var switchery = new Switchery(elem, { color: '#1AB394' });
</script>
@endsection