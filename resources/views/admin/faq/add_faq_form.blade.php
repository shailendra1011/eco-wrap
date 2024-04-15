@extends('admin.layout.app')
@section('content')
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
                <h3><a href="{{route('admin.faq.index')}}"> FAQ List </a>/ Add New FAQ </h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">

                    @if ($errors->any())
                        <div class="col-12">
                            @foreach ($errors->all() as $error)
                                <div class="row">
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        {{$error}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (Session::has('success'))
                        <div class="col-12">
                            <div class="row">
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{ Session::get('success') }}
                                </div>
                            </div>
                        </div>
                    @endif


                    <form method="post" action="{{route('admin.faq.store')}}">
                        @csrf
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>Language</b></label>
                                <div class="col-sm-6">
                                    <select class="form-control m-b" name="language">
                                        <option value="">Select Language</option>
                                        @foreach ($languages as $language)
                                            <option value="{{$language->language_code}}">{{$language->language .' - '.$language->language_code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 form-div-row" id="row-1">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>Question</b></label>
                                <div class="col-sm-6">
                                    <textarea name="questions[]" class="form-control" placeholder="Write Question here ..."></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>Answer</b></label>
                                <div class="col-sm-6">
                                    <textarea name="answers[]" class="form-control" placeholder="Write Answer here ..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group  row">
                                <div class="col-sm-6">
                                    <input type="button" class=" btn btn-primary float-right" id="add-new-question-asnwer-row" value="Add New Row">
                                </div>
                            </div>
                            <div class="form-group  row">
                                <div class="col-sm-6">
                                    <input type="submit" class=" btn btn-primary float-right" value="Save">
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

        $('#add-new-question-asnwer-row').click(function(){

            var row_length  =   $(".form-div-row").length;
            $("#row-"+row_length).after(`
                <div class="col-12 form-div-row" id="row-${row_length+1}">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>Question</b></label>
                                <div class="col-sm-6">
                                    <textarea name="questions[]" class="form-control" placeholder="Write Question here ..."></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>Answer</b></label>
                                <div class="col-sm-6">
                                    <textarea name="answers[]" class="form-control" placeholder="Write Answer here ..."></textarea>
                                </div>
                            </div>
                        </div>
            `);

        });

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

var elem = document.querySelector('.js-switch1');
var switchery = new Switchery(elem, { color: '#1AB394' });
</script>
@endsection