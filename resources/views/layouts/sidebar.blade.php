<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle"
                        src="{{Auth::user() && count(App\Models\StoreImage::where('store_id',Auth::user()->id)->get())?App\Models\StoreImage::where('store_id',Auth::user()->id)->inRandomOrder()->first()->store_image:asset('admin/userprofile.png')}}"
                        height="120" width="120" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{Auth::user()->store_name}}</span>
                        <span class="text-muted text-xs block">
                        </span>
                    </a>
                </div>
                <div class="logo-element">
                    <img alt="image" class="rounded-circle"
                        src="{{Auth::user() && count(App\Models\StoreImage::where('store_id',Auth::user()->id)->get())?App\Models\StoreImage::where('store_id',Auth::user()->id)->inRandomOrder()->first()->store_image:asset('admin/userprofile.png')}}"
                        height="60" width="60" />
                </div>
            </li>

            <li class="{{Request::is('home')?'active':''}}">
                <a href="{{route('home')}}"><i class="fa fa-th-large"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.dashboard')}}</span></a>
            </li>
            <li class="{{Request::is('addProfileInfo*')?'active':''}}">
                <a href="{{route('addProfileInfo')}}"><i class="fa fa-shopping-cart"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.profile')}}</span></a>
            </li>
            <!-- <li class="{{Request::is('/')?'active':''}}">
                <a href="{{route('store.profile')}}"><i class="fa fa-bank"></i> <span class="nav-label">{{__('StaticWords.vendor.create_business_account')}}</span></a>
            </li> -->
            <!-- <li class="{{Request::is('subscription*')?'active':''}}">
                <a href="{{route('subscription.plan')}}"><i class="fa fa-bank"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.subscription')}}</span></a>
            </li> -->
            <li class="{{Request::is('products*')?'active':''}}">
                <a href="{{route('product')}}"><i class="fa fa-shopping-cart"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.product_management')}}</span></a>
            </li>
            @if(Auth::user()->category_id==2)
            {{-- checking for food type vendor --}}
            <li class="{{Request::is('orders*')?'active':''}}">
                <a href="{{route('pharmacy.orders')}}"><i class="fa fa-file-image-o"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.order_management')}}</span></a>
            </li>
            @endif
            @if(Auth::user()->category_id==3)
            {{-- checking for food type vendor --}}
            <li class="{{Request::is('orders*')?'active':''}}">
                <a href="{{route('product.orders')}}"><i class="fa fa-file-image-o"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.order_management')}}</span></a>
            </li>
            @endif
            @if(Auth::user()->category_id==1)
            <li class="{{Request::is('orders*')?'active':''}}">
                <a href="#"><i class="fa fa-user"></i> <span
                        class="nav-label">{{__('StaticWords.vendor.order_management')}}</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{Request::is('orders/food')?'active':''}}"><a
                            href="{{route('food.orders')}}">{{__('StaticWords.vendor.food_order')}}</a>
                    </li>
                    <li class="{{Request::is('orders/hall')?'active':''}}"><a
                            href="{{route('food.table.orders')}}">{{__('StaticWords.vendor.hall_order')}}</a></li>


                </ul>
            </li>
            @endif

           
           
            <li class="{{Request::is('earning*')?'active':''}}">
                <a href="{{route('earning')}}"><i class="fa fa-eur"></i> <span
                        class="nav-label">Total Revenue</span></a>
            </li> 

        </ul>
    </div>
</nav>