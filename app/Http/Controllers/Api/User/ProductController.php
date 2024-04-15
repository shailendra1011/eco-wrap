<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use App\Models\UserProductCart;
use App\Models\SearchProduct;
use App\Store;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserWishlist;

class ProductController extends Controller
{
    public function ProductList(Request $request)
    {
        $products = Product::select('id', 'store_id', 'product_name', 'subcategory_id', 'product_name_es', 'price', 'discount', 'size', 'quantity')
            ->with('sub_category')
            ->where('store_id', $request->store_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->where('product_status', 1)
            ->get();
        foreach ($products as $product) {
            $image = ProductImage::where('product_id', $product->id)->first();
            $product->product_image = $image->product_image;
            //check product is in cart or not
            $inCart = UserProductCart::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();
            if ($inCart) {
                $product->isAddCart = true;
            } else {
                $product->isAddCart = false;
            }
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->user_language == 'es') {
                $product->product_name = $product->product_name_es;
                $product->subcategory_name = $product->sub_category->subcategory_name_es;
            } else if($user->user_language == 'pt') {
                $product->product_name = $product->product_name_pt;
                $product->subcategory_name = $product->sub_category->subcategory_name_pt;
            } else {
                $product->subcategory_name = $product->sub_category->subcategory_name;
            }
            unset($product->sub_category);
            unset($product->product_name_es);

            $wishlist = UserWishlist::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();

            if ($wishlist != null) {
                $product->isWishList = true;
            } else {
                $product->isWishList = false;
            }

        }

        return res(200, trans('CustomMessages.user.signup_success'), $products);
    }

    public function productDetail(Request $request)
    {
        if ($request->isSearch == 1) {
            $alreadyExists = SearchProduct::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->count();
            if (!$alreadyExists) {
                $searchProduct             = new SearchProduct;
                $searchProduct->user_id    = Auth::user()->id;
                $searchProduct->product_id = $request->product_id;
                $searchProduct->save();
            }
        }
        $product = Product::select('id', 'product_name', 'subcategory_id', 'product_name_es', 'price', 'discount', 'size', 'quantity', 'description', 'description_es')
            ->with('sub_category')
            ->with('productImages')
            ->where('id', $request->product_id)
            ->first();
        $user = User::where('id', Auth::user()->id)->first();
        if ($user->user_language == 'es') {
            $product->product_name = $product->product_name_es;
            $product->description = $product->description_es;
            $product->subcategory_name = $product->sub_category->subcategory_name_es;
        } else if($user->user_language == 'pt') {
            $product->product_name = $product->product_name_pt;
            $product->description = $product->description_pt;
            $product->subcategory_name = $product->sub_category->subcategory_name_pt;

        } else {
            $product->subcategory_name = $product->sub_category->subcategory_name;
        }
        //check product is in cart or not
        $inCart = UserProductCart::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();
        if ($inCart) {
            $product->isAddCart = true;
        } else {
            $product->isAddCart = false;
        }

        $wishlist = UserWishlist::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();

        if ($wishlist != null) {
            $product->isWishList = true;
        } else {
            $product->isWishList = false;
        }

        unset($product->product_name_es);
        unset($product->description_es);
        unset($product->product_name_pt);
        unset($product->description_pt);
        unset($product->sub_category);
        return res(200, trans('CustomMessages.user.signup_success'), $product);
    }

    public function userProductCart(Request $request)
    {
        $data = $request->validate([
            'store_id'      => 'required',
            'product_id'    => 'required'
        ]);
        // type = true, add product in cart
        if ($request->type == "true") {
            // check product is added of that store or not in cart
            $isAdd = UserProductCart::where('store_id', '!=', $request->store_id)->where('user_id', Auth::user()->id)->first();
            // if product not added in cart
            if ($isAdd == null) {
                $data['user_id'] = Auth::user()->id;
                $data['price'] = $request->price;
                $data['added_quantity'] = 1;
                UserProductCart::create($data);
                //total product count in cart
                $total_product_in_cart = UserProductCart::where('user_id', Auth::user()->id)->count();
                return res(200, trans('CustomMessages.user.signup_success'), ['total_product_in_cart' => $total_product_in_cart]);
            } else { // in else part we remove that stores product and add new store product
                UserProductCart::where('user_id', Auth::user()->id)->delete();
                $data['user_id'] = Auth::user()->id;
                $data['price'] = $request->price;
                $data['added_quantity'] = 1;
                UserProductCart::create($data);
                //total product count in cart
                $total_product_in_cart = UserProductCart::where('user_id', Auth::user()->id)->count();
                return res(200, trans('CustomMessages.user.signup_success'), ['total_product_in_cart' => $total_product_in_cart]);
            }
        } else if ($request->type == "false") { //type = false, remove product from cart
            UserProductCart::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->delete();
            //total product count in cart
            $total_product_in_cart = UserProductCart::where('user_id', Auth::user()->id)->count();
            return res(200, trans('CustomMessages.user.signup_success'), ['total_product_in_cart' => $total_product_in_cart]);
        }
    }

    public function viewProductCart()
    {
        $item = UserProductCart::where('user_id', Auth::user()->id)->first();
        if ($item) {
           
            $products = UserProductCart::select('id', 'product_id', 'store_id', 'price', 'added_quantity')
                ->where('user_id', Auth::user()->id)
                ->with('productDetail')
                ->get();
            //return($products);

            foreach ($products as $product) {
                $image = ProductImage::where('product_id', $product->product_id)->first();
                $product->product_image = $image->product_image;
                $user = User::where('id', Auth::user()->id)->first();
                if ($user->user_language == 'es') {
                    $product->productDetail->product_name = $product->productDetail->product_name_es;
                    $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name_es;
                } else if($user->user_language == 'pt') {
                    $product->productDetail->product_name = $product->productDetail->product_name_pt;
                    $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name_pt;

                } else {
                    $product->productDetail->subcategory_name = $product->productDetail->sub_category->subcategory_name;
                }
                $product->productDetail->stock_quantity = $product->productDetail->quantity;
                $category_id = Subcategory::where('id', $product->productDetail->subcategory_id)->first();
                $product->category_id = $category_id->category_id;

                // $product->shipping_charge = 
                unset($product->productDetail->sub_category);
                unset($product->productDetail->product_name_es);
                unset($product->productDetail->product_name_pt);
                // unset($product->productDetail->price);
                unset($product->productDetail->quantity);

                $wishlist = UserWishlist::where('product_id', $product->id)->where('user_id', Auth::user()->id)->first();

                if ($wishlist != null) {
                    $product->isWishList = true;
                } else {
                    $product->isWishList = false;
                }
            }

            $store_id  = UserProductCart::where('user_id', Auth::user()->id)->first();
            if ($store_id) {
                $user     = User::where('id', Auth::user()->id)->first();
                $stores   = Store::select('*', DB::raw('cast(6371 * acos(cos(radians(' . $user->user_lattitude . ')) * cos(radians(stores.store_latitude)) * cos(radians(stores.store_longitude) - radians(' . $user->user_longitude . ')) + sin(radians(' . $user->user_lattitude . ')) * sin(radians(stores.store_latitude))) as decimal(10,2)) as distance'))->first();
                if ($stores->distance <= 10) {
                    $shipping_charge = 49;
                } else if ($stores->distance > 10 || $stores->distance  < 15) {
                    $shipping_charge = 74;
                } else if ($stores->distance > 15 || $stores->distance  < 20) {
                    $shipping_charge = 99;
                }
            } else {
                $shipping_charge = 0;
            }
            $store    = Store::where('id', $store_id->store_id)->first();
            $store_online_payment = $store->completed_stripe_onboarding;

            //  Getting global discounts 
            $globalCouponCodes    =   \DB::table('coupon_codes')->where('coupon_type','global')->whereDate('end_date','>=',date('Y-m-d'))->get();

            //  Getting store coupon codes
            $storeCouponCodes   =   \DB::table('coupon_codes')->where('coupon_type','individual')->whereDate('end_date','>=',date('Y-m-d'))->get();

            return res(200, trans('CustomMessages.user.signup_success'), ['products' => $products, 'shipping_charge' => $shipping_charge, 'store_online_payment' => $store_online_payment,'global_coupon_codes'=>$globalCouponCodes,'store_coupon_codes'=>$storeCouponCodes]);
        } else {
            return res(200, trans('CustomMessages.user.signup_success'), ['products' => []]);
        }
    }

    public function applyPromocode(Request $request)
    {
        //  Validating the parameter
        $request->validate([
            'coupon_code_id'    =>  'required|exists:coupon_codes,id'
        ]);

        //  Getting coupon data
        $couponCodeData     =   \DB::table('coupon_codes')->whereId($request->coupon_code_id)->first();

        //  Checking if user has already used the copuon code or not
        $isCouponCodeAlreadyUsed    =   \DB::table('orders')->where('coupon_code_id',$request->coupon_code_id)
                                            ->where(function($query) use ($couponCodeData){
                                                $query->whereDate('created_at','>=',$couponCodeData->start_date);
                                                $query->whereDate('created_at','<=',$couponCodeData->end_date);
                                            })
                                            ->first();

        //  If data found in order table with this coupon code then
        if (!is_null($isCouponCodeAlreadyUsed) || !empty($isCouponCodeAlreadyUsed)) {
            
            //  Returning precondition failed response
            return res(412,trans('CustomMessages.user.cart.coupon_already_used'),[]);
        }

        //  Checking for min cart value for coupon code
        //  Getting user's cart value
        $userCartAmount     =   \DB::table('user_product_carts')
                                    ->select(\DB::raw('price*added_quantity AS total_amount'))
                                    ->where('user_id',Auth::user()->id)
                                    ->first();

        //  If user cart amount is less than min cart value then
        if ($userCartAmount->total_amount < $couponCodeData->min_cart_value) {
            
            //  Returning precondition failed response
            return res(412,trans('CustomMessages.user.cart.min_cart_value_failed',['price'=>$couponCodeData->min_cart_value]),[]);
        }

        //  Returning success response
        return res(200,trans('CustomMessages.user.cart.coupon_code_applied'));

                                            

    }

    public function cartProductQuantityUpdate(Request $request)
    {
        $data = $request->validate([
            'quantity'      => 'required',
            'product_id'    => 'required',
            'price'         => 'required'
        ]);

        UserProductCart::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->update(['added_quantity' => $request->quantity, 'price' => $request->price]);
        $product = UserProductCart::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->first();
        return res(200, trans('CustomMessages.user.signup_success'), ['added_quantity' => $product->added_quantity]);
    }

    public function searchProduct(Request $request)
    {
        if ($request->search != '') {
            $search = $request->search;
            $searchedProducts = Product::select('id', 'store_id', 'product_name', 'subcategory_id', 'product_name_es', 'price', 'discount')->with('sub_category')->where(function ($query) use ($search) {
                $user = User::where('id', Auth::user()->id)->first();
                if ($user->user_language == 'en') {
                    $query->where('product_name', 'LIKE', '%' . $search . '%');
                }
                if ($user->user_language == 'es') {
                    $query->where('product_name_es', 'LIKE', '%' . $search . '%');
                }
            });
            $searchedProducts = $searchedProducts->get();
            foreach ($searchedProducts as $product) {
                $image = ProductImage::where('product_id', $product->id)->first();
                $product->product_image = $image->product_image;
                //check product is in cart or not            
                $user = User::where('id', Auth::user()->id)->first();
                if ($user->user_language == 'es') {
                    $product->product_name = $product->product_name_es;
                    $product->subcategory_name = $product->sub_category->subcategory_name_es;
                } else {
                    $product->subcategory_name = $product->sub_category->subcategory_name;
                }
                unset($product->product_name_es);
                unset($product->sub_category);
            }

            return res(200, trans('CustomMessages.user.signup_success'), ['product_list' => $searchedProducts]);
        }
        return res(200, trans('CustomMessages.user.signup_success'), ['product_list' => []]);
    }

    //return recent searched product
    public function getRecentSearchedProductList()
    {
        $searchedProductList = SearchProduct::where('user_id', Auth::user()->id)->with('productDetail')->latest()->take(10)->get();
        foreach ($searchedProductList as $product) {
            $image = ProductImage::where('product_id', $product->product_id)->first();
            $product->product_image = $image->product_image;
            //check product is in cart or not            
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->user_language == 'es') {
                $product->productDetail->product_name = $product->productDetail->product_name_es;
            }
            unset($product->productDetail->product_name_es);
        }
        return res(200, trans('CustomMessages.user.signup_success'), ['recent_search' => $searchedProductList]);
    }
}
