<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\HelpAndSupport;
use App\Models\StoreImage;
use App\Models\UserAddress;
use App\Models\UserRating;
use App\Models\UserReserveTable;
use Illuminate\Http\Request;
use App\Models\UserWishlist;
use App\User;
use App\Models\Faq;
use App\Models\PrivacyPolicy;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductImage;

class SettingController extends Controller
{
    public function addWishList(Request $request)
    {
        if ($request->type == 'true') {
            $data = $request->validate([
                'product_id' => 'required'
            ]);
            $data['user_id'] = Auth::user()->id;
            UserWishlist::create($data);
        } else if ($request->type == 'false') {
            UserWishlist::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->delete();
        }
        return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
    }

    public function wishlistStores(Request $request)
    {

        $wishlistProducts = UserWishlist::where('user_id', Auth::user()->id)
                                        ->with('product_details')
                                        ->get();

        foreach ($wishlistProducts as $wishlistProduct) {

            $user_language = User::where('id', Auth::user()->id)->first();
            if ($user_language->user_language == 'es') {

                $wishlistProduct->product_details->product_name =   $wishlistProduct->product_details->product_name_es;

                $wishlistProduct->product_details->subcategory_name    =    $wishlistProduct->product_details->sub_category->subcategory_name_es;

            } elseif ($user_language->user_language == 'pt') {
                $wishlistProduct->product_details->product_name =   $wishlistProduct->product_details->product_name_pt;
                
                $wishlistProduct->product_details->subcategory_name    =    $wishlistProduct->product_details->sub_category->subcategory_name_pt;
            } else {

                $wishlistProduct->product_details->subcategory_name    =    $wishlistProduct->product_details->sub_category->subcategory_name_pt;
            }

            $image = ProductImage::where('product_id', $wishlistProduct->product_id)->first();

            $wishlistProduct->product_details->product_image    =   $image->product_image;

            $wishlist = UserWishlist::where('product_id', $wishlistProduct->product_id)->where('user_id', Auth::user()->id)->first();

            if ($wishlist != null) {
                $wishlistProduct->isWishList = true;
            } else {
                $wishlistProduct->isWishList = false;
            }
        }

        return res(200, trans('CustomMessages.user.signup_success'), $wishlistProducts);
    }

    public function reviewAndRating(Request $request)
    {
        $data = $request->validate([
            'rating'   => 'required',
            'review'   => 'required',
            'store_id' => 'required'
        ]);
        $data['user_id'] = Auth::user()->id;
        $review = UserRating::create($data);
        $userRating = UserRating::where('id', $review->id)->with('user_detail')->limit(1)->get();
        return res(200, trans('CustomMessages.user.signup_success'), $userRating);
    }

    public function addDeliveryAddress(Request $request)
    {
        $data = $request->validate([
            'country_code'    => 'required',
            'mobile'          => 'required',
            'name'            => 'required',
            'house_no'        => 'required',
            'address'         => 'required',
            'city'            => 'required',
            'house_type'      => 'required',
            'pin_code'        => 'required',
            'latitude'        => 'required',
            'longitude'       => 'required',
        ]);
        $data['user_id'] = Auth::user()->id;
        UserAddress::create($data);
        return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
    }

    public function DeliveryAddressList()
    {
        $address = UserAddress::where('user_id', Auth::user()->id)->get();
        return res(200, trans('CustomMessages.user.signup_success'), $address);
    }

    //delete delivery address
    public function deleteDeliveryAddress(Request $request)
    {
        $isDeleted = UserAddress::where('id',$request->id)->delete();
        if($isDeleted){
             return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
        }
    }

    //change user language
    public function changeLanguage(Request $request)
    {
        //  Getting auth user data
        $user   =   User::find(Auth::user()->id);

        //  Updating user languange 
        $user->user_language    =   $request->user_language;

        if ($request->has('country') && $request->country != null) {
            
            $user->country  =   $request->country;
        }

        //  Saving udpated user language
        $user->save();
        
        return res(200, trans('CustomMessages.user.signup_success'), $user);
    }


    //edit profile
    public function editProfile(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required',
           // 'email' => 'required|unique:users,email,' . Auth::user()->id . ',id'
        ]);
        $user = User::where('id', Auth::user()->id)->first();
        if ($request->hasFile('user_image')) {
            $url = $request['user_image']->store(
                'user_image',
                'public'
            );
            $url = '/storage/'.$url;
            $valid['user_image'] = $url;
            $user->update($valid);
            return res(200, trans('CustomMessages.user.signup_success'), $user);
        } else {
            $user->update($valid);
            return res(200, trans('CustomMessages.user.signup_success'),  $user);
        }
    }
        public function helpAndSupport(Request $request)
        {
            $valid = $request->validate([
                'name'         => 'required',
                'country_code' => 'required',
                'mobile'       => 'required',
                'comment'      => 'required'
            ]);
            $valid['user_id'] = Auth::user()->id;
            HelpAndSupport::create($valid);
            return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
        }

        public function faq()
        {
            $faq = Faq::select('id','question','answer')->get();
            return res(200, trans('CustomMessages.user.signup_success'), $faq);
        }
        public function privacyPolicy()
        {
            $privacy = PrivacyPolicy::select('id','cms')->first();
            return res(200, trans('CustomMessages.user.signup_success'), $privacy);
        }
   }

