<?php

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
Auth::routes();
Route::get('/facebook/login', 'Web\FacebookController@redirectToProvider');
Route::get('/facebook/callback', 'Web\FacebookController@handleProviderCallback');
Route::post('/newsletter', 'Web\NewsletterController@store');

Route::group(
[
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localize', 'localeSessionRedirect', 'localizationRedirect' ]
],
function()
{

	Route::get('/', 'Web\HomepageController@index')->name('home');

    Route::get('/privacy', 'Web\PrivacyController@index')->name('privacy');

	// Page	
	Route::get('/'.LaravelLocalization::transRoute('routes.page_show'), 'Web\PageController@show');

	// News
	Route::get('/'.LaravelLocalization::transRoute('routes.news'), 'Web\NewsController@index')->name('news');
	Route::get('/'.LaravelLocalization::transRoute('routes.news_show'), 'Web\NewsController@show')->name('news');

	// Blog
	Route::get('/'.LaravelLocalization::transRoute('routes.blog'), 'Web\BlogController@index')->name('blog');
	Route::get('/'.LaravelLocalization::transRoute('routes.blog_show'), 'Web\BlogController@show')->name('blog');

	// Service
	Route::get('/'.LaravelLocalization::transRoute('routes.service'), 'Web\ServiceController@index')->name('service');
	Route::get('/'.LaravelLocalization::transRoute('routes.service_show'), 'Web\ServiceController@show')->name('service');

	// Staff	
	Route::get('/'.LaravelLocalization::transRoute('routes.staff_show'), 'Web\StaffController@show')->name('staff');

	// Photogallery
	Route::get('/'.LaravelLocalization::transRoute('routes.photogallery'), 'Web\PhotogalleryController@index')->name('photogallery');
	Route::get('/'.LaravelLocalization::transRoute('routes.photogallery_show'), 'Web\PhotogalleryController@show')->name('photogallery');

	// Videogallery
	Route::get('/'.LaravelLocalization::transRoute('routes.videogallery'), 'Web\VideogalleryController@index')->name('videogallery');
	Route::get('/'.LaravelLocalization::transRoute('routes.videogallery_show'), 'Web\VideogalleryController@show')->name('videogallery');

	// Portfolio
	Route::get('/'.LaravelLocalization::transRoute('routes.portfolio'), 'Web\PortfolioController@index')->name('portfolio');
	Route::get('/'.LaravelLocalization::transRoute('routes.portfolio_show'), 'Web\PortfolioController@show')->name('portfolio');

	// Category
	Route::get('/'.LaravelLocalization::transRoute('routes.category'), 'Web\CategoryController@index')->name('category');

	// Subcategory
	Route::get('/'.LaravelLocalization::transRoute('routes.subcategory'), 'Web\SubcategoryController@index')->name('category');

	// Product
	Route::get('/'.LaravelLocalization::transRoute('routes.product_show'), 'Web\ProductController@show')->name('category');
	Route::get('/p/search', 'Web\ProductController@index')->name('category');

	// Cart
	Route::get('/cart/checkout', 'Web\CartController@checkout');
	Route::get('/cart/shipment', 'Web\CartController@shipment')->middleware('auth');
	Route::get('/cart/payment', 'Web\CartController@payment')->middleware('auth');
	Route::get('/cart/summary', 'Web\CartController@summary')->name('staff')->middleware('auth');
	Route::get('/cart/do-payment/{id}', 'Web\CartController@doPayment')->middleware('auth');
	Route::get('/cart/widget/refresh', 'Web\CartController@refresh');
	Route::get('/cart/delete/{rowid}', 'Web\CartController@delete');
	Route::get('/cart/coupon', 'Web\CartController@coupon');
	Route::get('/cart/finish', 'Web\CartController@finish')->middleware('auth');
	Route::get('/cart/error', 'Web\CartController@error')->middleware('auth');
	Route::post('/cart/add', 'Web\CartController@add');
	Route::post('/cart/store', 'Web\CartController@store')->middleware('auth');

	// Cart payment
	Route::get('/cart/paypal/{id}', 'Web\PaypalController@pay')->middleware('auth');
	Route::get('/cart/paypal/check/{id}', 'Web\PaypalController@check')->middleware('auth');

	// Order
	Route::resource('/order', 'Web\OrderController');

	// Contact
	Route::get('/'.LaravelLocalization::transRoute('routes.contact'), 'Web\ContactController@index')->name('contact');
	Route::post('/contact/send', 'Web\ContactController@send')->name('contact');

	// Sitemap route
	Route::get('/sitemap', 'Web\SitemapController@sitemap'); // html sitemap
	Route::get('/sitemap/{what}', 'Web\SitemapController@index'); // xml sitemap

	// Test route
	Route::get('/test', 'Web\TestController@index');		

	// Account route
	Route::get('/account/profile', 'Web\AccountController@edit')->name('account')->middleware('auth');
	Route::post('/account/profile', 'Web\AccountController@update')->name('account')->middleware('auth');
	Route::post('/account/profile/credential', 'Web\AccountController@credential')->name('account')->middleware('auth');	

	// Laravel Registration
	Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	Route::get('/sended/verification', '\App\Http\Controllers\Auth\RegisterController@sendverification');
	Route::get('/verify/{token}', '\App\Http\Controllers\Auth\RegisterController@verify');
	Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');	
	Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	Route::post('/register', 'Auth\RegisterController@register');
	Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
	Route::post('/login', 'Auth\LoginController@login');

});

Route::post('/review', 'Web\ReviewController@store');


// ******************************************************************************* //
// CMS Route
$cms_folder = '_lab/';

// Login
Route::get($cms_folder, 'Lab\LoginController@login');
Route::get($cms_folder.'logout', 'Lab\LoginController@logout');
Route::post($cms_folder, 'Lab\LoginController@authenticate');

Route::group(['middleware' => ['labenter']], function () use ($cms_folder) {
	// Dashbosrd
	Route::get($cms_folder.'dashboard', 'Lab\DashboardController@index');

	// Page
	Route::resource($cms_folder.'page', 'Lab\PageController');
	Route::get($cms_folder.'page/delete/img/{id}/{img}', 'Lab\PageController@deleteImg');
	Route::get($cms_folder.'page/change/flag/{id}/{field}', 'Lab\PageController@changeFlag');
	Route::put($cms_folder.'page/settings/{id}', 'Lab\PageController@settings');

	// News
	Route::resource($cms_folder.'news', 'Lab\NewsController');
	Route::get($cms_folder.'news/delete/img/{id}/{img}', 'Lab\NewsController@deleteImg');
	Route::get($cms_folder.'news/change/flag/{id}/{field}', 'Lab\NewsController@changeFlag');
	Route::put($cms_folder.'news/settings/{id}', 'Lab\NewsController@settings');

	// Servizi
	Route::resource($cms_folder.'service', 'Lab\ServiceController');
	Route::get($cms_folder.'service/delete/img/{id}/{img}', 'Lab\ServiceController@deleteImg');
	Route::get($cms_folder.'service/change/flag/{id}/{field}', 'Lab\ServiceController@changeFlag');
	Route::put($cms_folder.'service/settings/{id}', 'Lab\ServiceController@settings');

	// Staff
	Route::resource($cms_folder.'staff', 'Lab\StaffController');
	Route::get($cms_folder.'staff/delete/img/{id}/{img}', 'Lab\StaffController@deleteImg');
	Route::get($cms_folder.'staff/change/flag/{id}/{field}', 'Lab\StaffController@changeFlag');
	Route::put($cms_folder.'staff/registry/{id}', 'Lab\StaffController@registry');
	Route::put($cms_folder.'staff/settings/{id}', 'Lab\StaffController@settings');

	// Partners
	Route::resource($cms_folder.'partner', 'Lab\PartnerController');
	Route::get($cms_folder.'partner/delete/img/{id}/{img}', 'Lab\PartnerController@deleteImg');
	Route::get($cms_folder.'partner/change/flag/{id}/{field}', 'Lab\PartnerController@changeFlag');
	Route::put($cms_folder.'partner/registry/{id}', 'Lab\PartnerController@registry');
	Route::put($cms_folder.'partner/settings/{id}', 'Lab\PartnerController@settings');

	// Photogallery
	Route::resource($cms_folder.'photo', 'Lab\PhotogalleryController');
	Route::get($cms_folder.'photo/delete/img/{id}/{img}', 'Lab\PhotogalleryController@deleteImg');
	Route::get($cms_folder.'photo/change/flag/{id}/{field}', 'Lab\PhotogalleryController@changeFlag');
	Route::put($cms_folder.'photo/settings/{id}', 'Lab\PhotogalleryController@settings');

	// Videogallery
	Route::resource($cms_folder.'video', 'Lab\VideogalleryController');
	Route::get($cms_folder.'video/delete/img/{id}/{img}', 'Lab\VideogalleryController@deleteImg');
	Route::get($cms_folder.'video/change/flag/{id}/{field}', 'Lab\VideogalleryController@changeFlag');
	Route::put($cms_folder.'video/settings/{id}', 'Lab\VideogalleryController@settings');

	// Portfolio
	Route::resource($cms_folder.'portfolio', 'Lab\PortfolioController');
	Route::get($cms_folder.'portfolio/delete/img/{id}/{img}', 'Lab\PortfolioController@deleteImg');
	Route::get($cms_folder.'portfolio/change/flag/{id}/{field}', 'Lab\PortfolioController@changeFlag');
	Route::put($cms_folder.'portfolio/registry/{id}', 'Lab\PortfolioController@registry');
	Route::put($cms_folder.'portfolio/settings/{id}', 'Lab\PortfolioController@settings');

	// Category
	Route::resource($cms_folder.'category', 'Lab\CategoryController');
	Route::get($cms_folder.'category/delete/img/{id}/{img}', 'Lab\CategoryController@deleteImg');
	Route::get($cms_folder.'category/change/flag/{id}/{field}', 'Lab\CategoryController@changeFlag');
	Route::put($cms_folder.'category/settings/{id}', 'Lab\CategoryController@settings');

	// Subcategory
	Route::resource($cms_folder.'subcategory', 'Lab\SubcategoryController');
	Route::get($cms_folder.'subcategory/delete/img/{id}/{img}', 'Lab\SubcategoryController@deleteImg');
	Route::get($cms_folder.'subcategory/change/flag/{id}/{field}', 'Lab\SubcategoryController@changeFlag');
	Route::put($cms_folder.'subcategory/settings/{id}', 'Lab\SubcategoryController@settings');

	// Products
	Route::resource($cms_folder.'products', 'Lab\ProductController');
	Route::get($cms_folder.'products/delete/img/{id}/{img}', 'Lab\ProductController@deleteImg');
	Route::get($cms_folder.'products/change/flag/{id}/{field}', 'Lab\ProductController@changeFlag');
	Route::put($cms_folder.'products/settings/{id}', 'Lab\ProductController@settings');

	// User
	Route::resource($cms_folder.'user', 'Lab\UserController');
	Route::get($cms_folder.'user/change/flag/{id}/{field}', 'Lab\UserController@changeFlag');
	Route::put($cms_folder.'user/password/{id}', 'Lab\UserController@password');
	Route::post($cms_folder.'user/avatar/set', 'Lab\UserController@setAvatar');

	// Upload
	Route::get($cms_folder.'upload/{id}/edit/', 'Lab\UploadController@edit');
	Route::get($cms_folder.'upload/{id}/{folder}', 'Lab\UploadController@index');
	Route::get($cms_folder.'upload/change/flag/{id}/{field}', 'Lab\UploadController@changeFlag');
	Route::delete($cms_folder.'upload/{id}', 'Lab\UploadController@delete');
	Route::post($cms_folder.'upload/image/{id}/{table}/{folder}', 'Lab\UploadController@image');
	Route::post($cms_folder.'upload/multiupload/{id}/{folder}', 'Lab\UploadController@multiupload');
	Route::put($cms_folder.'upload/{id}', 'Lab\UploadController@update');
	Route::put($cms_folder.'upload/settings/{id}', 'Lab\UploadController@settings');

	// Product options
	Route::resource($cms_folder.'po', 'Lab\ProductoptionController');
	Route::get($cms_folder.'po/delete/img/{id}/{img}', 'Lab\ProductoptionController@deleteImg');
	Route::get($cms_folder.'po/change/flag/{id}/{field}', 'Lab\ProductoptionController@changeFlag');
	Route::put($cms_folder.'po/settings/{id}', 'Lab\ProductoptionController@settings');

	// Banner
	Route::resource($cms_folder.'banner', 'Lab\BannerController');
	Route::get($cms_folder.'banner/delete/img/{id}/{img}', 'Lab\BannerController@deleteImg');
	Route::get($cms_folder.'banner/change/flag/{id}/{field}', 'Lab\BannerController@changeFlag');
	Route::put($cms_folder.'banner/settings/{id}', 'Lab\BannerController@settings');

	// Review
	Route::resource($cms_folder.'review', 'Lab\ReviewController');
	Route::get($cms_folder.'review/delete/img/{id}/{img}', 'Lab\ReviewController@deleteImg');
	Route::get($cms_folder.'review/change/flag/{id}/{field}', 'Lab\ReviewController@changeFlag');
	Route::put($cms_folder.'review/settings/{id}', 'Lab\ReviewController@settings');

	// Coupon
	Route::resource($cms_folder.'coupon', 'Lab\CouponController');
	Route::get($cms_folder.'coupon/delete/img/{id}/{img}', 'Lab\CouponController@deleteImg');
	Route::get($cms_folder.'coupon/change/flag/{id}/{field}', 'Lab\CouponController@changeFlag');
	Route::put($cms_folder.'coupon/settings/{id}', 'Lab\CouponController@settings');

	// Shipment
	Route::resource($cms_folder.'shipment', 'Lab\ShipmentController');	
	Route::get($cms_folder.'shipment/change/flag/{id}/{field}', 'Lab\ShipmentController@changeFlag');
	Route::put($cms_folder.'shipment/settings/{id}', 'Lab\ShipmentController@settings');

	// Payment
	Route::resource($cms_folder.'payment', 'Lab\PaymentController');	
	Route::get($cms_folder.'payment/change/flag/{id}/{field}', 'Lab\PaymentController@changeFlag');
	Route::put($cms_folder.'payment/settings/{id}', 'Lab\PaymentController@settings');

	// Cart
	Route::resource($cms_folder.'cart', 'Lab\CartController');	

	// Business
	Route::resource($cms_folder.'business', 'Lab\BusinessController');
	Route::get($cms_folder.'business/change/flag/{id}/{field}', 'Lab\BusinessController@changeFlag');
	Route::put($cms_folder.'business/settings/{id}', 'Lab\BusinessController@settings');

	// Order
	Route::post($cms_folder.'order/{table}', 'Lab\OrderController@update');

	// Parameters
	Route::get($cms_folder.'parameter/{module2nd}', 'Lab\ParameterController@edit');
	Route::put($cms_folder.'parameter', 'Lab\ParameterController@update');

	// Office
	Route::resource($cms_folder.'office', 'Lab\OfficeController');
	Route::get($cms_folder.'office/delete/img/{id}/{img}', 'Lab\OfficeController@deleteImg');
	Route::get($cms_folder.'office/change/flag/{id}/{field}', 'Lab\OfficeController@changeFlag');
	Route::put($cms_folder.'office/settings/{id}', 'Lab\OfficeController@settings');

    // Cookie
    Route::resource($cms_folder.'cookie', 'Lab\CookieController');
    Route::get($cms_folder.'cookie/change/flag/{id}/{field}', 'Lab\CookieController@changeFlag');
    Route::put($cms_folder.'cookie/settings/{id}', 'Lab\CookieController@settings');


});