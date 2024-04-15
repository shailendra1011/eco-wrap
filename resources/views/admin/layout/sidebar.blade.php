<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle"
                        src="{{Auth::user()->image?url(Auth::user()->image):asset('admin/img/logoVector.svg')}}"
                        height="120" width="120" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold" style="margin-left: 14%;">Admin Panel</span>
                        <span class="text-muted text-xs block">
                        </span>
                    </a>
                </div>
                <div class="logo-element">
                    <img alt="image" class="rounded-circle"
                        src="{{Auth::user()->image?url(Auth::user()->image):asset('admin/img/profile_small.jpg')}}"
                        height="60" width="60" />

                </div>
            </li>
            <li class="{{Request::is('admin/home*')?'active':''}}">
                <a href="{{route('admin.dashboard')}}"><i class="fa fa-th-large"></i> <span
                        class="nav-label">Dashboard</span></a>
            </li>
            <li class="{{Request::is('admin/vendor*')?'active':''}}">
                <a href="{{route('admin.vendor')}}"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Vendor
                        Management</span></a>
            </li>
            <li class="{{Request::is('admin/subscribed-plans*')?'active':''}}">
                <a href="#"><i class="fa fa-user"></i> <span class="nav-label">Subscription</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{Request::is('admin/subscribed-plans/1')?'active':''}}"><a href="{{route('subscribeddata',['type'=>1])}}">Driver</a>
                    </li>
                    <li class="{{Request::is('admin/subscribed-plans/2')?'active':''}}"><a
                            href="{{route('subscribeddata',['type'=>2])}}">Vendor</a></li>


                </ul>
            </li>
            <li class="{{Request::is('admin/user*')?'active':''}}">
                <a href="{{route('admin.user')}}"><i class="fa fa-user-circle"></i> <span class="nav-label">user
                        Management</span></a>
            </li>
            <li class="{{Request::is('admin/driver*')?'active':''}}">
                <a href="{{route('admin.driver')}}"><i class="fa fa-automobile"></i> <span class="nav-label">Driver
                        Management</span></a>
            </li>
            <li class="{{Request::is('admin/coupon*')?'active':''}}">
                <a href="{{route('admin.coupon.index')}}"><i class="fa fa-dollar"></i> <span class="nav-label">Coupon Codes</span></a>
            </li>
            <li class="{{Request::is('admin/category*')?'active':''}}">
                <a href="{{route('admin.category')}}"><i class="fa fa-bandcamp"></i> <span class="nav-label">Category
                        Management</span></a>
            </li>
            <li class="{{Request::is('admin/banners')?'active':''}}">
                <a href="{{route('banner')}}"><i class="fa fa-file-image-o"></i> <span class="nav-label">Banner
                        Management</span></a>
            </li>

            <li class="{{Request::is('admin/help-support')?'active':''}}">
                <a href="{{route('help.queries')}}"><i class="fa fa-question-circle"></i> <span class="nav-label">Help & Support</span></a>
            </li>

            <li class="{{Request::is('admin/faq')?'active':''}}">
                <a href="{{route('admin.faq.index')}}"><i class="fa fa-question"></i> <span class="nav-label">FAQs</span></a>
            </li>

            {{-- <li class="active">
                <a href="#"><i class="fa fa-user"></i> <span class="nav-label">User Management</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{Request::is('admin/user-management/seller*')?'active':''}}"><a href="#">Option</a></li>
                    <li class="{{Request::is('admin/user-management/buyer*')?'active':''}}"><a href="#">Option</a></li>


                </ul>
            </li> --}}

        </ul>
    </div>
</nav>