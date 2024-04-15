<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('logo.svg') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        } */

        .content {
            text-align: justify;
            margin: 0% 5% 0% 5%;
        }

        /* .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        } */
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
                
            <!-- <div class="top-right links mr-5">
                {{-- @if(Auth::guard('admin')->check())
                <a href="{{ url('/admin/home') }}">Admin Home</a>
                @else
                <a href="{{ route('admin.login') }}">Admin Login</a>
                @endif --}}
            
                @if(Auth::guard('web')->check())
                <a href="{{ url('/home') }}">Vendor Home</a>
                @else
                <a href="{{ route('login') }}">{{__('StaticWords.vendor_login')}}</a>
                @endif
            </div> -->
        
        
        

        <div class="content">
            <section class="col-lg-12 ">
                <div class="card">
                    <div class="card-body">
                        <!-- <br><br><br><br><br><br> -->
                        <!-- <center>
                            <h1>This <b>Terms and Conditions</b> page is under development.</h1>
                            <img src="{{asset('under_construction.gif')}}" class="img-responsive">
                        </center> -->

                        <h1 align="center">
                            Terms and Conditions
                        </h1>

                        <h3>
                            You're Acceptance of These Terms
                        </h3>
                        
                        <p>
                            By using our app, you signify your acceptance of this policy and terms of service. If you do not agree to this policy, please do not use our platform. Your continued use of the platform following the posting of changes to this policy will be deemed your acceptance of those changes.
                        </p>
                        
                        <h3>
                            Your personal consent
                        </h3>
                        
                        <p>
                            By accessing our app, you agree to this privacy policy.
                        </p>
                    </div>
                </div>
            </section>            
        </div>
    </div>
    
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->
  
</body>

</html>