@extends('admin.layout.app')
@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
     <div class="row">
          <div class="col-lg-12">
               <div class="ibox ">
                    <div class="ibox-title">
                         <h5>Category Management</h5>
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
                                             <h2 class="no-margins" style="color:#1ab394;">Total Category : <span id="data_count">{{$total_category}}</span></h2>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="row" style="margin-bottom: 15px;">
                              <div class="col-3 ">
                                   <input type="text" id="search" class="form-control" placeholder="Search by  subcategory name" onkeyup="get_data()">
                              </div>
                              <div class="col-md-2 float-right" style="margin-left: auto;">
                                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-plus mr-2"></i> Add Category
                                   </button>
                              </div>
                         </div>
                         <!-- launch modal for add category -->
                         <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                              <div class="modal-dialog">
                                   <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                             <h4 class="modal-title">Add Category</h4>
                                        </div>
                                        <div class="modal-body">
                                             <form id="add_subcategory" enctype="multipart/form-data">
                                                  @csrf
                                                  <div class="form-group"><label></label>
                                                       <input type="text" placeholder="Enter Category Name in English" id="category_name" name="category_name" class="form-control">
                                                  </div>
                                                  <div>
                                                       <p id="validation_err" class="text-danger custom_danger_clss  mt-2 ml-2"></p>
                                                  </div>
                                                  <div class="form-group"><label></label>
                                                       <input type="text" placeholder="Enter Category Name in Espanish" id="category_name_es" name="category_name_es" class="form-control">
                                                  </div>
                                                  <div>
                                                       <p id="validation_err1" class="text-danger custom_danger_clss  mt-2 ml-2"></p>
                                                  </div>
                                                  <div class="form-group"><label></label>
                                                       <input type="text" placeholder="Enter Category Name in Portuguese" id="category_name_pt" name="category_name_pt" class="form-control">
                                                  </div>
                                                  <div>
                                                       <p id="validation_err2" class="text-danger custom_danger_clss  mt-2 ml-2"></p>
                                                  </div>
                                        </div>
                                        <div class="modal-footer">
                                             <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                             <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                        </form>
                                   </div>
                              </div>
                         </div>
                         <!-- end modal -->
                         <div class="table-responsive" style="margin-bottom: 20px">
                              <table class="table table-striped table-hover dataTables-example">
                                   <thead>
                                        <tr>
                                             <th>#</th>
                                             <th>Category Name(English)</th>
                                             <th>Category Name(Espanish)</th>
                                             <th>Category Name(Portuguese)</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
     $(document).ready(function() {
          get_data();

          $("#add_subcategory").on('submit', function(e) {
               e.preventDefault();
               var formData = new FormData(this);
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $("meta[name='token']").attr('value')
                    }
               });
               $.ajax({
                    type: 'POST',
                    url: "{{ route('add/category') }}",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                         console.log(res);
                         if (res.message == "The category name field is required.") {
                              $('#validation_err').text('The category name field is required.');
                              setInterval(function() {
                                   $('#validation_err').text('');
                              }, 3000);
                         } else if (res.message == "The category name es field is required.") {
                              $('#validation_err1').text('The category name es field is required.');
                              setInterval(function() {
                                   $('#validation_err1').text('');
                              }, 3000);
                         } else if (res.message == "The category name pt field is required.") {
                              $('#validation_err2').text('The category name Portuguese field is required.');
                              setInterval(function() {
                                   $('#validation_err1').text('');
                              }, 3000);
                         } else {
                              swal({
                                   position: 'center',
                                   type: 'success',
                                   title: 'Category Added Successfully!',
                                   showConfirmButton: false,
                                   timer: 2000
                              });
                              location.reload();
                         }
                    }
               });
          });

     });

     function get_data() {
          var search = $('#search').val();
          $.ajax({
               url: "{{route('category/list')}}",
               type: "post",
               data: {
                    _token: "{{ csrf_token() }}",
                    search: search
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
                              row += `
							<tr>
							<td> ${res.data.from++} </td>
                              <td>${value.category_name? value.category_name : '-'}</td>
                              <td>${value.category_name_es? value.category_name_es : '-'}</td>
                              <td>${value.category_name_pt? value.category_name_pt : '-'}</td>
                              <td>
					          <a href="{{url('admin/subcategory')}}/${value.id}" class="mt-1 btn btn-xs btn-primary"><i class="fa fa-eye"></i> view subcategory</a>
                                   &nbsp;
                                   <a href="javascript:void(0)" onclick="deleteCategory(${value.id})" class="mt-1 btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
                              </td>
                            `;
                         })
                         $('#user_data').html(row);
                         let pagination = `
						<button type="button" onclick="getData('${res.data.first_page_url}')" class="btn btn-light text-muted"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></button>
						`;
                         if (res.data.prev_page_url) {
                              pagination += `
							<button onclick="getData('${res.data.prev_page_url}')" class="btn btn-light">${res.data.current_page - 1}</button>
							`;
                         }
                         pagination += `
						<button class="btn btn-light active">${res.data.current_page}</button>
						`;
                         if (res.data.next_page_url) {
                              pagination += `
							<button onclick="getData('${res.data.next_page_url}')" class="btn btn-light">${res.data.current_page + 1}</button>
							`;
                         }
                         pagination += `
						<button type="button" onclick="getData('${res.data.last_page_url}')" class="btn btn-light text-muted"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i> </button>
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


     function deleteCategory(id) {
          var url   =    "{{ route('delete/category',['category_id']) }}";
          url  =    url.replace('category_id',id);
          $.ajax({
               type: 'DELETE',
               url: url,
               data : {'_token':"{{ csrf_token() }}"},
               success: function(res) {
                    swal({
                         position: 'center',
                         type: 'success',
                         title: 'Category deleted Successfully!',
                         showConfirmButton: false,
                         timer: 4000
                    });
                    location.reload();
               },
               error : function(res){
                    swal({
                         position  :    'center',
                         type      :    'error',
                         title     :    'Unsuccessful!',
                         text      :    res.responseJSON.message
                    });
               }
          });
     }
     


     
</script>

@endsection