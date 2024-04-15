<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\StoreBanner;
use App\Models\StoreImage;
use App\Models\Subcategory;
use App\Models\UserProductCart;
use App\Models\UserRating;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Store;
use App\User;
use Laravel\Ui\Presets\React;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $request->validate([
            'current_latitude'     => 'required',
            'current_longitude'    => 'required'
        ]);
        $data = [];
        //get user language
        $user = User::where('id', Auth::user()->id)->first();
        if ($request->has('current_latitude') && $request->has('current_longitude')) {
            //update user lattitude and longitude
            User::where('id',Auth::user()->id)->update(['user_lattitude'=>$request->current_latitude, 'user_longitude'=>$request->current_longitude,'user_address'=>$request->user_address,'city'=>$request->city]);
            $featured_stores = Store::select('id', 'store_name', 'store_address', 'store_name_es', 'category_id', DB::raw('cast(6371 * acos(cos(radians(' . $request->current_latitude . ')) * cos(radians(stores.store_latitude)) * cos(radians(stores.store_longitude) - radians(' . $request->current_longitude . ')) + sin(radians(' . $request->current_latitude . ')) * sin(radians(stores.store_latitude))) as decimal(10,2)) as distance'))
                ->having('distance', '<=', '100');
            if ($user->user_language == 'es') {
                $featured_stores = $featured_stores->where('store_name_es', '!=', null);
            }
            if ($user->user_language == 'en') {
                $featured_stores = $featured_stores->where('store_name', '!=', null);
            }

            $featured_stores = $featured_stores->limit('5')
                ->orderBy('distance', 'ASC')
                ->get();

            foreach ($featured_stores as $store) {
                // get one image of store
                $image = StoreImage::where('store_id', $store->id)->first();
                $store->store_image = $image->store_image;
                if ($user->user_language == 'es') {
                    $store->store_name = $store->store_name_es;
                }
                unset($store->store_name_es);
            }
            $suggested_stores = Store::select('id', 'store_name', 'store_address', 'store_name_es', 'category_id', DB::raw('cast(6371 * acos(cos(radians(' . $request->current_latitude . ')) * cos(radians(stores.store_latitude)) * cos(radians(stores.store_longitude) - radians(' . $request->current_longitude . ')) + sin(radians(' . $request->current_latitude . ')) * sin(radians(stores.store_latitude))) as decimal(10,2)) as distance'))
                ->having('distance', '<=', '100');
            if ($user->user_language == 'es') {
                $suggested_stores = $suggested_stores->where('store_name_es', '!=', null);
            }
            if ($user->user_language == 'en') {
                $suggested_stores = $suggested_stores->where('store_name', '!=', null);
            }

            $suggested_stores = $suggested_stores->limit('5')
                ->orderBy('distance', 'ASC')
                ->get();

            foreach ($suggested_stores as $store) {
                $image = StoreImage::select('store_image')->where('store_id', $store->id)->first();
                $store->store_image = $image->store_image;
                if ($user->user_language == 'es') {
                    $store->store_name = $store->store_name_es;
                }
                unset($store->store_name_es);
            }
            $data['category'] = Category::select('id', 'category_name', 'category_name_es')->get();
            foreach ($data['category'] as $category) {
                if ($user->user_language == 'es') {
                    $category->category_name = $category->category_name_es;
                }
                unset($category->category_name_es);
            }
            $data['banners']  = StoreBanner::select('id', 'store_id', 'store_banner')->get();
            $data['featured_stores']  = $featured_stores;
            $data['suggested_stores']  = $suggested_stores;
            foreach ($data['banners'] as $banner) {
                $category_id = Store::where('id', $banner->store_id)->first();
                $banner->category_id = $category_id->category_id;
            }
            //total product count in cart
            $data['total_product_in_cart'] = UserProductCart::where('user_id',Auth::user()->id)->count();
            $data['user_detail'] = $user;
            return res(200, trans('CustomMessages.user.signup_success'), $data);
        }
    }


    public function storeList(Request $request)
    {
        $data = $request->validate([
            'type'                 => 'required',
            'current_latitude'     => 'required',
            'current_longitude'    => 'required'
        ]);
        $stores = $this->categoryWiseStores($request->type, $request->current_latitude, $request->current_longitude, $request->distance, $request->rating);
        return res(200, trans('CustomMessages.user.signup_success'), $stores);
        // return $stores;
    }

    public function profileDetails()
    {
        //  Getting user's referral code
        $profileDetails =   User::find(Auth::user()->id);

        //  Adding currently referral point reward set by Admin
        $profileDetails->referral_reward_point  =   REFERRAL_POINTS;

        //  Returning success response
        return res(200,'Profile Details',$profileDetails);
    }


    public function referralCodeRewards()
    {

        //  Getting user's referral code rewards
        $userReferralCodeRewardsData    =   \App\UserReferral::with('referred_to_user_data')->whereUserId(Auth::user()->id)->get();

        //  Returning success response
        return res(200,'Referral Reward Earnings',['referral_rewards'=>$userReferralCodeRewardsData]);
    }


    function categoryWiseStores($type, $current_latitude, $current_longitude, $distance, $rating)
    {
        if ($distance != null) {
            $distance = $distance;
        } else {
            $distance = 25;
        }
        $user = User::where('id', Auth::user()->id)->first();
        if ($type == 1 || $type == 2 || $type == 3) {
            if ($current_latitude != null && $current_longitude != null) {
                $stores = Store::select('id', 'store_name', 'store_address', 'store_name_es', 'category_id','isDining', DB::raw('cast(6371 * acos(cos(radians(' . $current_latitude . ')) * cos(radians(stores.store_latitude)) * cos(radians(stores.store_longitude) - radians(' . $current_longitude . ')) + sin(radians(' . $current_latitude . ')) * sin(radians(stores.store_latitude))) as decimal(10,2)) as distance'))
                    ->where('category_id', $type)
                    ->having('distance', '<=', $distance);
                if ($user->user_language == 'es') {
                    $stores = $stores->where('store_name_es', '!=', null);
                }
                if ($user->user_language == 'en') {
                    $stores = $stores->where('store_name', '!=', null);
                }
                $stores = $stores->limit('5')
                    ->orderBy('distance', 'ASC')
                    ->get();

                foreach ($stores as $store) {
                    $image = StoreImage::where('store_id', $store->id)->first();
                    $store->store_image = $image->store_image;
                    $store->isDining = $store->isDining == 1 ?  true : false;
                    // $wishlist = UserWishlist::where('store_id', $store->id)->where('user_id', Auth::user()->id)->first();
                    // if ($wishlist != null) {
                    //     $store->isWishList = true;
                    // } else {
                    //     $store->isWishList = false;
                    // }
                    $user_language = User::where('id', Auth::user()->id)->first();
                    if ($user_language->user_language == "en") {
                        $store->store_name = $store->store_name;
                    } else {
                        $store->store_name = $store->store_name_es;
                    }
                    unset($store->store_name_es);
                }
            }
        } else if ($type == 4) {   // type=>4 is used for featured stores
            if ($current_latitude != null && $current_longitude != null) {
                $stores = Store::select('id', 'store_name', 'store_address', 'store_name_es', 'category_id','isDining', DB::raw('cast(6371 * acos(cos(radians(' . $current_latitude . ')) * cos(radians(stores.store_latitude)) * cos(radians(stores.store_longitude) - radians(' . $current_longitude . ')) + sin(radians(' . $current_latitude . ')) * sin(radians(stores.store_latitude))) as decimal(10,2)) as distance'))
                    ->having('distance', '<=', $distance);
                if ($user->user_language == 'es') {
                    $stores = $stores->where('store_name_es', '!=', null);
                }
                if ($user->user_language == 'en') {
                    $stores = $stores->where('store_name', '!=', null);
                }
                $stores = $stores->limit('5')
                    ->orderBy('distance', 'ASC')
                    ->get();

                foreach ($stores as $store) { 
                    $image = StoreImage::where('store_id', $store->id)->first();
                    $store->store_image = $image->store_image;
                    $store->isDining = $store->isDining == 1 ?  true : false;
                    // $wishlist = UserWishlist::where('store_id', $store->id)->where('user_id', Auth::user()->id)->first();
                    // if ($wishlist != null) {
                    //     $store->isWishList = true;
                    // } else {
                    //     $store->isWishList = false;
                    // }
                    $user_language = User::where('id', Auth::user()->id)->first();
                    if ($user_language->user_language == "en") {
                        $store->store_name = $store->store_name;
                    } else {
                        $store->store_name = $store->store_name_es;
                    }
                    unset($store->store_name_es);
                }
            }
        } else if ($type == 5) { // type=>4 is used for suggested stores
            if ($current_latitude != null && $current_longitude != null) {
                $stores = Store::select('id', 'store_name', 'store_address', 'store_name_es', 'category_id','isDining', DB::raw('cast(6371 * acos(cos(radians(' . $current_latitude . ')) * cos(radians(stores.store_latitude)) * cos(radians(stores.store_longitude) - radians(' . $current_longitude . ')) + sin(radians(' . $current_latitude . ')) * sin(radians(stores.store_latitude))) as decimal(10,2)) as distance'))
                    ->having('distance', '<=', $distance);
                if ($user->user_language == 'es') {
                    $stores = $stores->where('store_name_es', '!=', null);
                }
                if ($user->user_language == 'en') {
                    $stores = $stores->where('store_name', '!=', null);
                }
                $stores = $stores->limit('5')
                    ->orderBy('distance', 'ASC')
                    ->get();

                foreach ($stores as $store) {
                    $image = StoreImage::where('store_id', $store->id)->first();
                    $store->store_image = $image->store_image;
                    $store->isDining = $store->isDining == 1 ?  true : false;
                    // $wishlist = UserWishlist::where('store_id', $store->id)->where('user_id', Auth::user()->id)->first();
                    // if ($wishlist != null) {
                    //     $store->isWishList = true;
                    // } else {
                    //     $store->isWishList = false;
                    // }
                    $user_language = User::where('id', Auth::user()->id)->first();
                    if ($user_language->user_language == "en") {
                        $store->store_name = $store->store_name;
                    } else {
                        $store->store_name = $store->store_name_es;
                    }
                    unset($store->store_name_es);
                }
            }
        }
        return $stores;
    }

    public function storeDetails(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required'
        ]);
        $user_language = User::where('id', Auth::user()->id)->first();
        $store = Store::select('id', 'store_name', 'store_name_es', 'store_address', 'category_id','completed_stripe_onboarding')
            ->with('store_images')
            ->where('id', $request->store_id)
            ->first();
        $user_language = User::where('id', Auth::user()->id)->first();
        if ($user_language->user_language == "es") {
            $store->store_name = $store->store_name_es;
        }

        if ($user_language->user_language == "pt") {
            $store->store_name = $store->store_name_pt;
        }
        unset($store->store_name_es);
        unset($store->store_name_pt);

        // $wishlist = UserWishlist::where('store_id', $request->store_id)->where('user_id', Auth::user()->id)->first();
        // if ($wishlist != null) {
        //     $store->isWishList = true;
        // } else {
        //     $store->isWishList = false;
        // }
        $category   = Subcategory::select('id', 'subcategory_name', 'subcategory_name_es')->where('category_id', $store->category_id)->get();
        foreach ($category as $cat) {
            if ($user_language->user_language == "es") {
                $cat->category = $cat->subcategory_name_es;
            }
            if ($user_language->user_language == "pt") {
                $cat->category = $cat->subcategory_name_pt;
            }
            unset($cat->subcategory_name_es);
            unset($cat->subcategory_name_pt);
        }
        $store->subcategory = $category;
        if ($user_language->user_language == 'es') {
            $store->store_name  = $store->store_name_es;
            $store->subcategory = $store->subcategory_name_es;
        }

        if ($user_language->user_language == 'pt') {
            $store->store_name  = $store->store_name_pt;
            $store->subcategory = $store->subcategory_name_pt;
        }
        //need to do dynamic in future
        $store->about_us      = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s";
        $store->review        = UserRating::select('id', 'user_id', 'rating', 'review')->with('user_detail')->where('store_id', $request->store_id)->limit(2)->get();
        return res(200, trans('CustomMessages.user.signup_success'), $store);
    }

    public function allReviews(Request $request)
    {
        $data = $request->validate([
            'store_id' => 'required'
        ]);
        $array['reviews'] = UserRating::select('id', 'user_id', 'rating', 'review')
            ->with('user_detail')
            ->where('store_id', $request->store_id);
            if($request->rating != ''){
                $array['reviews'] =  $array['reviews'] ->where('rating', $request->rating);
            }
           
            $array['reviews'] = $array['reviews']->get();
        $array['excellent']       = UserRating::where('rating', 5)->where('store_id', $request->store_id)->count();
        $array['good']            = UserRating::where('rating', 4)->where('store_id', $request->store_id)->count();
        $array['average']         = UserRating::where('rating', 3)->where('store_id', $request->store_id)->count();
        $array['below average']   = UserRating::where('rating', 2)->where('store_id', $request->store_id)->count();
        $array['poor']            = UserRating::where('rating', 1)->where('store_id', $request->store_id)->count();
        $array['total_review']    = UserRating::where('store_id', $request->store_id)->count();
        $total_rating             = UserRating::where('store_id', $request->store_id)->sum('rating');
        $array['average_rating']  =  $total_rating / $array['total_review'];

        return res(200, trans('CustomMessages.user.signup_success'), $array);
    }

    public function updateAddress(Request $request)
    {
        $data = $request->validate([
            'user_lattitude' => 'required',
            'user_longitude' => 'required',
            'user_address'   => 'required',
            'city'           => 'required'
        ]);
        User::where('id',Auth::user()->id)->update(['user_lattitude'=>$request->user_lattitude,'user_longitude'=>$request->user_longitude,'user_address'=>$request->user_address,'city'=>$request->city,]);
        $user = User::where('id',Auth::user()->id)->first();
        return res(200, trans('CustomMessages.user.signup_success'), $user);
    }
}
