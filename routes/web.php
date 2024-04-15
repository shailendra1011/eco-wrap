<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Ui\Presets\React;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('enLanguage', function (Request $request) {
    Session::put('locale', $request->language_code);
    Session::put('locale_language', $request->language);
    return redirect()->back();
})->name('enLanguage');
Route::post('esLanguage', function () {
    Session::put('locale', 'es');
    return redirect()->back();
})->name('esLanguage');


Route::group(['middleware' => 'setLocalLanguage'], function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/terms-and-conditions', function () {
        return view('site_info.terms_conditions');
    })->name('terms.and.conditions');

    Route::get('/privacy-policy', function () {
        return view('site_info.privacy_policy');
    })->name('privacy.policy');

    Auth::routes();

    Route::group(['middleware' => 'auth:web'], function () {
        Route::get('/subscription/plan', 'Vendor\SubscriptionController@plans')->name('subscription.plan');
        Route::get('/plan/{plan}', 'Vendor\SubscriptionController@show')->name('plans.show');
        Route::post('/subscription', 'Vendor\SubscriptionController@create')->name('subscription.create');
        Route::get('/cancel-subscription', 'Vendor\SubscriptionController@cancel')->name('subscription.cancel');

        Route::group(['middleware' => 'CheckStoreSubscription'], function () {

            Route::get('/home', 'HomeController@index')->name('home');

            Route::group(['namespace' => 'Vendor'], function () {

                //stripe route
                Route::get('/', 'StripeController@showProfile')->name('store.profile');
                Route::get('stripe/{id}', 'StripeController@redirectToStripe')->name('redirect.stripe');
                Route::get('connect/{token}', 'StripeController@saveStripeAccount')->name('save.stripe');
                Route::post('charge/{id}', 'StripeController@purchase')->name('complete.purchase');


                Route::get('recent', 'OrderController@recentOrder')->name('product.recent');

                // change store status(online/offline)
                Route::post('/change-store-status', 'VendorProfileController@changeStoreStatus')->name('change.store.status');
                // product controller start here
                Route::group(['prefix' => 'products'], function () {
                    Route::get('/', 'ProductController@index')->name('product');
                    Route::get('/add', 'ProductController@showAddForm')->name('product.new');
                    Route::post('/add', 'ProductController@addProduct')->name('product.add');
                    Route::post('/change-status', 'ProductController@changeProductStatus')->name('product.change-status');
                    Route::get('/edit/{id?}', 'ProductController@showEditProductForm')->name('product.showEditForm');
                    Route::post('/edit', 'ProductController@editProduct')->name('product.edit');
                    Route::delete('/delete/{product_id}', 'ProductController@destroy')->name('product.destroy');
                });
                // product controller end here

                // update profile route start here
                Route::get('/profile', 'VendorProfileController@index')->name('profile');
                Route::post('/update-profile', 'VendorProfileController@updateProfile')->name('profile.update');
                Route::post('/update-password', 'VendorProfileController@updatePassword')->name('password.update');

                // update profile route end here

                //  Coupon Management Routes
                Route::get('coupon/list','CouponController@index')->name('coupon');

                //  Coupon Add Routes
                Route::get('coupon/add',function(){
                    return view('vendor.coupon_code.add-coupon-code');
                })->name('coupon.add');
                
                //  Store Coupon Data
                Route::post('coupon/store','CouponController@store')->name('coupon.store');

                Route::post('coupon/update/status','CouponController@update')->name('coupon.change-status');

                //order management route start here

                //product order route start here
                Route::get('/orders/product', 'OrderController@getProductOrder')->name('product.orders');
                //product order route end here

                //pharmacy order route start here
                Route::get('/orders/pharmacy', 'OrderController@getPharmacyOrder')->name('pharmacy.orders');
                //pharmacy order route end here
                //food/hall/table order route start here
                Route::get('/orders/food', 'OrderController@getFoodOrder')->name('food.orders');
                Route::get('/orders/hall', 'OrderController@getFoodTableOrder')->name('food.table.orders');
                Route::post('/order/hall/change-status', 'OrderController@changeFoodTableOrderStatus')->name('food.table.orders.change-status');

                //food/hall/table order route end here
                Route::get('/order/details/{id?}', 'OrderController@getOrderDetails')->name('order.details');

                Route::post('order/change-status', 'OrderController@changeOrderStatus')->name('order.change-status');
                //order management route end here


                // refund /return route start here
                Route::get('refund', function () {
                    return back();
                })->name('refund');
                // refund /return route end here

                // earningroute start here
                Route::get('earning', function () {
                    return back();
                })->name('earning');
                // earningroute end here
            });
        });
    });
});
