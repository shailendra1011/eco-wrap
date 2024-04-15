
<?php

use Illuminate\Support\Facades\Route;
// Route for admin 

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login.submit');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout');
        Route::get('/home', 'DashboardController@index')->name('admin.dashboard');

        // Vendor Controller routes
        Route::get('vendor', 'VendorController@index')->name('admin.vendor');
        Route::post('vendor/data', 'VendorController@vendorList')->name('vendor/data');
        Route::put('vendor/update/data','VendorController@update')->name('vendor/update/data');
        Route::post('vendor/status', 'VendorController@vendorStatus')->name('vendor/status');

        // User Controller routes
        Route::get('user', 'UserController@index')->name('admin.user');
        Route::post('user/data', 'UserController@userList')->name('user/data');
        Route::post('user/status', 'UserController@userStatus')->name('user/status');
        Route::post('user/update', 'UserController@update')->name('user/update/data');


        // Banner Controller routes
        Route::get('/banners', 'BannerController@index')->name('banner');
        Route::post('/add', 'BannerController@add')->name('banner.add');
        Route::post('/update-delete', 'BannerController@updateDelete')->name('banner.update.delete');


        // Category Management Controller routes
        Route::get('/category', 'CategoryManagementController@index')->name('admin.category');
        Route::post('category/list', 'CategoryManagementController@categoryList')->name('category/list');
        Route::get('/subcategory/{id}', 'CategoryManagementController@subCategory')->name('admin.subcategory/{id}');
        Route::post('subcategory/list', 'CategoryManagementController@subCategoryList')->name('subcategory/list');
        Route::post('subcategory/status', 'CategoryManagementController@subCategoryStatus')->name('subcategory/status');
        Route::post('add/category', 'CategoryManagementController@addCategory')->name('add/category');
        Route::delete('category/{category_id}', 'CategoryManagementController@destroyCategory')->name('delete/category');
        Route::post('add/subcategory', 'CategoryManagementController@addSubCategory')->name('add/subcategory');
        Route::delete('subcategory/{subcategory_id}', 'CategoryManagementController@destroySubcategory')->name('delete/subcategory');


        Route::resource('faq','FaqController')->names('admin.faq');

        Route::post('faq-list','FaqController@faqList')->name('admin/faq/list');

        Route::get('help-support','SupportController@index')->name('help.queries');

        Route::post('queries-list','SupportController@queryList')->name('queries/data');

        Route::post('reply-to-query','SupportController@replyToQuery')->name('user/query/reply');

        Route::post('help-support-reply','SupportController@replyQuery')->name('help.query.reply');
    });
});
