<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('logo.svg') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <style>

        .content {
            text-align: justify;
            margin: 0% 5% 0% 5%;
        }

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
                        <h1>This <b>Privacy Policy</b> page is under development.</h1>
                            <img src="{{asset('under_construction.gif')}}" class="img-responsive">
                        </center> -->

                        <h1 align="center">Privacy Policy</h1>
                        <br>
                        <p>
                            This Privacy Policy governs how the Voowx app collects, uses, maintains, and discloses information collected from users for providing the services to the users. This privacy policy is applied to both apps and all the services and products offered by Voowx.
                        </p>
                        <h3>
                            Personal information identification
                        </h3>
                        <p>
                            As you must be using our service for getting a list of restaurant and pharmacy options, we ought to take your personal information and provide you with the best solutions according to your requirements. You can take a monthly subscription to the app and can take benefit of the services, and resources available on the app. Users must be asked for their name, address, contact number, email, and some other details. But users can deny providing such information and this may lead that they will get fewer services as the user must agree to the privacy policy.
                        </p>
                        
                        <h3>
                            Non-Personal Identification Information
                        </h3>
                        <p>
                            We may also collect some non-personal information whether the user interacts with our app or not. This information may contain the device name, bandwidth, and some technical information through which the user accesses our app.
                        </p>
                        
                        <h3>
                            External links
                        </h3>
                        <p>
                            Although we have created a safe platform for our users, the user must adopt a policy of caution before accessing external links that are mentioned in the app. Our links are genuine but sometimes these links can lead to external links that might not be suitable for your device. So, we don't take any guarantee if there are any external links and it will be at complete user risk if they try to access the link.
                        </p>
                        
                        <h3>
                            Social Media Platforms
                        </h3>
                        
                        <p>
                            Users are advised to use social media platforms wisely and communicate/engage with them with due care and caution regarding their own privacy and personal details. This app nor its owners will ever ask for personal or sensitive information through social media platforms and encourage users wishing to discuss sensitive details to contact them through primary communication channels such as by telephone or email.
                        </p>
                        
                        <h3>
                            How We Use Collected Information?
                        </h3>
                        
                        <p>
                            Voowx collects and uses users' personal information for the following purposes:
                        </p>
                        
                        <ol>
                            <li>
                                To improve platform 
                                <br>
                                We continuously strive to improve our platform services based on the information and feedback that we receive from our users.
                            </li>
                            <br>
                            <li>
                                To improve customer service
                                <br>
                                Your information helps us to more effectively respond to your customer service requests and support needs.
                            </li>
                        </ol>
                        <br>
                        <h3>
                            What things Voowx deliver?
                        </h3>
                        
                        <p>
                            Basically, our services are made for customers and we work on specific drivers to provide our customers with the best services according to their requirements. There are specific sections available in the app and when a user interacts with the section, our app sends a notification and the results are produced for the customer accordingly. We ensure all the data of our customers are safe with us and there is no chance of data leakage.
                        </p>
                        
                        <h3>
                            How Voowx Protect Your Information?
                        </h3>
                        
                        <p>
                            We adopt appropriate data collection, storage, and security measures to protect against unauthorized access, disclosure, or destruction of your personal information, username, password, transaction information, and data stored on our app.
                        </p>
                        
                    </div>
                </div>
            </section>            
        </div>
    </div>
    
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh37Xc0jk=" crossorigin="anonymous"></script> -->
  
</body>

</html>