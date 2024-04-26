@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Product Management (Pharmacy) </h3>
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
                      <a href="{{route('product.new')}}">  <button type="button" class="btn  btn-primary {{$getslug->slug}}">
                            <i class="fa fa-plus mr-2"></i>{{__('StaticWords.vendor.new_product')}}
                        </button></a>
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
                                <th>#</th>
                                <th>{{__('StaticWords.vendor.product_name_en')}}</th>                                
                                <th>{{__('StaticWords.vendor.manufacturer_name_en')}}</th>                                
                                <th>{{__('StaticWords.vendor.sub_cat')}}</th>
                                <th>{{__('StaticWords.vendor.price')}}</th>
                                <th>{{__('StaticWords.vendor.sachet')}}</th>
                                <th>{{__('StaticWords.vendor.quantity')}}</th>                                                                          
                                <th>{{__('StaticWords.vendor.ingredients')}}</th>                              
                                <th>{{__('StaticWords.vendor.status')}}</th>                               
                                <th>{{__('StaticWords.vendor.created_on')}}</th>
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
            url: link ? link : "{{ route('product') }}",
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
                            <td> ${value.product_name ??''} </td>  
                            <td>${value.manufacturer_name??''}</td>
                            <td>${"{{Session::get('locale')}}"=='en'?value.sub_category.subcategory_name:value.sub_category.subcategory_name_es}</td> 
                            <td>${value.price??0}</td>  
                            <td>${value.sachet_capsule??''}</td>  
                            <td>${value.quantity??0}</td> 
                            <td>${value.ingredients??''}</td> 
                            <td>
                                <span class="badge ${value.product_status==0?'bg-danger':'bg-info'}">${value.product_status==1?'Online':'Offline'}</span>                            
                            </td>                         
                                                      
                            
                           <td> ${value.created_at} </td>
                           <td class="text-capitalize"> 
                                <div class="pull-right">                                    
                                    <button class=" btn btn-success" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu animated fadeInLeft">
                                            <li><a  class="btn btn-light href="javascript:void(0)"  dropdown-item" onclick="details(${value.id})" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye  mr-3"></i>View</a></li>
                                             <li><a   href="{{route('product.showEditForm')}}/${value.id}"  dropdown-item"><i class="fa fa-edit  mr-3"></i>Edit</a></li>
                                        `;
                         if (value.product_status ==0) {
                            row +=
                                ` <li><a onclick="changeStatus(${value.id},1)" class="btn btn-light  dropdown-item" href="javascript:void(0)"><i class="fa fa-check  mr-3"></i>Active</a></li>                        
                                `;
                                
                        }
                        else{
                            row +=
                                ` <li><a onclick="changeStatus(${value.id},0)" class="btn btn-light  dropdown-item" href="javascript:void(0)"><i class="fa fa-check  mr-3"></i>Inactive</a></li>                        
                                `;
                      

                        }
                      
                        row+=`
                                    <li><a onclick="deleteProduct(${value.id})" class="btn btn-danger  dropdown-item" href="javascript:void(0)"><i class="fa fa-trash mr-3"></i>Delete</a></li>
                                </td>
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

    function changeStatus(product_id,status) {   
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

           url: "{{ route('product.change-status') }}",
           type: 'POST',
           data: {
               _token: "{{ csrf_token() }}",               
               product_id: product_id,
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


function deleteProduct(product_id) {
    swal({
       title: "Are you sure?",
       text: 'Do you want to delete the product?',
       type: "warning",
       showCancelButton: true,
       confirmButtonColor: "#DD6B55",
       confirmButtonText: 'Ok',
       closeOnConfirm: true
   }, function () {
        var url     =   "{{ route('product.destroy',['product_id']) }}";
        url     =   url.replace('product_id',product_id);
        $.ajax({
           url: url,
           type: 'DELETE',
           data: {
               _token: "{{ csrf_token() }}"
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
                            <p><strong>{{__('StaticWords.vendor.product_name')}} :</strong> ${details.product_name??''}</p>
                        </div>
                        <div class="col-4">
                            <p><strong>{{__('StaticWords.vendor.quantity')}} : </strong>${details.quantity??0}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>{{__('StaticWords.vendor.direction_to_use')}} :</strong> ${details.direction_to_use??''}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>{{__('StaticWords.vendor.ingredients')}} :</strong> ${details.ingredients??''}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>{{__('StaticWords.vendor.description')}} :</strong> ${details.description??''}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>{{__('StaticWords.vendor.other_info')}} :</strong> ${details.description??''}</p>
                        </div>
                    </div>
                    <div class="row mt-2">`;
                        $.each(details.product_images,function(key,value){
                            row+=`<div class="col-6" style="margin-top:20px">
                                <img src="${value.product_image}" height="400px" width="370px"/>
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
