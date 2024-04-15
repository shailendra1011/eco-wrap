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
                            placeholder="{{__('StaticWords.vendor.search_by_order_id')}}" class="form-control">
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
                                {{-- <th>{{__('StaticWords.vendor.order_id')}}</th>
                                --}}
                                <th>Order no</th>
                                <th>User name</th>
                                <th>Driver name</th>
                                <th>{{__('StaticWords.vendor.order_status')}}</th>
                                <th>Is driver accepted</th>
                                <th>{{__('StaticWords.vendor.order_date')}}</th>
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
            url: link ? link : "{{ route('food.orders') }}",
            type: 'get',
            data: {
                _token: "{{ csrf_token() }}",
                from_date: from_date,
                to_date: to_date,
                search: search
            },
            success: function (res) {
                //console.log(res);

                $('#data_count').text(res.count);
                $('#meta_data').text('');
                if (res.data.data.length) {
                    table_data = res.data.data;
                    $('#meta_data').text(
                        `Showing results ${res.data.from} to ${res.data.to} of ${res.data.total}`
                    );
                    $('#table_data').html('');
                    $.each(res.orders, function (key, value) {
                       

                        let row = `
                        <tr>
                            <td> ${res.data.from++} </td>                            
                            <td> ${value.order_no ??''} </td>                              
                            <td>${value.user_name}</td>
                            <td>${value.driver_name}</td>
                            <td>
                                <span class="badge ${value.order_status==1?'bg-info':'bg-success'}">${value.order_status==1?'Accepted':(value.order_status==2 && value.driver_status==0?'Driver order request sent':(value.order_status==3?'Out for delivery':(value.order_status==0?'Incomming':(value.order_status==4?'Deliverd':(value.order_status==2 && value.driver_status==1?'Driver assigned':'Cancelled')))))}</span>                            
                            </td>
                            <td>
                                <span class=" badge ${value.is_driver_accepted==0?'bg-info':'bg-success'}">${value.is_driver_accepted==1?'Yes':'No'}</span>
                            </td>                                          
                            
                           <td> ${moment(new Date(value.created_at)).format("D MMM YYYY")} </td>
                           <td class="text-capitalize"> 
                                <div class="pull-right">                                    
                                    <button class=" btn btn-success" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu animated fadeInLeft">
                                            <li><a  class="btn btn-light" href="{{route('order.details')}}/${value.order_id}" ><i class="fa fa-eye  mr-3"></i>Order details</a></li>`;
                                            if(value.order_status==0){
                                                row+=`<li><a  class="btn btn-light dropdown-item" href="javascript:void(0)"   onclick="changeStatus(${value.order_id},1)" ><i class="fa fa-check  mr-3"></i>Accept</a></li>`;
                                            }
                                            if(value.order_status==0){
                                                row+=`<li><a  class="btn btn-danger dropdown-item" href="javascript:void(0)"   onclick="changeStatus(${value.order_id},5)" ><i class="fa fa-times  mr-3"></i>Cancel</a></li>`;
                                            }
                                            if(value.order_status==1){
                                                row+=`<li><a  class="btn btn-light dropdown-item" href="javascript:void(0)"   onclick="changeStatus(${value.order_id},2)" ><i class="fa fa-check  mr-3"></i>Assign driver</a></li>`;
                                            }
                                            if(value.order_status==2 && value.driver_status==1){
                                                row+=`<li><a  class="btn btn-light dropdown-item" href="javascript:void(0)"   onclick="expectedTime(${value.order_id})" data-toggle="modal" data-target="#myModal" ><i class="fa fa-check  mr-3"></i>Out for delivery</a></li>`;
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

           url: "{{ route('order.change-status') }}",
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

    

    function expectedTime(order_id) {
            $('#details').html('');            
            var row=``;
            
            row+=`
            <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content animated bounceInRight">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            
                            <h4 class="modal-title">{{Session::get('locale')=='en'?'Expected Date':'Fecha esperada'}} </h4>
                            
                        </div>
                        <div class="modal-body">
                            <form id="expectedDelivery">
                                <div class='row'>
                                    <label class="col-4 col-form-label"><b>Expected Delivery Date</b></label>
                                    <div class="col-8">
                                        <div class="form-group" id="expectedDate">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" id="expected_delivery_date" class="form-control"
                                                    placeholder="Enter expected delivery date" required>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="date_error"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 ">
                                        <button type="button" class="btn btn-primary float-right" onclick="submitDeliveryDate(${order_id})">Save</button>
                                    </div>
                                </div>
                            </form>                              
                        </div>                        
                    </div>
                </div>
            </div>
            
			`;        
            
            $('#details').html(row);
            $('#expectedDate .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                minDate:0,                           
                autoclose: true
            });
        }


        function submitDeliveryDate(order_id)
        {
            let orderId=order_id;
            let date=$('#expected_delivery_date').val();
            if(date=='')
            {
                $('#date_error').text('Expected date required');
                return false;
            }
            $('#date_error').text('');
            $.ajax({
            headers : {
            'Accept':'application/json'
            },
            url : '{{ route("order.change-status") }}',
            type : 'POST',
            data : {
                _token:"{{csrf_token()}}",
                order_id:order_id,
                expectedDeliveryDate:date,
                status:3
            }
            }).done(function(response){
            console.log(response);
            if (response.status == 200) {
                   toastr.success(response.message), {
                       "closeButton": true
                   };
                  location.reload();
               } else {
                   toastr.error(response.message), {
                       "closeButton": true
                   };
                   location.reload();
               }
           
            }).fail(function(response){
            console.log(response);

            }).always(function(response){
             console.log(response);

            });
        }





</script>
@endsection