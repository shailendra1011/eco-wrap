@extends('admin.layout.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Driver Management</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#" class="dropdown-item">Config option 1</a>
                            </li>
                            <li><a href="#" class="dropdown-item">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-md-10 float-left">
                            <div class="ibox float-e-margins no-border">
                                <div class="ibox-content" style="border: none;">
                                    <h2 class="no-margins" style="color:#1ab394;">Total Driver : <span
                                            id="data_count">{{$total_drivers}}</span></h2>
                                </div>
                            </div>
                        </div>
                        <!-- <div class=" col-md-2 float-right" style="margin-top:15px;">
                            <a href=""> <button type="button" class="btn  btn-primary">
                                    <i class="fa fa-download" style="margin-right: 8px;"></i>Download
                                </button>
                            </a>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <input type="text" id="search" class="form-control" placeholder="Search by name,email"
                                onkeyup="get_data()">
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <select class="form-control m-b" id="status" onchange="get_data()">
                                    <option value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group" id="data_1">
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" id="from_date" class="form-control" placeholder="From"
                                        onchange="get_data()">
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group" id="data_2">
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" id="to_date" class="form-control" placeholder="To"
                                        onchange="get_data()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 20px">
                        <table class="table table-striped  table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Driver Image</th>
                                    <th>Driver Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Vehicle Type</th>
                                    <th>Vehicle Number</th>
                                    <th>Verification Status</th>
                                    <th>Documents</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="user_data">
                            </tbody>
                        </table>
                    </div>
                    <div class="pull-left" id="meta_data" style="margin-top:-14px">Showing results 1 to 3 of 3</div>
                    <div class="btn-group pull-right" id="pagination" style="margin-top:-19px">
                        <button type="button" class="btn btn-white text-muted"><i class="fa fa-chevron-left"></i><i
                                class="fa fa-chevron-left"></i></button>

                        <button class="btn btn-white active">1</button>

                        <button type="button" class="btn btn-white text-muted"><i class="fa fa-chevron-right"></i><i
                                class="fa fa-chevron-right"></i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="details"></div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        get_data();
    });
    var table_data=[];
    function get_data(link = null) {
        var search = $('#search').val();
        var status = $('#status').val();
        var gender = $('#gender').val();
        var to_date = $('#to_date').val();
        var from_date = $('#from_date').val();

        $.ajax({
            url: link != null ? link : "{{route('driver/data')}}",
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
                search: search,
                status: status,
                gender: gender,
                from_date: from_date,
                to_date: to_date
            },
            success: function(res) {
                console.log(res);
                $('#meta_data').text('');
                if (res.data.data.length) {
                    table_data = res.data.data;
                    $('#meta_data').text(`Showing results ${res.data.from} to ${res.data.to} of ${res.data.total}`);
                    $('#user_data').html('');
                    let row = ``;
                    $.each(res.data.data, function(key, value) {
                        var url = "{{url('admin/view/document')}}" + '/' + `${value.id}`;
                        row += `
							<tr>
							<td> ${res.data.from++} </td>`
                        if (value.driver_image) {
                            row += `<td><img src="${value.driver_image}" style="height:40px;width:40px; margin-right:2px; border-radius:50%"></td>`
                        } else {
                            row += `<td><img src= "{{asset('admin/img/logoVector.svg ')}}" style="height:40px;width:40px; margin-right:2px; border-radius:50%"></td>`
                        }
                        row += `<td> ${value.driver_name? value.driver_name : '-'}</td>
                            <td> ${value.driver_email? value.driver_email : '-'} </td>
                            <td> ${value.driver_mobile? value.driver_mobile : '-'} </td>
                            <td>    <i class='btn fa fa-eye' onclick="Details(${value.id})" data-toggle="modal" data-target="#myModal"></i></td>
                            <td> ${value.vehicle_type? value.vehicle_type : '-'} </td>
                            <td> ${value.vehicle_number? value.vehicle_number : '-'}  </td>
                            <td> ${value.document_status? value.document_status : '-'}</td>
                            <td>`
                        if (value.document_status == 'Not Applied') {
                            row += `${value.document_status? value.document_status : '-'}`
                        } else {
                            row += `<a href="${url}"class="mt-1 btn btn-xs btn-btn btn-info" ><i class="fa fa-eye" ></i> View Document</a>
                            </td> `
                        }
                        if (value.document_status == 'Not Applied') {
                            row += `<td> 
                                <a href="#"class="mt-1 btn btn-xs btn-btn btn-danger" ><i class="fa fa-times" ></i> Inactive</a>     
                            </td>
                            `
                        } else {
                            row += `<td> 
                            <button onclick="changeStatus(${value.id},${value.status})" class=" mt-1 btn btn-xs btn-${value.status==0 ? 'danger' :'btn btn-primary'} "><i class="fa fa-${value.status==0 ? 'times' : 'check'}"></i> ${value.status==0 ? "Inactive" : "Active"}</button>     
                            </td>
                            `
                        };
                    })
                    $('#user_data').html(row);
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
                    $('#user_data').html(row);
                }
            },
        });
    }

    function changeStatus(driver_id, driver_status) {
        swal({
                title: "Are you sure?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Success!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    url: "{{route('driver/status')}}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        driver_id: driver_id,
                        driver_status: driver_status
                    },
                    success: function(res) {
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'Driver Status Changed!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        get_data();
                    },
                });
                
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
                                    <p><strong>Driver address :</strong> ${details.driver_address}</p>
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
</script>

@endsection