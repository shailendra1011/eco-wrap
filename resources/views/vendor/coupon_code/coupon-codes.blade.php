@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Coupon Management</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">
                        <div class="ibox float-e-margins no-border">
                            <div class="ibox-content" style="border: none;">
                                <h2 class="no-margins">{{__('StaticWords.vendor.total_product')}} : <span id="data_count">0</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-2 float-right">
                        <a href="{{route('coupon.add')}}">
                            <button type="button" class="btn  btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Coupon
                            </button>
                        </a>
                    </div>                    
                </div>
                <div class="row ">
                    <div class="col-4 ">
                        <input type="text" id="search" onkeyup="get_data()" placeholder="{{__('StaticWords.vendor.search_by_pro_name')}}" class="form-control">
                    </div>
                    <div class="col-4">
                        <div class="form-group" id="data_1">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="from_date" class="form-control"  placeholder="{{__('StaticWords.from')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group" id="data_2">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="to_date" class="form-control"  placeholder="{{__('StaticWords.to')}}">
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th># </th>
                                <th>Coupon Code</th>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Minimum Cart Value</th>
                                <th>Maximum Discount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
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
            url: link ? link : "{{ route('coupon') }}",
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
                            <td>${res.data.from++} </td>                            
                            <td>${value.coupon_code ??''} </td>  
                            <td>${value.discount_type}</td> 
                            <td>${value.discount_value}</td>  
                            <td>${value.min_cart_value}</td>  
                            <td>${value.maximum_discount}</td>  
                            <td>${value.start_date}</td>                             
                            <td>${value.end_date}</td>                             
                            <td>${value.description}</td>`;
                            if (value.is_active) {
                                row     +=  `<td><span class="badge badge-primary">Active</span></td>`;
                            } else {
                                row     +=  `<td><span class="badge badge-warning">Inactive</span></td>`;
                            }
                            row += `<td class="text-capitalize"> 
                                <div class="pull-right">                                    
                                    <button class=" btn btn-success" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu animated fadeInLeft">
                                            <li><a  class="btn btn-light href="javascript:void(0)"  dropdown-item" onclick="details(${value.id})" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye  mr-3"></i>View</a></li>
                                             <li><a   href="{{route('product.showEditForm')}}/${value.id}"  dropdown-item"><i class="fa fa-edit  mr-3"></i>Edit</a></li> 
                                            `;
                                        
                        if (value.is_active ==0) {
                            row +=
                                ` <li><a onclick="changeStatus(${value.id},1)" class="btn btn-light  dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#account"><i class="fa fa-check  mr-3"></i>Active</a></li>`;                        
                                
                        }
                        else{
                            row +=
                                ` <li><a onclick="changeStatus(${value.id},0)" class="btn btn-light  dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#account"><i class="fa fa-check  mr-3"></i>Inactive</a></li>`;                        
                      

                        }
                      
                        row+=`</td> 
                        
                                                                               
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
                        <td colspan="12"> Record not found! </td>
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

function changeStatus(coupon_id,status) {   
let t= (status==1?'Do you want to make this product online ?':'Do you want to make this product offline ?');


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

           url: "{{ route('coupon.change-status') }}",
           type: 'POST',
           data: {
               _token: "{{ csrf_token() }}",               
               coupon_id: coupon_id,
               status:status
               
           },
           success: function (res) {
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
            $.each(details.product_log,function(key,value){
            row+=`
            <div class="form-group row">
                <div class="col-12">
                <p> <strong>Action Type : </strong> ${value.action_type==0?'Disable':(value.action_type==1?'Enable':(value.action_type==3?'Add':'Validity change'))}</p>
                </div>
                <div class="col-12">
                <p> <strong>Action Taken By : </strong>${value.action_taken_by.name??''} </p>
                </div>
                <div class="col-12">
                <p> <strong>Reason : </strong> ${value.reason??''} </p>
                </div>
                <div class="col-12">
                <p> <strong>Action Date : </strong>${value.created_at} </p>
                </div>
                
            </div>
            <hr>
            
			`;
        });

            $('#details').html(row);
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
                        <div class="col-8">
                            <p><strong>{{__('StaticWords.vendor.product_name')}} :</strong> ${details.product_name}</p>
                        </div>
                        <div class="col-4">
                            <p><strong>{{__('StaticWords.vendor.quantity')}} : </strong>${details.quantity}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>{{__('StaticWords.vendor.description')}} :</strong> ${details.description}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>{{__('StaticWords.vendor.other_info')}} :</strong> ${details.description}</p>
                        </div>`;
                        $.each(details.product_images,function(key,value){
                            row+=`<div class="col-6">
                                <img src="${value.product_image}" height="200px" width="200px"/>
                            </div>`;
                        });
                        row+=` </div>
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
