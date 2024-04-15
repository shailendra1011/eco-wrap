<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('send/otp', 'Api\User\AuthController@sendSms');
Route::get('user/privacy/policy', 'Api\User\SettingController@privacyPolicy');
// it set app language on the basis of user/driver request language anbd return response 
Route::group(['middleware' => 'setLocalLanguage'], function () {
    // User signup api route
    Route::post('user/signup', 'Api\User\AuthController@signup');
    //user check email and mobile
    Route::post('user/check/email', 'Api\User\AuthController@checkEmailMobile');
    // User Login api route
    Route::post('user/login', 'Api\User\AuthController@login');
    // User Forgot Password
    Route::post('user/reset/password', 'Api\User\AuthController@forgotPassword');
    // Driver Signup api route
    Route::post('driver/signup', 'Api\Driver\AuthController@driverSignup');
    //user check email and mobile
    Route::post('driver/check/email', 'Api\Driver\AuthController@checkEmailMobile');
    // Driver Login api route
    Route::post('driver/login', 'Api\Driver\AuthController@driverlogin');
    //Add document
    Route::post('driver/add/document', 'Api\Driver\AuthController@vehicleDetails');
    // driver forget password
    Route::post('driver/reset/password', 'Api\Driver\AuthController@forgotPassword');

    Route::post('send/sms', 'Api\User\AuthController@sendSms');



    Route::group(['namespace' => 'Api\User', 'prefix' => 'user', 'middleware' => 'auth:user-api'], function () {

        // Home Controller routes
        Route::post('home', 'HomeController@home');
        Route::post('delete/account','AuthController@deleteAccount');
        Route::post('store/list', 'HomeController@storeList');
        Route::post('store/detail', 'HomeController@storeDetails');
        Route::post('view/reviews', 'HomeController@allReviews');
        Route::post('update/address', 'HomeController@updateAddress');

        Route::get('referral/code','HomeController@referralCode');

        Route::get('referral/code/rewards','HomeController@referralCodeRewards');

        Route::get('profile/details', 'HomeController@profileDetails');

        //Setting Controller routes
        Route::post('add/wishlist', 'SettingController@addWishList');
        Route::get('wishlist', 'SettingController@wishlistStores');
        Route::post('review', 'SettingController@reviewAndRating');
        Route::post('add/deliveryAddress', 'SettingController@addDeliveryAddress');
        Route::get('deliveryAddress/list', 'SettingController@DeliveryAddressList');
        Route::post('edit-profile', 'SettingController@editProfile');
        Route::post('delete/address', 'SettingController@deleteDeliveryAddress');
        Route::post('change/language', 'SettingController@changeLanguage');
        Route::post('help/support', 'SettingController@helpAndSupport');
        Route::get('faq', 'SettingController@faq');

        //Product Controller routes
        Route::post('product/list', 'ProductController@productList');
        Route::post('product/detail', 'ProductController@productDetail');
        Route::post('addProduct/cart', 'ProductController@userProductCart');
        Route::get('view/cart', 'ProductController@viewProductCart');
        Route::post('update/cart/product', 'ProductController@cartProductQuantityUpdate');
        Route::get('product/recent/search', 'ProductController@getRecentSearchedProductList');
        Route::post('product/search', 'ProductController@searchProduct');

        //  Apply promo code to cart
        Route::post('apply/promocode','ProductController@applyPromocode');

        //Order Controller routes
        Route::post('product/order', 'OrderController@addOrder');
        Route::post('reserve/table', 'OrderController@reserveTable');
        Route::post('order/number', 'OrderController@generateOrderNumber');

        //My Order controller routes
        Route::post('myorder', 'MyOrderController@myOrder');
        Route::post('order/detail', 'MyOrderController@orderDetail');
        Route::post('track/order', 'MyOrderController@trackOrder');
        Route::post('item/detail', 'MyOrderController@itemDetail');

        // payment gateway
        Route::post('payment', 'OrderController@payStripe');
        Route::post('refund', 'OrderController@refund');
        Route::get('saved-card', 'OrderController@getSavedCards');

        //notification routes
        Route::get('notification/list','NotificationController@notification_list');
    });
});

Route::group(['namespace' => 'Api\Driver', 'prefix' => 'driver', 'middleware' => 'auth:driver-api'], function () {

    Route::post('subscription/list', 'SubscriptionController@subscriptionList');
    Route::post('buy/subscription', 'SubscriptionController@driverBuySubscription');
    //Home Controller Api
    Route::get('homepage', 'HomeController@incomingOrders');
    Route::post('online/status', 'HomeController@driverOnlineStatus');
    Route::post('accept/order', 'HomeController@driverAcceptOrder');
    Route::get('ongoing/order', 'HomeController@onoingOrder');
    Route::post('order/status', 'HomeController@statusUpdate');
    Route::post('order/detail', 'HomeController@orderDetail');
    Route::post('order/delivered', 'HomeController@orderDelivered');
    Route::post('allOrder', 'HomeController@allOrder');
    Route::post('allOrder/detail', 'HomeController@allOrderDetail');

    //Setting controller Api
    Route::post('update/profile', 'SettingController@updateProfile');
    Route::post('update/vehicleDetail', 'SettingController@vehicleDetailUpdate');
    Route::post('change/language', 'SettingController@changeLanguage');
    Route::post('cms', 'SettingController@cms');
});
