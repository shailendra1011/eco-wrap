@extends('admin.layout.app')
@section('content')

<section class="view_challenge_sec">
     <div class="main_div_boxess">
          <div class="vdo_boxess">
               <div class="row">
                    <input type="hidden" id="driver_id" value="{{$documents->id}}">
                    <div class="vdo_inner_divv" style="margin-left: 8%;">
                         <p class="votes_status" style="margin-left: 35%;">Driving License</p>
                         <img src="{{$documents->driving_license}}" alt="img" width="450" height="450">
                    </div>
                    <div class="vdo_inner_divv" style="margin-left: 2%;">
                         <p class="votes_status" style="margin-left: 35%;"> Vehicle Registration</p>
                         <img src="{{$documents->vehicle_registration}}" alt="img" width="450" height="450">
                    </div>
               </div>
          </div>
          <div class="row" style="margin-left: 45%; margin-top:5%">
               @if($documents->document_status==1)
               <div style="margin-right:5%;">
                    <button class="mt-3 btn btn-xm btn-btn btn-success" onclick="AcceptDocument()">Accept</button>
               </div>
               <div>
                    <button class="mt-3 btn btn-xm btn-btn btn-danger" data-toggle="modal"
                         data-target="#myModal">Reject</button>
               </div>
               @endif
               @if($documents->document_status==2)
               <div>
                    <button class="mt-3 btn btn-xm btn-btn btn-danger" data-toggle="modal"
                         data-target="#myModal">Reject</button>
               </div>
               @endif
               @if($documents->document_status==3)
               <div style="margin-right:5%;">
                    <button class="mt-3 btn btn-xm btn-btn btn-success" onclick="AcceptDocument()">Accept</button>
               </div>
               @endif
          </div>
          <!-- launch modal for add category -->
          <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
               <div class="modal-dialog">
                    <div class="modal-content animated bounceInRight">
                         <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                              <h4 class="modal-title">Document rejection reason</h4>
                         </div>
                         <div class="modal-body">
                              <form>
                                   <textarea class=" form-control" id="message" name="" rows="10" cols="50"></textarea>
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                              <button type="button" class="btn btn-primary" onclick="RejectDocument()">Send
                                   Mail</button>
                         </div>
                         </form>
                    </div>
               </div>
          </div>
          <!-- end modal -->
     </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
     crossorigin="anonymous"></script>
<script>
     function AcceptDocument() {
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
                    var driver_id = $('#driver_id').val();
                    var status = 2;
                    $.ajax({
                         url: "{{route('accept/document')}}",
                         type: "post",
                         data: {
                              _token: "{{ csrf_token() }}",
                              id: driver_id,
                              document_status: status
                         },
                         success: function(res) {
                              console.log(res);
                              swal({
                                   position: 'center',
                                   type: 'success',
                                   title: 'Document Accepted!',
                                   showConfirmButton: false,
                                   timer: 2000
                              });
                              window.location.href = "{{route('admin.driver')}}";

                         },
                    });
                    
               });

     }

     function RejectDocument() {
          var driver_id = $('#driver_id').val();
          var message = $('#message').val();
          var status = 3;
          $.ajax({
               url: "{{route('reject/document')}}",
               type: "post",
               data: {
                    _token: "{{ csrf_token() }}",
                    id: driver_id,
                    message : message,
                    document_status: status
               },
               success: function(res) {
                    console.log(res);
                    swal({
                         position: 'center',
                         type: 'success',
                         title: 'Document Rejected!',
                         showConfirmButton: false,
                         timer: 2000
                    });
                 
                  window.location.href = "{{route('admin.driver')}}";
               },
          });
     }
</script>
@endsection