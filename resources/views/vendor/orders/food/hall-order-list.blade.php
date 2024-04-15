@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>{{__('StaticWords.vendor.order_management')}} </h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">
                        <div class="ibox float-e-margins no-border">
                            <div class="ibox-content" style="border: none;">
                                <h2 class="no-margins">{{__('StaticWords.vendor.total_order')}} : <span
                                        id="data_count">0</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-4 ">
                        <input type="text" id="search" onkeyup="get_data()"
                            placeholder="search by name" class="form-control">
                    </div>
                    <div class="col-4">
                        <div class="form-group" id="data_1">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="from_date" class="form-control"
                                    placeholder="{{__('StaticWords.from')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group" id="data_2">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="to_date" class="form-control"
                                    placeholder="{{__('StaticWords.to')}}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th># </th>
                                <th>Booking user name</th>
                                <th>Booking person mobile</th>
                                <th>Reservation type</th>
                                <th>Time</th>
                                <th>Date</th>
                                <th>No of person</th>
                                <th>Description</th> 
                                <th>Status</th>                              
                                <th class="text-right">{{__('StaticWords.vendor.action')}}</th>
                            </tr>
                        </thead>
                        <tbody id="table_data">

                            {{-- table Data --}}
                        </tbody>
                    </table>
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
{{-- show product details --}}
<div id="details"></div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        get_data();
    });

var table_data=[];
    function get_data(link = null) {

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let search = $('#search').val();

        let row = `
        <tr>
            <td colspan="12"> Loading... </td>
        </tr>
    `;
        $('#table_data').html(row);
        $('#pagination').html('');

        $.ajax({
            url: link ? link : "{{ route('food.table.orders') }}",
            type: 'get',
            data: {
                _token: "{{ csrf_token() }}",
                from_date: from_date,
                to_date: to_date,
                search: search
            },
            success: function (res) {
                console.log(res);

                $('#data_count').text(res.count);
                $('#meta_data').text('');
                if (res.data.data.length) {
                    table_data = res.data.data;
                    $('#meta_data').text(
                        `Showing results ${res.data.from} to ${res.data.to} of ${res.data.total}`
                    );
                    $('#table_data').html('');
                    $.each(res.data.data, function (key, value) {
                       

                        let row = `
                        <tr>
                            <td> ${res.data.from++} </td>                            
                            <td> ${value.booking_person_name ??''} </td>  
                            <td>${value.country_code??''}${value.booking_person_mobile??''}</td>
                            <td>
                                <span class="badge ${value.reservation_type==1?'bg-info':'bg-success'}">${value.reservation_type==1?'Hall':'Table'}</span>                            
                            </td>
                            <td> ${value.time ??''} </td>                
                            
                           <td> ${moment(new Date(value.date)).format("D MMM YYYY")} </td>
                           <td> ${value.no_of_persons ??''} </td>                
                           <td> ${value.description ??''} </td>                
                           <td> <span class=" badge ${value.status==0?'bg-info':(value.status==1?'bg-success':'bg-danger')}">${value.status==0?'Incomming':(value.status==1?'Accepted':'Rejected')} </span></td>                

                           <td class="text-capitalize"> 
                                <div class="pull-right">                                    
                                    <button class=" btn btn-success" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu animated fadeInLeft">
                                            <li><a  class="btn btn-light href="javascript:void(0)"  dropdown-item" onclick="details(${value.id})" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye  mr-3"></i>View</a></li>`;
                                            if(value.status==0)
                                            {
                                                row+=`<li><a  class="btn btn-light dropdown-item" href="javascript:void(0)"   onclick="changeStatus(${value.id},1)" ><i class="fa fa-check  mr-3"></i>Accept</a></li>
                                                        <li><a  class="btn btn-light dropdown-item" href="javascript:void(0)"   onclick="changeStatus(${value.id},2)" ><i class="fa fa-check  mr-3"></i>Reject</a></li>`;
                                            }                                           
                                    
                                                               
                      
                        row+=`</ul></td> 
                        
                                                                               
                        </tr>
                    `;
                        $('#table_data').append(row);
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
                        <td colspan="12"> {{__('StaticWords.record_not_found')}} </td>
                    </tr>
                `;
                    $('#table_data').html(row);
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

function changeStatus(order_id,status) {   
    let t= 'Do you want to change order status ?';

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

           url: "{{ route('food.table.orders.change-status') }}",
           type: 'POST',
           data: {
               _token: "{{ csrf_token() }}",               
               order_id: order_id,
               status:status
               
           },
           success: function (res) {
               console.log(res);
               if (res.status == 200) {
                   toastr.success(res.message), {
                       "closeButton": true
                   };
                  get_data();
               } else {
                   toastr.error(res.message), {
                       "closeButton": true
                   };
                   get_data();
               }

           },
           error: function (err) {
               console.log(err);
           }
       });

   });
}

    

    function details(id) {
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
                            
                            <h4 class="modal-title">{{Session::get('locale')=='en'?'Details':'Detalles'}} </h4>
                            
                        </div>
                        <div class="modal-body">
                            <div class='row'>
                                <div class="col-6">
                                    <p><strong>Booking user name :</strong> ${details.user.name}</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Booking user email : </strong>${details.user.email}</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Booked for user name :</strong> ${details.booking_person_name}</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Booked for user mobile :</strong> ${details.booking_person_mobile}</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Booking type :</strong> ${details.reservation_type==1?'Hall':'Table'}</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Booking date :</strong> ${moment(details.date).format('MMMM Do YYYY')}</p>
                                </div>
                                <div class="col-6">
                                    <p><strong>Booking time :</strong> ${moment(details.time, "HH:mm:ss").format("hh:mm A")}</p>
                                </div>
                               
                                <div class="col-6">
                                    <p><strong>No of person :</strong> ${details.no_of_persons??''}</p>
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