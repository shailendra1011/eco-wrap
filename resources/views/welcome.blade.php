<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('admin/Eco-Wrap.png') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        html,
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
        }

        .content {
            text-align: center;
        }

        .title {
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
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
                
            <div class="top-right links mr-5">
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
            </div>
        
        
        

        <div class="content">
            <section class="col-lg-12 ">
                <div class="card" style="text-align: center;">
                    <div class="card-body"><br><br><br><br><br><br>
                        <center>
                            <h1>{{__('StaticWords.under_dev')}}</h1>
                            <img src="{{asset('under_construction.gif')}}" class="img-responsive">
                        </center>
                    </div>
                </div>
            </section>            
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  
</body>

</html>