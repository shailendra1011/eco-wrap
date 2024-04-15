@extends('admin.layout.app')
@section('content')
<style>
    .product-imitation {
        text-align: center;
        padding: 0px 0;
        background-color: #f8f8f9;
        color: #bebec3;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Banner </h3>
            </div>

            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">
                        <div class="ibox float-e-margins no-border">
                            <div class="ibox-content" style="border: none;">
                                <h2 class="no-margins">Total Banner : <span id="data_count">0</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-2 float-right">
                        <button type="button" class="btn  btn-primary" data-toggle="modal" data-target="#myModal">

                            <i class="fa fa-plus mr-2"></i>New Banner
                        </button>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" id="data_1">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="from_date" class="form-control" placeholder="From">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" id="data_2">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="to_date" class="form-control" placeholder="To">
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row" id="banner_data">
                    </div>
                </div>
                <div class="pull-left" id="meta_data"></div>
                <div class="btn-group pull-right" id="pagination">
                    {{-- Pagination --}}
                </div>
                <br><br>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('banner.add')}}" method="POST" id="addForm" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf

                    <div class="form-group row">

                        <div class="col-md-11 mt-2 ml-3">
                            <input id="logo" name="store_banner" type="file" class="custom-file-input">
                            <label for="logo" class="custom-file-label">Choose file...</label>
                        </div>
                        @error('file')
                        <p class="text-danger" role="alert">
                            <strong id="error">{{ $message }}</strong>
                        </p>
                        @enderror

                    </div>

                    <div class="form-group row">

                        <div class="col-md-11 mt-2 ml-3">
                            <label for="meta_title">Meta Title :</label>
                            <input id="meta_title" name="meta_title" type="text" class="form-control">
                        </div>
                        @error('meta_title')
                        <p class="text-danger" role="alert">
                            <strong id="error">{{ $message }}</strong>
                        </p>
                        @enderror

                    </div>

                    <div class="form-group row">

                        <div class="col-md-11 mt-2 ml-3">
                            <label for="meta_description">Meta Description :</label>
                            <input id="meta_description" name="meta_description" type="text" class="form-control">
                        </div>
                        @error('meta_description')
                        <p class="text-danger" role="alert">
                            <strong id="error">{{ $message }}</strong>
                        </p>
                        @enderror

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let table_data = [];

    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        get_data();

    });

    function get_data(link = null) {

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();


        let row = `
				<tr>
                    <td colspan="12"> Loading... </td>
                </tr>
			`;
        $('#banner_data').html(row);
        $('#pagination').html('');

        $.ajax({
            url: link ? link : "{{ route('banner') }}",
            type: 'get',
            data: {
                _token: "{{ csrf_token() }}",
                from_date: from_date,
                to_date: to_date,
            },
            success: function(res) {
                console.log(res);
                $('#data_count').text(res.count);
                $('#meta_data').text('');
                if (res.data.data.length) {
                    table_data = res.data.data;
                    $('#meta_data').text(
                        `Showing results ${res.data.from} to ${res.data.to} of ${res.data.total}`
                    );
                    $('#banner_data').html('');
                    $.each(res.data.data, function(key, value) {
                        let row = `
                        <div class="col-md-3">
                            <div class="ibox">
                                <div class="ibox-content product-box">

                                    <div class="product-imitation">
                                        <img alt="image" src="${value.store_banner}" width="217px" height="200px">  
                                        
                                    </div>
                                    <div class="product-desc">
                                        <div class="m-t text-left">
                                            <label>Meta Title :</lable>
                                            <p>${value.meta_title}</p>
                                        </div>
                                        <div class="m-t text-left">
                                            <label>Meta Description :</lable>
                                            <p>${value.meta_description}</p>
                                        </div>
                                        <div class="m-t text-right text-danger">
                                            <button onclick="changeStatusAndDelete(${value.id},${value.status},1)" class=" mt-1 btn btn-xs btn-${value.status==0 ? 'danger' :'btn btn-primary'} "><i class="fa fa-${value.status==0 ? 'times' : 'check'}"></i> ${value.status==0 ? "Inactive" : "Active"}</button>     
                                            <button onclick="changeStatusAndDelete(${value.id},${value.status},2)" class=" mt-1 btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</button>     
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        `;
                        $('#banner_data').append(row);
                    })
                    let pagination = `
                            <button type="button" onclick="get_data('${res.data.first_page_url}')" class="btn btn-white text-muted"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></button>
                        `;
                    if (res.data.prev_page_url) {
                        pagination += `
                                <button onclick="get_data('${res.data.prev_page_url}')" class="btn btn-white">${res.data.current_page - 1}</button>
                            `;
                    }
                    pagination += `
                              <button class="btn btn-white active">${res.data.current_page}</button>
                        `;
                    if (res.data.next_page_url) {
                        pagination += `
                                <button onclick="get_data('${res.data.next_page_url}')" class="btn btn-white">${res.data.current_page + 1}</button>
                            `;
                    }
                    pagination += `
                              <button type="button" onclick="get_data('${res.data.last_page_url}')" class="btn btn-white text-muted"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i> </button>
                        `;
                    $('#pagination').html(pagination);
                } else {
                    let row = `
							<tr>
    	                        <td colspan="7"> Record not found! </td>
    	                    </tr>
		    			`;
                    $('#banner_data').html(row);
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    @if(count($errors) > 0)
    $('#myModal').modal('show');
    get_data();
    @endif
</script>



<script>
    var date = new Date();
    date.setDate(date.getDate());
    $('#start_date .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: date
    });
    $('#end_date .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        startDate: date
    });
    $(document).ready(function() {

        $('#addForm').validate({ // initialize the plugin
            rules: {

                store_banner: {
                    required: true,

                },
                meta_title: {
                    required: true,

                },
                meta_description: {
                    required: true,

                },
            }
        });

    });

    function changeStatusAndDelete(id,status,type)
    {
        var t=type==1?'Do you want to change banner status ?':'Do you want to delete banner ?';
        swal({
            title: "Are you sure?",
            text: t,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Ok',
            closeOnConfirm: true
        }, function () {
        $.ajax({
               url: "{{route('banner.update.delete')}}",
               type: "post",
               data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status : status,
                    type:type
               },
               success: function(res) {                   
                    
                    swal({
                         position: 'center',
                         type: 'success',
                         title: 'Success!',
                         showConfirmButton: false,
                        timer: 3000
                    });
                 
                 location.reload();
               },
          });
        });
        
    }
</script>
@endsection