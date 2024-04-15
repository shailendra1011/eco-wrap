<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('admin/img/favicon.png') }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center lockscreen animated fadeInDown">
        <div>
            <div class="m-b-md">
            <img alt="image" class="img-circle circle-border" src="https://s3.amazonaws.com/uifaces/faces/twitter/ok/128.jpg">
            </div>
            <h3>{{ Auth::user()->name}}</h3>
            <p>{{ __('Please confirm your password before continuing.') }}</p>
            <form class="m-t" role="form" method="POST" action="{{ route('password.confirm') }}">
                <div class="form-group">
                    @csrf
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width">{{ __('Confirm Password') }}</button>
            </form>
            <br>
            @if (Route::has('password.request'))
                <small>
                    <a class="" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </small>
            @endif
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('admin/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>

</body>

</html>