<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('logo.svg') }}" />

    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/cropper/cropper.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/switchery/switchery.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/nouslider/jquery.nouislider.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/ionRangeSlider/ion.rangeSlider.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css')}}"
        rel="stylesheet">

    <link href="{{asset('admin/css/plugins/clockpicker/clockpicker.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/select2/select2.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/plugins/dualListbox/bootstrap-duallistbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">

    {{-- section for add on css --}}

    @yield('style')
</head>

<body class="">
    <div id="wrapper">
        {{-- sidebar included here --}}
        @include('layouts.sidebar')
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top navbar-light bg-white shadow-sm " role="navigation"
                    style="margin-bottom: 0">
                    <div class="navbar-header"> <a class="navbar-minimalize minimalize-styl-2 btn btn-primary "
                            href="#"><i class="fa fa-bars"></i> </a>
                    </div>

                    <ul class="nav navbar-top-links navbar-right mr-5">
                        <li class=" text-center mr-lg-5"> {{__('StaticWords.vendor.store_status')}} <span
                                class="m-r-sm text-muted welcome-message"><input type="checkbox" name="store_status"
                                    id="store_status" class="js-switch" {{Auth::user()->store_status==1?'checked':''}}>
                                {{Auth::user()->store_status==1?__('StaticWords.vendor.online'):__('StaticWords.vendor.offline')}}</span>
                        </li>

                        <li>
                            <div class="dropdown">
                                @if(Request::is('home'))
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @php 
                                            $languages  =   \App\Language::select('language','language_code')->distinct('language')->get();
                                        @endphp
                                        {{ Session::get('locale_language')??"English" }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach($languages as $language)
                                            <a class="dropdown-item" href="{{ route('enLanguage') }}" onclick="event.preventDefault();
                                                                        document.getElementById('{{ $language->language_code }}-form').submit();">
                                                {{ $language->language }}
                                            </a>

                                            <form id="{{ $language->language_code }}-form" action="{{ route('enLanguage') }}" method="POST" class="d-none">
                                                @csrf
                                                <input type="hidden" name="language_code" value="{{ $language->language_code }}">
                                                <input type="hidden" name="language" value="{{ $language->language  }}">
                                            </form>
                                        @endforeach
                                        <!-- <a class="dropdown-item" href="{{ route('esLanguage') }}" onclick="event.preventDefault();
                                                                    document.getElementById('es-form').submit();">
                                            {{ __('Spanish') }}
                                        </a>

                                        <form id="es-form" action="{{ route('esLanguage') }}" method="POST" class="d-none">
                                            @csrf
                                        </form> -->

                                    </div>
                                @endif
                            </div>
                        </li>
                        <li class=" text-center mr-lg-5"> <span class="m-r-sm text-muted welcome-message">
                            </span>
                        </li>
                        <li class=" text-center mr-lg-5"> <span class="m-r-sm text-muted welcome-message">Welcome to
                                <b> {{ config('app.name', 'Laravel') }}</b></span>
                        </li>
                        <li>
                            <img alt="image" style="cursor: pointer" data-toggle="dropdown" class="rounded-circle"
                                src="{{Auth::user() && count(App\Models\StoreImage::where('store_id',Auth::user()->id)->get())?App\Models\StoreImage::where('store_id',Auth::user()->id)->inRandomOrder()->first()->store_image:asset('admin/userprofile.png')}}"
                                height="50" width="50" />

                            <b class=" ml-2 mt-4" style="cursor: pointer" data-toggle="dropdown"><i
                                    class="fa fa-caret-down fa-2x"></i></b>
                            <ul class="dropdown-menu animated fadeInLeft m-t-xs">
                                <li><a class="dropdown-item" href="{{route('profile')}}"><i
                                            class="fa fa-user mr-2"></i>{{__('StaticWords.vendor.profile')}}</a></li>
                                <li class="dropdown-divider"></li>
                                <li> <a class=" dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"> <i class="fa fa-sign-out"></i>
                                        {{__('StaticWords.vendor.logout')}}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">@csrf</form>
                                </li>
                            </ul>

                        </li>

                        {{--
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> <i
                                    class="fa fa-bell"></i> <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html" class="dropdown-item">
                                        <div> <i class="fa fa-envelope fa-fw"></i> You have 16 messages <span
                                                class="float-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="profile.html" class="dropdown-item">
                                        <div> <i class="fa fa-twitter fa-fw"></i> 3 New Followers <span
                                                class="float-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a href="grid_options.html" class="dropdown-item">
                                        <div> <i class="fa fa-upload fa-fw"></i> Server Rebooted <span
                                                class="float-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html" class="dropdown-item"> <strong>See All
                                                Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>--}}
                        {{-- <li>
                            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"> <i class="fa fa-sign-out"></i>
                                {{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                style="display: none;">@csrf</form>
                        </li> --}}
                    </ul>

                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content" style="min-height: 575px;">
                        @yield("content")
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="float-right">
                    <div> <strong>Copyright</strong> {{ config('app.name', 'Laravel') }} &copy; {{ now()->year
                        }}-{{Carbon\Carbon::now()->addYear(1)->year}}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- section for modal --}}
    @yield('modal')
    <!-- Mainly scripts -->

    <script src="{{asset('admin/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('admin/js/plugins/validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('admin/js/popper.min.js')}}"></script>
    <script src="{{asset('admin/js/bootstrap.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{asset('admin/js/inspinia.js')}}"></script>
    <script src="{{asset('admin/js/plugins/pace/pace.min.js')}}"></script>
    <script src="{{asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Chosen -->
    <script src="{{asset('admin/js/plugins/chosen/chosen.jquery.js')}}"></script>

    <!-- JSKnob -->
    <script src="{{asset('admin/js/plugins/jsKnob/jquery.knob.js')}}"></script>

    <!-- Input Mask-->
    <script src="{{asset('admin/js/plugins/jasny/jasny-bootstrap.min.js')}}"></script>

    <!-- Data picker -->
    <script src="{{asset('admin/js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>

    <!-- NouSlider -->
    <script src="{{asset('admin/js/plugins/nouslider/jquery.nouislider.min.js')}}"></script>

    <!-- Switchery -->
    <script src="{{asset('admin/js/plugins/switchery/switchery.js')}}"></script>

    <!-- IonRangeSlider -->
    <script src="{{asset('admin/js/plugins/ionRangeSlider/ion.rangeSlider.min.js')}}"></script>

    <!-- iCheck -->
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>

    <!-- MENU -->
    <script src="{{asset('admin/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>

    <!-- Color picker -->
    <script src="{{asset('admin/js/plugins/colorpicker/bootstrap-colorpicker.min.js')}}"></script>

    <!-- Clock picker -->
    <script src="{{asset('admin/js/plugins/clockpicker/clockpicker.js')}}"></script>

    <!-- Image cropper -->
    <script src="{{asset('admin/js/plugins/cropper/cropper.min.js')}}"></script>

    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="{{asset('admin/js/plugins/fullcalendar/moment.min.js')}}"></script>

    <!-- Date range picker -->
    <script src="{{asset('admin/js/plugins/daterangepicker/daterangepicker.js')}}"></script>

    <!-- Select2 -->
    <script src="{{asset('admin/js/plugins/select2/select2.full.min.js')}}"></script>

    <!-- TouchSpin -->
    <script src="{{asset('admin/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>

    <!-- Tags Input -->
    <script src="{{asset('admin/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>

    <!-- Dual Listbox -->
    <script src="{{asset('admin/js/plugins/dualListbox/jquery.bootstrap-duallistbox.js')}}"></script>
    <script src="{{asset('admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('admin/js/plugins/toastr/toastr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' })
        $(document).ready(function () {
            $(".chosen-select").chosen({
                width: "100%"
            });
            $(".dual_select").bootstrapDualListbox({
                selectorMinimalHeight: 160,
            });


            $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
            $('#data_2 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#from_date').on('keyup', function () {
                $(this).val('');
            });
            $('#to_date').on('keyup', function () {
                $(this).val('');
            });

            <?php
            if (Session::has('message')) {
                if ('success' == session('type')) {
                    ?>
                    toastr.success("<?php echo session('message'); ?>", "", {
                        "closeButton": true
                    });

                    <?php
                } else if ('failed' == session('type')) {
                    ?>
                    toastr.error('<?php echo trim(session('message')); ?>', {
                            "closeButton": true
                        });
                         <?php
                }
            }

            ?>
        });

       
        $('#user_type').on('change',function(){get_data();});
        $('#from_date').change(function(){get_data()});
        $('#to_date').change(function(){get_data()});

        //change store status
       
        $(document).ready(function() {
            //set initial state.           

            $('#store_status').change(function() {
                if(this.checked) {
                    var data={
                        status:1,
                        _token: "{{ csrf_token() }}"
                    }
                    changeStoreStatus(data);
                }else{
                    var data={
                        status:0,
                        _token: "{{ csrf_token() }}",
                    }
                    changeStoreStatus(data);
                }                       
            });
        });
    function changeStoreStatus(data)
    {
        $.ajax({       
        url : '{{ route("change.store.status") }}',
        type : 'POST',
        data : data
        }).done(function(response){
        console.log(response);   
        location.reload();    
        }).fail(function(response){
        location.reload();    

        console.log(response);
        }).always(function(response){ 
        location.reload();    


        });
    }

    </script>

    @yield('script')

</body>

</html>