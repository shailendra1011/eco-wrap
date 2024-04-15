@extends('admin.layout.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Coupon Codes</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        @if(Session::has('error'))
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <strong>Error !</strong> {{ session::get('error') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-10 float-left">
                            <div class="ibox float-e-margins no-border">
                                <div class="ibox-content" style="border: none;">
                                    {{-- <h2 class="no-margins" style="color:#1ab394;">Total User : <span id="data_count">{{$total_users}}</span></h2> --}}
                                </div>
                            </div>
                        </div>
                        <div class=" col-md-2 float-right" style="margin-top:15px;">
                            <a href="{{ route('admin.coupon.create') }}"> <button type="button" class="btn  btn-primary">
                                    <i class="fa fa-plus" style="margin-right: 8px;"></i>Add Coupon Code
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <input type="text" id="search" class="form-control" placeholder="Search coupon code" onkeyup="get_data()">
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <select class="form-control m-b" id="status" onchange="get_data()">
                                    <option value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group" id="data_1">
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" id="from_date" class="form-control" placeholder="From" onchange="get_data()">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group" id="data_2">
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" id="to_date" class="form-control" placeholder="To" onchange="get_data()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 20px">
                        <table class="table table-striped  table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Coupon Code</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="coupon_code_data">
                            </tbody>
                        </table>
                    </div>
                    <div class="pull-left" id="meta_data" style="margin-top:-14px">Showing results 1 to 3 of 3</div>
                    <div class="btn-group pull-right" id="pagination" style="margin-top:-19px">
                        <button type="button" class="btn btn-white text-muted"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></button>

                        <button class="btn btn-white active">1</button>

                        <button type="button" class="btn btn-white text-muted"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="details"></div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        get_data();
    });
    var table_data=[];
    function get_data(link = null) {
        var search = $('#search').val();
        var status = $('#status').val();
        var to_date = $('#to_date').val();
        var from_date = $('#from_date').val();

        $.ajax({
            url: link != null ? link : "{{route('admin.coupon.code.list')}}",
            type: "GET",
            data: {
                // _token: "{{ csrf_token() }}",
                search: search,
                status: status,
                from_date: from_date,
                to_date: to_date
            },
            success: function(res) {
                console.log(res);
                $('#meta_data').text('');
                if (res.data.data.length) {
                    table_data = res.data.data;
                    $('#meta_data').text(`Showing results ${res.data.from} to ${res.data.to} of ${res.data.total}`);
                    $('#coupon_code_data').html('');
                    let row = ``;
                    $.each(res.data.data, function(key, value) {
                        // var url = "{{url('admin/view/document')}}" + '/' + `${value.id}`;
                        row += `
							<tr>
							<td> ${res.data.from++} </td>`
                        row += `<td> ${value.coupon_code? value.coupon_code : '-'}</td>
                            <td> ${value.start_date? value.start_date : '-'} </td>
                            <td> ${value.end_date? value.end_date : '-'} </td>
                            <td> ${value.discount_type? value.discount_type : '-'} </td>
                            <td> ${value.discount_value? value.discount_value : '-'} </td>
                            <td> ${value.description? value.description : '-'} </td>`;
                        if (value.end_date >= new Date().toISOString().slice(0, 10)) {
                            row += `<td>
                                        <span class="badge badge-success">Active</span>
                                    </td>`;
                        } else {
                            row += `<td>
                                        <span class="badge badge-danger">Expired</span>
                                    </td>`;
                        }
                        var edit_url    =   "{{ route('admin.coupon.edit',[':id']) }}";
                        edit_url        =   edit_url.replace(':id',btoa(value.id));
                        var delete_url  =   "{{ route('admin.coupon.destroy',[':id']) }}";
                        delete_url      =   delete_url.replace(':id',btoa(value.id));
                        row +=  `<td> 
                                    <a href="${edit_url}" titile="Edit"><i class="fa fa-pencil-square-o"></i></a>

                                    <a href="javascript:void(0)" onclick="confirmAndDeleteCouponCode('${delete_url}')" titile="Delete"><i class="fa fa-ban"></i></a>

                                    <a href="javascript:void(0)" onclick="confirmAndDeleteCouponCode('${delete_url}')" titile="Delete"><i class="fa fa-trash"></i></a>
                                </td>
                                `;
                        })
                        // <button onclick="changeStatus(${value.id},${value.user_status})" class=" mt-1 btn btn-xs btn-${value.user_status==0 ? 'danger' :'btn btn-primary'} "><i class="fa fa-${value.user_status==0 ? 'times' : 'check'}"></i> ${value.user_status==0 ? "Inactive" : "Active"}</button>     
                        $('#coupon_code_data').html(row);
                    let pagination = `
						<button type="button" onclick="get_data('${res.data.first_page_url}')" class="btn btn-light text-muted"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></button>
						`;
                    if (res.data.prev_page_url) {
                        pagination += `
							<button onclick="get_data('${res.data.prev_page_url}')" class="btn btn-light">${res.data.current_page - 1}</button>
							`;
                    }
                    pagination += `
						<button class="btn btn-light active">${res.data.current_page}</button>
						`;
                    if (res.data.next_page_url) {
                        pagination += `
							<button onclick="get_data('${res.data.next_page_url}')" class="btn btn-light">${res.data.current_page + 1}</button>
							`;
                    }
                    pagination += `
						<button type="button" onclick="get_data('${res.data.last_page_url}')" class="btn btn-light text-muted"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i> </button>
						`;
                    $('#pagination').html(pagination);
                } else {
                    let row = `
                            <tr>
                            <td colspan="12"> Record not found! </td>
                            </tr>
                            `;
                    $('#coupon_code_data').html(row);
                }
            },
        });
    }

    function changeStatus(id, status) {
        var msg=status==0?"Do you want to make user active ?":"Do you want to make user inactive ?";
        swal({
                title: "Are you sure?",
                text: msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    url: "{{route('user/status')}}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: status
                    },
                    success: function(res) {
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'User Status Changed!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        get_data();
                    },
                });
                swal("Deleted!", "", "success");
            });
    }

    function Details(id) {
            $('#details').html('');
            let details = table_data.find(o => o.id === id);

            console.log(details);
            var row=``;
            
            row+=`
            <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content animated bounceInRight">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">Address </h4>                            
                        </div>
                        <div class="modal-body">
                            <div class='row'>
                                <div class="col-6">
                                    <p><strong>User address :</strong> ${details.user_address}</p>
                                </div>
                            </div>                        
                       </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>                            
                        </div>
                    </div>
                </div>
            </div>
            
			`;        

            $('#details').html(row);
    }

    function confirmAndDeleteCouponCode(url) {
        swal({
            title   :   'Are you sure?',
            text    :   'Do you want to delete the coupon code?',
            type    :   "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            closeOnConfirm: false
        },
        function(isConfirmed) {
            if (isConfirmed) {
                $.ajax({
                    url     :    url ,
                    type    :   "delete",
                    data    :   {
                        '_token'    :   "{{ csrf_token() }}",
                    },
                    success: function(res) {

                        if (res.status == 200) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title : 'Deleted',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            get_data();

                        } else {
                            swal({
                                position: 'center',
                                type: 'error',
                                title : 'Failed!',
                                text: res.message
                            });
                        }
                    },
                    error : function(res){
                        console.log('error');
                        console.log(res);
                    }
                });
            }
        });
    }
</script>

@endsection