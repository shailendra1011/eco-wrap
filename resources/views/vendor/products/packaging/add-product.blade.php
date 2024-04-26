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
                <h3><a href="{{route('product')}}">Product</a> / Add New Product</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" action="{{route('product.add')}}" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <div class=" ml-4">
                            <div class="form-group row"><label
                                    class="col-sm-3 col-form-label"><b>Product name</b></label>

                                <div class="col-sm-6">
                                    <input type="text" value="{{old('product_name')}}" name="product_name"
                                        class="form-control" placeholder="Write Here...">
                                    @error('product_name')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row"><label
                                class="col-sm-3 col-form-label"><b>Product Title</b></label>
                                <div class="col-sm-6">
                                    <input type="text" value="{{old('product_title')}}" name="product_title"
                                        class="form-control" placeholder="Write Here...">
                                    @error('product_title')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                    class="col-sm-3 col-form-label"><b>Product Category</b></label>

                                <div class="col-sm-6"><input type="text" value="{{old('product_category')}}" name="product_category"
                                        class="form-control" placeholder="Write Here...">
                                    @error('product_category')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Product Type</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('product_type')}}" name="product_type"
                                        class="form-control" placeholder="Write Here...">
                                    @error('product_type')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Product Size</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('product_size')}}" name="product_size"
                                        class="form-control" placeholder="Write Here...">
                                    @error('product_size')
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
                                class="col-sm-3 col-form-label"><b>Product Colour</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('product_colour')}}" name="product_colour"
                                        class="form-control" placeholder="Write Here...">
                                    @error('product_colour')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Price (According to quantity)</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('price')}}" name="price"
                                        class="form-control" placeholder="Write Here...">
                                    @error('price')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Special Price (Date range for special price)</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('special_price')}}" name="special_price"
                                        class="form-control" placeholder="Write Here...">
                                    @error('special_price')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Price per piece</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('price_per_piece')}}" name="price_per_piece"
                                        class="form-control" placeholder="Write Here...">
                                    @error('price_per_piece')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Minium Quantity</b></label>
                                <div class="col-sm-6"><input type="number" value="{{old('minimum_quantity')}}" name="minimum_quantity"
                                        class="form-control" placeholder="Write Here...">
                                    @error('minimum_quantity')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Maximum Quantity</b></label>
                                <div class="col-sm-6"><input type="number" value="{{old('maximum_quantity')}}" name="maximum_quantity"
                                        class="form-control" placeholder="Write Here...">
                                    @error('maximum_quantity')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group  row"><label
                                class="col-sm-3 col-form-label"><b>Status</b></label>
                                <div class="col-sm-6"><input type="text" value="{{old('status')}}" name="status"
                                        class="form-control" placeholder="Write Here...">
                                    @error('status')
                                    <p class="text-danger" role="alert">
                                        <strong id="error">{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <hr>
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
                        <hr>
            
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Specifications</b></label>
                            <div class="col-sm-6">
                                <input type="text" value="{{old('Specifications')}}" name="Specifications"
                                    class="form-control" placeholder="Write here...">
                                @error('Specifications')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Product Descriptions</b></label>
                            <div class="col-sm-6">
                                <input type="text" value="{{old('product_description')}}" name="product_description"
                                    class="form-control" placeholder="Write here...">
                                @error('product_description')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Product USP / Short Descriptions</b></label>
                            <div class="col-sm-6">
                                <input type="text" value="{{old('product_short_description')}}" name="product_short_description"
                                    class="form-control" placeholder="Write here...">
                                @error('product_short_description')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Delivery Time</b></label>
                            <div class="col-sm-6">
                                <input type="time" value="{{old('delivery_time')}}" name="delivery_time"
                                    class="form-control" placeholder="Write here...">
                                @error('delivery_time')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Product Weight</b></label>
                            <div class="col-sm-6">
                                <input type="text" value="{{old('product_weight')}}" name="product_weight"
                                    class="form-control" placeholder="Write here...">
                                @error('product_weight')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Product Weight</b></label>
                            <div class="col-sm-6">
                                <input type="text" value="{{old('product_weight')}}" name="product_weight"
                                    class="form-control" placeholder="Write here...">
                                @error('product_weight')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Print On Demand</b></label>
                            <div class="col-sm-6">
                                <select name="print_on_demand" class="form-control">
                                    <option value="yes">YES</option>                                   
                                    <option value="no">NO</option>
                                </select>
                                @error('print_on_demand')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Design Type</b></label>
                            <div class="col-sm-6">
                                <select name="design_type" class="form-control">
                                    <option value="label">Labels</option>                                   
                                    <option value="designing">Designing</option>
                                    <option value="package_labeling">Package Labeling</option>
                                </select>
                                @error('design_type')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group  row">
                            <label class="col-sm-3 col-form-label"><b>Printing Service</b></label>
                            <div class="col-sm-6">
                                <input type="text" value="{{old('printing_service')}}" name="printing_service"
                                    class="form-control" placeholder="Write here...">
                                @error('printing_service')
                                <p class="text-danger" role="alert">
                                    <strong id="error">{{ $message }}</strong>
                                </p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group  row">
                            <div class="col-sm-12">
                                <input type="submit" class=" btn btn-primary float-right" value="Save">
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
// required: true,

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
// required: true,

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
discount: {
number:true,
max:100

},

},
messages:{
// product_name:{
// required:'Se requiere el nombre del producto.',
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