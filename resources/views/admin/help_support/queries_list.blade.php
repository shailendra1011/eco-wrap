@extends('admin.layout.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>User Management</h5>
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
                                    <h2 class="no-margins" style="color:#1ab394;">Total Queries : <span id="data_count">{{$total_quries}}</span></h2>
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
                            <input type="text" id="search" class="form-control" placeholder="Search by name,email" onkeyup="get_data()">
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
                                    <th>User Name</th>
                                    <th>Query By</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Comment</th>
                                    <th>Reply</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="user_data">
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
<div id="change-user-data-model"></div>
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
            url: link != null ? link : "{{route('queries/data')}}",
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
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
                    $('#user_data').html('');
                    let row = ``;
                    $.each(res.data.data, function(key, value) {
                        var url = "{{url('admin/view/document')}}" + '/' + `${value.id}`;
                        row += `
							<tr>
							<td> ${res.data.from++} </td>
                            <td> ${value.user_data? value.user_data.name : '-'}</td>
                            <td> ${value.name? value.name : '-'}</td>
                            <td> ${value.user_data? value.user_data.email : '-'} </td>
                            <td> ${value.mobile? value.mobile : '-'} </td>
                            <td> ${value.comment? value.comment : '-'} </td>`;
                            if(value.reply) {
                                row += `<td>
                                <button onclick="viewReply('${value.reply}','${value.comment}')" class=" mt-1 btn btn-xs btn btn-success" data-toggle="modal" data-target="#viewReplyModal"><i class="fa fa-eye"></i>View Reply</button>
                                        </td>`;
                            } else {
                                row += `<td>-</td>`;
                            }
                            row += `<td> 
                            <button onclick="changeUserData(${value.id})" class=" mt-1 btn btn-xs btn btn-primary" data-toggle="modal" data-target="#userDataModal"><i class="fa fa-reply"></i> Reply</button>     
                            </td>
                            `;
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

    function changeUserData(id) {
            $('#change-user-data-model').html('');
            let details = table_data.find(o => o.id === id);

            console.log(details);
            var row=``;
            
            row+=`
            <div class="modal inmodal" id="userDataModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content animated bounceInRight">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">User Query </h4>                            
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <form id="user-query-reply-form" method="post">
                                    @csrf
                                    <input type="hidden" name="query_id" value="${details.id}">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><b>Query :</b></label>
                                        <div class="col-9">
                                            <textarea class="form-control" id="query-question">${details.comment}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><b>Reply :</b></label>
                                        <div class="col-9">
                                            <textarea class="form-control" id="query-reply" name="query_reply"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary pull-right" onclick="sendUserQueryReply()" id="submit-user-data-update-form">Send Reply</button>
                                        </div>
                                    </div>
                                </form>
                            </div>                        
                       </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>                            
                        </div>
                    </div>
                </div>
            </div>
            
			`;        

            $('#change-user-data-model').html(row);
    }


    function viewReply(reply,comment) {
            $('#change-user-data-model').html('');
            var row=``;
            
            row+=`
            <div class="modal inmodal" id="viewReplyModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content animated bounceInRight">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">User Query Reply</h4>                            
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>Query :</b></label>
                                    <div class="col-9">
                                        <p>${comment}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><b>Reply :</b></label>
                                    <div class="col-9">
                                    <p>${reply}</p>
                                    </div>
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

            $('#change-user-data-model').html(row);
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

    function sendUserQueryReply() {

            swal({
                title: "Are you sure?",
                text: "Do you want to send reply to the user?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            },
            function() {
                console.log($("#user-query-reply-form").serialize());
                $.ajax({
                    url: "{{route('user/query/reply')}}",
                    type: "post",
                    data: $("#user-query-reply-form").serialize(),
                    success: function(res) {
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'Reply Sent!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $("#userDataModal").modal('toggle');
                        get_data();
                    },
                    error: (res)=>{
                        swal("Error!", "", "error");
                    }
                });
               
            });
    }
</script>

@endsection