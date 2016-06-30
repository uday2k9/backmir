<?php

/*
|--------------------------------------------------------------------------
| Application Routes for application
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route:: get('/','Frontend\HomeController@index');
Route:: post('/','Frontend\HomeController@index');
Route::resource('home','Frontend\HomeController');
//Route::get('/error','Frontend\HomeController@errorpage');

Route::resource('basecon','Frontend\BaseController');
Route::resource('image','MyController');

Route::get('/refresh-token', function(){
    return csrf_token();
});

// ======================= BRAND CONTROLLER FUNCTIONALITY START  ============================

Route:: get('/brands','Frontend\BrandController@index');   
Route:: get('/brand-details/{id}','Frontend\BrandController@brandDetails');
Route:: post('/brand-details/{id}','Frontend\BrandController@brandDetails');
Route:: get('/brand-dashboard','Frontend\BrandController@brandDashboard');  /* To Show the brand dashboard */ 
Route:: get('/brand-account','Frontend\BrandController@brandAccount');      /* To Show the brand account  */ 
Route:: post('/brand-account','Frontend\BrandController@brandAccount');      /* To post data the brand account  */ 
Route:: get('/validatecalltime','Frontend\BrandController@validateCalltime');  /* To validate Call Date Time Ajax */
Route:: post('/validatecalltime','Frontend\BrandController@validateCalltime');  
Route:: get('/change-password','Frontend\BrandController@brand_change_pass');    /* Change password for Brand */
Route:: post('/change-password','Frontend\BrandController@brand_change_pass');   /* Change password for Brand */

Route:: get('/brand-creditcards','Frontend\BrandController@brand_creditcard_details');    /* Change password for Brand */
Route:: post('/brand-creditcards','Frontend\BrandController@brand_creditcard_details');   /* Change password for Brand */
Route:: get('/brand-paydetails','Frontend\BrandController@brand_paydetails');    /* Change password for Brand */
Route:: post('/brand-paydetails','Frontend\BrandController@brand_paydetails');   /* Change password for Brand */

Route:: get('/brand-shipping-address','Frontend\BrandController@brandShippingAddress'); 		/* edit brand shipping address */
Route:: post('/brand-shipping-address','Frontend\BrandController@brandShippingAddress');		/* edit brand shipping address */

Route:: get('/create-brand-shipping-address','Frontend\BrandController@createBrandShippingAddress');   /* create brand shipping address */
Route:: post('/create-brand-shipping-address','Frontend\BrandController@createBrandShippingAddress');	/* create brand shipping address */
Route:: get('/edit-brand-shipping-address','Frontend\BrandController@editBrandShippingAddress'); 		/* edit brand shipping address */
Route:: post('/edit-brand-shipping-address','Frontend\BrandController@editBrandShippingAddress');		/* edit brand shipping address */
Route:: get('/delete-brand-shipping-address','Frontend\BrandController@delAddress');                    /* delete brand shiping address */
Route:: get('/sold-products','Frontend\BrandController@soldProducts');                    /* delete brand shiping address */

Route:: post('/subscription-history','Frontend\BrandController@subscriptionHistory');
Route:: get('/subscription-history','Frontend\BrandController@subscriptionHistory');             

//============================== BRAND CONTROLLER FUNCTIONALITY END ========================

 //Package for brand
 Route::controller('/package', 'Frontend\PackageController');

// ============================== For Payment Methods START=========================== 

// ============================== For Payment Methods End ============================= 

// Product Share Start //
Route:: get('/saveShare','Frontend\ProductController@saveShare'); 
Route:: post('/saveShare','Frontend\ProductController@saveShare');
// Product Share Start //

// ======================= Add To Cart Fuctionality Start  =======================

Route:: get('/allmycard','Frontend\CartController@cart');           // runnng...
Route:: post('/allmycard','Frontend\CartController@cart');          // runnng...
Route:: get('/updateCart','Frontend\CartController@updateCart');    // runnng...
Route:: post('/updateCart','Frontend\CartController@updateCart');   // runnng...
Route:: get('/deleteCart','Frontend\CartController@deleteCart');    // runnng...
Route:: post('/deleteCart','Frontend\CartController@deleteCart');   // runnng...
Route:: get('/show-cart','Frontend\CartController@showAllCart');
Route:: get('/reorder','Frontend\CartController@reorder');           // runnng...
Route:: post('/reorder','Frontend\CartController@reorder');          // runnng...

Route:: get('/coupon-cart','Frontend\CartController@coupon_cart');          // runnng...
Route:: post('/coupon-cart','Frontend\CartController@coupon_cart');          // runnng...

Route:: get('/redeem-cart','Frontend\CartController@redeem_cart');          // runnng...
Route:: post('/redeem-cart','Frontend\CartController@redeem_cart');          // runnng...

Route:: get('/allmycard1','Frontend\Product1Controller@cart1');  
Route:: post('/allmycard1','Frontend\Product1Controller@cart1');  
Route:: post('/mycarttest','Frontend\Product1Controller@mycarttest');
Route:: get('/mycarttest','Frontend\Product1Controller@mycarttest');
Route:: get('/carttest','Frontend\HomeController@cart');
Route:: get('/mycart','MyController@cart');  
Route:: get('/allCart','Frontend\CartController@cart2');  // runnng...

Route:: get('/social-content','Frontend\CartController@socialShareContent');    //Social Share Content
Route:: post('/social-content','Frontend\CartController@socialShareContent');  //Social Share Content


Route:: get('/getallrate','Frontend\Product1Controller@getallrate');  
Route:: post('/getallrate','Frontend\Product1Controller@getallrate');  

/// To delete all items from cart which are lying for last 4 hours
Route:: get('/cleanup-cart','Frontend\CartController@cleanupCart');   // runnng...

// ======================= Product Route +========================
Route:: resource('product','Frontend\ProductController');   
Route:: post('/getIngDtls','Frontend\ProductController@getIngDtls');  
Route:: get('/getFormFactor','Frontend\ProductController@getFormFactor');  
Route:: post('/getFormFactor','Frontend\ProductController@getFormFactor');  
Route:: get('/product-details/{param}','Frontend\Product1Controller@productDetails'); 
Route:: get('/my-products','Frontend\ProductController@allProductByBrand'); 

Route:: get('/getFormFactorPrice','Frontend\ProductController@getFormFactorPrice'); 
Route:: post('/getFormFactorPrice','Frontend\ProductController@getFormFactorPrice'); 
Route:: get('/edit-product/{id}','Frontend\ProductController@edit_product'); 
Route:: get('/delete-product/{id}','Frontend\ProductController@delete_product'); 

Route:: post('/productPost','Frontend\ProductController@productPost');

Route:: get('/private/{sku}','Frontend\ProductController@private_product'); 

// ======================= Product Route +========================


// ======================= CheckOut Start ========================

//Route:: get('checkout-step1','Frontend\CheckoutController@checkoutStep1');   
Route:: get('/checkout-step2','Frontend\CheckoutController@checkoutStep2'); 
Route:: post('/checkout-step2','Frontend\CheckoutController@checkoutStep2');
Route:: get('/checkout-step3','Frontend\CheckoutController@checkoutStep3'); 
Route:: post('/checkout-step3','Frontend\CheckoutController@checkoutStep3'); 
Route:: get('/checkout-step4','Frontend\CheckoutController@checkoutStep4'); 
Route:: post('/checkout-step4','Frontend\CheckoutController@checkoutStep4'); 

Route:: post('/checkout-submit-step2','Frontend\CheckoutController@checkoutSubStep2');
Route:: post('/checkout-submit-step3','Frontend\CheckoutController@checkoutSubStep3');
Route:: post('/checkout-guest-login','Frontend\CheckoutController@checkoutguestlogin');
Route:: post('/checkout-guest-submit','Frontend\CheckoutController@checkoutguestsubmit'); 
Route:: get('/checkout-wholesale','Frontend\CheckoutController@checkoutWholesale'); 



Route:: get('checkout',['uses' => 'Frontend\CheckoutController@checkoutStep1', 'as' => 'checkout']);    

Route:: get('/checkout-paypal/{id}','Frontend\CheckoutController@checkoutPaypal');
Route:: post('/checkout-paypal/{id}','Frontend\CheckoutController@checkoutPaypal');

Route:: get('/paypal-notify','Frontend\CheckoutController@paypalNotify');
Route:: post('/paypal-notify','Frontend\CheckoutController@paypalNotify');
Route:: get('/checkout-success','Frontend\CheckoutController@success');
Route:: post('/checkout-success','Frontend\CheckoutController@success');
Route:: get('/checkout-cancel','Frontend\CheckoutController@cancel');
Route:: post('/checkout-cancel','Frontend\CheckoutController@cancel');

Route:: get('/checkout-authorize/{id}','Frontend\CheckoutController@checkoutAuthorize');
Route:: post('/checkout-authorize/{id}','Frontend\CheckoutController@checkoutAuthorize');

Route:: get('/checkout-member-login','Frontend\CheckoutController@checkoutMemberLogin'); 
Route:: post('/checkout-member-login','Frontend\CheckoutController@checkoutMemberLogin');
Route:: post('/uspsAddressValidate','Frontend\CheckoutController@uspsAddressValidate');
Route:: get('/social-share-content','Frontend\CheckoutController@socialShareContent');    //Social Share Content
Route:: post('/social-share-content','Frontend\CheckoutController@socialShareContent');  //Social Share Content

Route:: get('/socialShareCheckout','Frontend\ProductController@socialShareCheckout');  // Social Share From Checkout Page
Route:: post('/socialShareCheckout','Frontend\ProductController@socialShareCheckout'); // Social Share From Checkout Page

//======================== Paypal notify Url Call Start==============================//

Route:: get('/wc-api/WC_Gateway_Paypal/','Frontend\CheckoutController@paypalNotify');
Route:: post('/wc-api/WC_Gateway_Paypal/','Frontend\CheckoutController@paypalNotify');

//======================== Paypal notify Url Call End ==============================//

// ======================= CheckOut End ========================

// =======================REGISTER CONTROLLER START ===============================

Route:: resource('register','Frontend\RegisterController');              
Route:: get('/register','Frontend\RegisterController@index');            /* For Show signup page - registration */
Route:: post('/getState','Frontend\RegisterController@getState');         /* For Save signup page - registration [call store function on controller]*/
Route:: get('/activateLink/{id}/{parameter}','Frontend\RegisterController@activateLink'); /* For Register user activation */
Route:: post('/emailChecking','Frontend\RegisterController@emailChecking');  /* For duplicate email checking - registration [call store function on controller]*/
Route:: post('/emailChecking2','Frontend\RegisterController@emailChecking2');  /* For duplicate email checking - registration [call store function on controller]*/

Route:: post('/usernameChecking','Frontend\RegisterController@usernameChecking'); /* For duplicate email checking - registration [call store function on controller] */

Route:: get('/brandregister','Frontend\RegisterController@brandRegister');            /* For Show signup page - registration */
Route:: post('/brandregister','Frontend\RegisterController@brandRegister');            /* For Show signup page - registration */
Route:: post('/updateDate','Frontend\RegisterController@updateDate');           /* For ajax sign up page calender date update */


Route:: post('/usernameEmailChecking','Frontend\RegisterController@usernameEmailChecking'); /* For duplicate email & username checking  */


// =======================REGISTER CONTROLLER END ===============================

// ======================= HOME CONTROLLER START ===============================

Route:: get('/brandLogin','Frontend\HomeController@brand_login');       /* For showing Brand login page*/
Route:: post('/brandLogin','Frontend\HomeController@brand_login');    /* For Member login page, use for login */
Route:: get('/loginexpire','Frontend\HomeController@login_expire');
Route:: get('/contactadmin/{id}','Frontend\HomeController@contactadmin');

Route:: post('/renew','Frontend\HomeController@renew');

Route:: get('/brandlogin','Frontend\HomeController@brand_login');       /* For showing Brand login page*/
Route:: post('/brandlogin','Frontend\HomeController@brand_login');    /* For Member login page, use for login */

Route:: get('/member-dashboard','Frontend\MemberController@index');     /* To Show the memeber dashboard */ 
Route:: get('/member-account','Frontend\MemberController@memberAccount');         /* To Show the memeber account */ 
Route:: post('/member-account','Frontend\MemberController@memberAccount');         /* To Show the memeber account */ 

Route:: get('/member-shipping-address','Frontend\MemberController@memberShippingAddress');         /* To Show the memeber account */ 
Route:: post('/member-shipping-address','Frontend\MemberController@memberShippingAddress');         /* To Show the memeber account */ 

Route:: get('/create-member-shipping-address','Frontend\MemberController@createMemberShippingAddress');   /* create brand shipping address */
Route:: post('/create-member-shipping-address','Frontend\MemberController@createMemberShippingAddress');	/* create brand shipping address */
Route:: get('/edit-member-shipping-address','Frontend\MemberController@editMemberShippingAddress'); 		/* edit brand shipping address */
Route:: post('/edit-member-shipping-address','Frontend\MemberController@editMemberShippingAddress');		/* edit brand shipping address */
Route:: get('/delete-member-shipping-address','Frontend\MemberController@delAddress');    

Route:: get('/brand-forgot-password','Frontend\HomeController@brand_forgotPassword');    /* For brand forgot passsword */
Route:: post('/brand-forgot-password','Frontend\HomeController@brand_forgotPassword');   /* For brand forgot passsword */
Route:: get('/brand-reset-password/{id}','Frontend\HomeController@brand_resetpassword');     /* For brand Reset passsword */
Route:: post('/brand-reset-password/{id}','Frontend\HomeController@brand_resetpassword');    /* For brand Reset passsword */

Route:: get('/memberLogin','Frontend\HomeController@member_login');                         /* For showing Member login page*/
Route:: post('/memberLogin','Frontend\HomeController@member_login');                        /* For Member login page, use for login */
Route:: get('/member-changepass','Frontend\MemberController@member_change_pass');    
Route:: post('/member-changepass','Frontend\MemberController@member_change_pass');    

Route:: get('/member-forgot-password','Frontend\HomeController@member_forgotPassword');    /* For member forgot passsword */
Route:: post('/member-forgot-password','Frontend\HomeController@member_forgotPassword');   /* For member forgot passsword */
Route:: get('/member-reset-password/{id}','Frontend\HomeController@member_resetpassword');    /* For member Reset passsword */
Route:: post('/member-reset-password/{id}','Frontend\HomeController@member_resetpassword');   /* For member Reset passsword */

Route:: get('/userLogout','Frontend\HomeController@userLogout');    /* For member logout */
//Route:: get('/brandLogout','Frontend\HomeController@brandLogout');      /* For brand logout */

Route:: get('/search-tags','Frontend\HomeController@searchtags');    /* For Search Tags */
Route:: post('/search-tags','Frontend\HomeController@searchtags');   /* For Search Tags */

Route:: get('/home-next','Frontend\HomeController@homenext');    /* For Search Tags */
Route:: post('/home-next','Frontend\HomeController@homenext');   /* For Search Tags */
Route:: get('/newsletterajax','Frontend\HomeController@newsletterajax');    /* For Search Tags */
Route:: post('/newsletterajax','Frontend\HomeController@newsletterajax');   /* For Search Tags */
//Route:: get('/page-not-found','Frontend\HomeController@show404');   /* For Search Tags */
Route:: get('/tag-popularity','Frontend\HomeController@tagPopularity');   /* For Tag Popularity */
Route:: post('/tag-popularity','Frontend\HomeController@tagPopularity');   /* For Tag Popularity */

Route::get('/facebook', 'Frontend\HomeController@facebook_redirect');
Route::get('/account/facebook', 'Frontend\HomeController@facebook');
Route::post('/account/facebook', 'Frontend\HomeController@facebook');

Route::get('/google', 'Frontend\HomeController@google_redirect');
Route::get('/account/google', 'Frontend\HomeController@google');
Route::post('/account/google', 'Frontend\HomeController@google');

//============================ HOME CONTROLLER END ================================

//============================ FAO CONTROLLER START ===============================
Route:: get('/faqs', 'Frontend\FaqController@faqList');
//============================ FAO CONTROLLER START ===============================

//===============================BRAND COUPONS===============================//

resource('/brandcoupons', 'Frontend\CouponController');
    get('/change_status/{id}/{param}', 'Frontend\CouponController@change_status');
    get('/checkCouponCode', 'Frontend\CouponController@checkCouponCode');
    post('/checkCouponCode', 'Frontend\CouponController@checkCouponCode');

    //==============================END BRAND COUPON============================//

//============================ Corn Controller Start ==============================
Route:: get('/cron','Frontend\CronController@index'); 
Route:: get('/sendpass','Frontend\CronController@sendpasswordmail');
Route:: get('/onlineusers','Frontend\CronController@onlineusers');

Route:: get('/cron/checksubscription','Frontend\CronController@checksubscription'); 
//============================ Corn Controller End ================================

//============================ ORDER CONTROLLER START ==============================
Route:: get('/order-history','Frontend\OrderController@index');  
Route:: get('/order-detail/{id}','Frontend\OrderController@order_detail');
Route:: get('/rate-product/{id}','Frontend\OrderController@rateProduct');  
Route:: post('/rate-product/{id}','Frontend\OrderController@rateProduct');
Route:: get('/rate-ajax','Frontend\OrderController@rateAjax');
Route:: post('/rate-ajax','Frontend\OrderController@rateAjax');
Route::controller('/orders', 'Frontend\OrderController');

Route::controller('/file', 'Frontend\FileController');

Route:: get('/wholesale-status/{id}/{status}','Frontend\OrderController@wholesale_status');  
//Route:: post('/wholesale-checkout','Frontend\OrderController@post_order_checkout');
Route:: post('/wholesale-checkout','Frontend\OrderController@wholesaleCheckout');

//============================ ORDER CONTROLLER END ================================

//============== STATIC FRONT PAGE URL START [** Write this Static section bellow all route **] ====================
Route:: get('/contact-us','Frontend\CmsController@contactUs');   /* For Show content */
Route:: post('/contact-us','Frontend\CmsController@contactUs');   /* For Show content */
//============== STATIC FRONT PAGE URL START [** Write this Static section bellow all route **] ====================
//============================= STATIC FRONT PAGE URL END ===========================================================



Route::resource('book','BookController');
Route:: get('/inventory','Frontend\InventoryController@inventory');   
Route:: post('/inventory','Frontend\InventoryController@inventory');   
Route:: get('/inventory-products/{id}','Frontend\InventoryController@inventory_details');   /* For Show content */
//Route:: get('/inventory-products/{id}/{page}','Frontend\InventoryController@inventory_details');   /* For Show content */

// ===================================== Admin area ================================
get('admin', function () {
  return redirect('/admin/home');
});



$router->group([
  'namespace' => 'Admin',
  'middleware' => 'auth',
], function () {
	
    resource('admin/post', 'PostController');

    resource('admin/home', 'HomeController');
    resource('admin/admin-profile', 'HomeController@getProfile');
    resource('admin/change-password', 'HomeController@changePass');
    resource('admin/populate', 'HomeController@populate');
    resource('admin/searchtags', 'HomeController@populate_searchtags');


    
    resource('admin/ingredient', 'IngredientController');
    resource('admin/ingredient-list', 'IngredientController@index');
    resource('admin/ingredient-search', 'IngredientController@ingredient_search');    
    resource('admin/vitamin-search', 'IngredientController@viatmin_auto_search');
    resource('admin/component-search', 'IngredientController@component_auto_search');


	resource('admin/shipment-package', 'ShipmentController');
	post('ajax/create_child_types', 'AjaxController@create_child_types');
	post('ajax/create_selected_order_counter', 'AjaxController@create_selected_order_counter');
	post('/ajax/getshipmentpackage', 'AjaxController@getshipmentpackage');
	post('/ajax/storetopostmasterqueue', 'AjaxController@storetopostmasterqueue');
	post('/ajax/generatelabel', 'AjaxController@generatelabel');
// ++++++++++++++++++++++++++++===++++++++++++++ Product ++++++++++++++++++++++++++++===++++++++++++++ 
    resource('admin/product', 'ProductController');
    //resource('admin/product-list', 'ProductController');
    get('admin/product-list/{id}', 'ProductController@index');
    get('admin/product-list/{id}/{param}', 'ProductController@index');
    get('admin/ratings/{id}', 'ProductController@ratings');
    post('admin/ratings/{id}', 'ProductController@ratings');
    get('admin/destroyrating/{id}', 'ProductController@destroyrating');
    post('admin/destroyrating/{id}', 'ProductController@destroyrating');
    get('admin/ratingstatus/{id}', 'ProductController@ratingstatus');
    post('admin/ratingstatus/{id}', 'ProductController@ratingstatus');
    
    get('admin/orders/filter', 'OrderController@filters');
    post('admin/orders/filter', 'OrderController@filters');

    post('admin/add-process-queue', 'OrderController@add_process_queue');
    get('admin/push_order_process/{id}', 'OrderController@push_order_process');

    
    get('admin/change_related_status/{id}/{param}', 'ProductController@change_related_status');
    resource('admin/discontinue-product-search', 'ProductController@discontinue_product_search');  

// ++++++++++++++++++++++++++++===++++++++++++++ Product ++++++++++++++++++++++++++++===++++++++++++++ 

    resource('admin/book', 'BookController');

    resource('admin/member/status', 'MemberController@status');
    resource('admin/member/admin_active_status', 'MemberController@admin_active_status');
    resource('admin/member/admin_inactive_status', 'MemberController@admin_inactive_status');
    resource('admin/member', 'MemberController');
    post('admin/member', 'MemberController@index');
    
    post('admin/add-member', 'MemberController@add_member');
    get('admin/add-member', 'MemberController@add_member');
    post('admin/brand-orders/{id}', 'MemberController@brand_orders');
    get('admin/brand-orders/{id}', 'MemberController@brand_orders');
    
    
    resource('admin/brand/status', 'BrandController@status');
    resource('admin/brand/admin_active_status', 'BrandController@admin_active_status');
    resource('admin/brand/admin_inactive_status', 'BrandController@admin_inactive_status');
    resource('admin/brand', 'BrandController');
    post('admin/brand', 'BrandController@index');
    
    resource('admin/vitamin', 'VitaminController');
	get('admin/upload', 'UploadController@index');
    resource('admin/cms', 'CmspageController');
    resource('admin/faq', 'FaqController');

    resource('admin/sitesetting', 'SitesettingController');
    
    resource('admin/formfactor', 'FormfactorController');
    resource('admin/form-factor', 'FormfactorController@index');
    resource('admin/form-factor-name', 'FormfactorController@form_factor_name');

   // resource('admin/package', 'PackageController');
   // resource('admin/package', 'PackageController@index');
   // resource('admin/package-name', 'PackageController@package_name');

    //Package for admin
    route::controller('admin/package', 'PackageController');
    resource('admin/package-list', 'PackageController@getIndex');
    resource('admin/package-name', 'PackageController@package_name');
    resource('admin/package-type-name', 'PackageController@package_type_name');
    resource('admin/package-type-list', 'PackageController@getType');
   
    resource('admin/orders', 'OrderController');
    post('admin/brand-search/', 'OrderController@brand_search');
    get('admin/brand-search/', 'OrderController@brand_search');

	get('admin/wholesale-orders', 'OrderController@wholesale_orders');
	get('admin/wholesale-offer/{id}', 'OrderController@wholesale_offer');
	post('admin/wholesale-offer/{id}', 'OrderController@wholesale_offer');


    
    get('admin/order-details/{id}', 'OrderController@orderDetails');
    get('admin/generate-packing-slip/{id}', 'OrderController@generatePackingSlip');

    // ========================== COUPON PAGES URL START ===============================//
    resource('admin/coupons','CouponController@index');
    // ========================== COUPON PAGES URL START ===============================//
    
   //************************************ Subscription ****************************************/
    resource('admin/subscription', 'SubscriptionController');
  
   //************************************ Coupon ****************************************/
    resource('admin/coupon', 'CouponController');
    get('admin/change_status/{id}/{param}', 'CouponController@change_status');
    get('admin/checkCouponCode', 'CouponController@checkCouponCode');
    post('admin/checkCouponCode', 'CouponController@checkCouponCode');

});

// Logging in and out
get('/auth/login', 'Auth\AuthController@getLogin');
post('/auth/login', 'Auth\AuthController@postLogin');
get('/auth/logout', 'Auth\AuthController@getLogout');



// admin/test
Route::group(
    array('prefix' => 'admin'), 
    function() {
        Route::get('forgotpassword', 'Admin\HomeController@forgotPassword');
        Route::post('forgotpasswordcheck', 'Admin\HomeController@forgotpasswordcheck');
        Route::get('resetpassword/{id}', 'Admin\HomeController@resetpassword');
        Route::post('updatepassword/{id}', 'Admin\HomeController@updatePassword');

    }
);



Route:: get('/{param}','Frontend\CmsController@showContent');   /* For Show content */
Route:: post('/{param}','Frontend\CmsController@showContent');   /* For Show content */
