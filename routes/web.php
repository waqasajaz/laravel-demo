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

Route::get('/admin', 'Admin\LoginController@index');
Route::post('/admin/logincheck', 'Admin\LoginController@authenticate');
Route::get('/admin/logout', 'Admin\LoginController@logout');
Route::get('/admin/dashboard', 'Admin\DashboardController@index');
Route::get('/admin/chats', 'Admin\ChatController@index');
Route::get('/admin/chats/{type}', 'Admin\ChatController@index');
Route::post('/admin/chat/message', 'Admin\ChatController@saveMessage');
Route::post('/admin/chat/update', 'Admin\ChatController@updateChat');
Route::post('/admin/chats/update', 'Admin\ChatController@updateChatWindows');
Route::get('/admin/exportoffer/{type}/{status}', 'Admin\DashboardController@exportOffer');
Route::get('/admin/exportoffer/{type}/{status}/{asset_type}', 'Admin\DashboardController@exportOffer');
Route::get('/admin/agent', 'Admin\AgentsController@index');
Route::get('/admin/agent/create', 'Admin\AgentsController@create');
Route::post('/admin/agent/create', 'Admin\AgentsController@save');
Route::get('/admin/agent/edit/{id}', 'Admin\AgentsController@edit');
Route::get('/admin/agent/delete/{id}', 'Admin\AgentsController@delete');
Route::get('/admin/assets/published', 'Admin\AssetsController@published');
Route::get('/admin/assets/published/{type}', 'Admin\AssetsController@published');
Route::get('/admin/assets', 'Admin\AssetsController@index');
Route::get('/admin/assets/{type}', 'Admin\AssetsController@index');
Route::post('/admin/assign/agent', 'Admin\AssetsController@allocateAgent');
Route::get('/admin/my-assets', 'Admin\AssetsController@myAssets');
Route::get('/admin/my-assets/{type}', 'Admin\AssetsController@myAssets');

Route::get('/admin/offers-completed', 'Admin\DashboardController@offers_completed');
Route::get('/admin/offers-completed/{type}', 'Admin\DashboardController@offers_completed');

Route::any('/admin/get-offers', 'Admin\DashboardController@get_offers');

Route::get('/admin/offers-accepted', 'Admin\DashboardController@offers_accepted');
Route::get('/admin/offers-accepted/{type}', 'Admin\DashboardController@offers_accepted');
Route::get('/admin/change-offer-status/{offer_id}/{status}/{type}', 'Admin\DashboardController@changeOfferStatus');
Route::get('/admin/offer/delete/{offer_id}/{type}', 'Admin\DashboardController@deleteOffer');
Route::get('/admin/logs', 'Admin\ActivityController@index')->name('activity');
Route::any('/logs/authentication', 'Admin\ActivityController@authenticate')->name('logauthenticate');
Route::any('/admin/resetpassword', 'Admin\AdminController@resetpassword');
Route::any('/admin/settings', 'Admin\AdminController@settings')->name('settings');
Route::any('/logs/export/{type?}', 'Admin\ActivityController@export')->name('activity-export');
Route::any('/admin/resetlogpassword', 'Admin\AdminController@resetlogpassword');
Route::any('/admin/companies/', 'Admin\AdminController@companies')->name('companies');
Route::any('/admin/company/properties/{company}/{type?}', 'Admin\AdminController@company_properties')->name('company_properties');
Route::any('/admin/property/verify/{id}', 'Admin\AssetsController@unverify')->name('verify-property');
Route::any('/admin/property/unverify/{id}', 'Admin\AssetsController@verify')->name('unverify-property');
Route::any('agent/assign/company', 'Admin\AdminController@company_assign_agent')->name('company_assign_agent');
Route::any('/admin/import/company', 'Admin\AdminController@importcompany')->name('importcompany');
Route::get('/admin/assets/unpublished', 'Admin\AssetsController@unpublished');
Route::get('/admin/assets/unpublished/{type}', 'Admin\AssetsController@unpublished');
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@getlogout']);
Route::post('/admin/new/offers', 'Admin\DashboardController@getNewOffers')->name('new_offers');
Route::get('admin/read/notification/{notification_id}', 'Admin\AssetsController@readNotification');
Route::get('/admin/unread/notification/count', 'Admin\DashboardController@unreadNotificationCount');
Route::get('/admin/unread/notifications', 'Admin\DashboardController@unreadNotifications');

Route::any('/property/{property}/gallery', 'Admin\AssetsController@gallery');

Route::get('/admin/tour/{visited}', 'Admin\TourController@index');
Route::post('/admin/tour/visited_date_update', 'Admin\TourController@VisitedDateUpdate');
Route::any('/admin/get-tour-requested', 'Admin\TourController@get_requested_tour');
Route::any('/admin/tour/visited/{id}', 'Admin\TourController@visited')->name('visited-tour');

Route::any('/property/{property}/images', 'CI_ModelController@getimages');
Route::any('/download/{id}/{type}/{filename}', 'CI_ModelController@download_file');
Route::any('/backup/{directory}', 'CI_ModelController@backup');

Route::any('/average_price/{id?}', 'PropertyController@get_average_price');
Route::any('/property-sold/{id?}', 'Admin\DashboardController@sold_property')->name("property-sold");

Route::any('assets/sold-assets/{type?}', 'Admin\AssetsController@soldassets');
Route::any('schedule_visit/{id?}', 'Admin\AssetsController@schedule_visit');
Route::any('schedule', 'Admin\AssetsController@schedule');

Route::any('property/edit_image', 'Admin\AssetsController@edit_image');

Route::any('support', 'LsupportController@index');

Auth::routes();
Route::middleware(['auth'])->group(function () {

	Route::get('/', 'HomeController@index')->name('home');
	Route::get('/properties/{type?}/{hood_id?}', 'HomeController@listing')->name('properties');
	Route::get('/property/detail/{id?}', 'HomeController@property')->name('property');
	Route::get('/page/{page?}', 'HomeController@page')->name('page');
	Route::any('/image/delete', 'CI_ModelController@delete_image')->name('delete-image');

	Route::any('/retailer/properties', array('as' => 'rent-properties', 'uses' => 'HomeController@properties'));
	Route::any('/search/category', 'Yelp_helperController@search')->name('token');
	Route::any('/search_add_collection', 'CollectionsController@search_add_collection')->name('search_add_collection');
	Route::any('/savein/collection', 'CollectionsController@savein_collection')->name('savein_collection');
	Route::any('/searchresult', 'HomeController@searchresult')->name('searchresult');

	Route::any('/searchin', 'HomeController@searchin')->name('searchin');
	Route::any('/searchfromhome', 'HomeController@search')->name('search');

	Route::any('property/add', 'PropertyController@add')->name('add_property');
	Route::post('property/submit', 'PropertyController@submit')->name('submit_property');
	Route::any('property/publish', 'PropertyController@publish')->name('publish-property');
	Route::any('property/get/{type?}/{id?}', 'PropertyController@get_property')->name('get-property');
	Route::any('property/delete', 'PropertyController@delete')->name('delete-property');
	Route::any('property/edit/{id}', 'PropertyController@edit')->name('edit-property');
	Route::any('property/update', 'PropertyController@update')->name('update-property');
	Route::any('/property/nearby', 'PropertyController@nearbyproperty');
	Route::any('/property/import', 'PropertyController@import')->name('import_property');
	Route::any('/property/deleteimage', 'PropertyController@deleteimage')->name('deleteimage');

	Route::any('/demoexcel', 'PropertyController@demoexcel')->name("demoexcel");

	Route::any('collection/rename', 'CollectionsController@rename')->name('collection-rename');
	Route::any('collection/delete', 'CollectionsController@delete')->name('collection-delete');
	Route::any('collection/remove_from_collection', 'CollectionsController@remove_from_collection')->name('collection-remove_from_collection');
	Route::any('collection/update_collection_comment', 'CollectionsController@update_collection_comment')->name('collection-update_collection_comment');
	Route::any('collection/get/{type?}/{id?}', 'CollectionsController@get_collection')->name('get_collection');

	Route::any('collections/{name}/{id?}', 'CollectionsController@collection')->name('my-collection');
	Route::any('get_collection_property', 'CollectionsController@get_collection_property')->name('get-collection');
	Route::any('get_collection_data', 'CollectionsController@get_collection_data')->name('get-collection-data');

	Route::any('my/properties/{name}', 'User\UserController@my_property')->name('my-property');
	Route::any('/get-my-properties', 'User\UserController@get_my_property')->name('get_my_property');
	Route::get('my/offers/{name}', 'User\UserController@myOffers');
	Route::any('/get-my-offers', 'User\UserController@get_my_offers')->name('get_my_offers');
	Route::any('/offer/cancel', 'User\UserController@cancel_offer')->name('cancel_offer');



	Route::post('/visitor/feedback', 'HomeController@visitor_feedback')->name('visitor-feedback');
	
	Route::get('/area', 'HomeController@area')->name('area');
	Route::get('/state/{id}', 'HomeController@state')->name('state');
	Route::get('/neighbour/{id}', 'HomeController@neighbour')->name('neighbour');
	Route::any('/hoodsbydeistrict', 'CI_ModelController@gethoods_by_district')->name('gethoods_by_district');
	Route::post('/sell_rent_price', 'HomeController@get_sell_rent_price')->name('get-sell-rent-price');
	Route::get('s3-image-upload','S3ImageController@imageUpload');
	Route::post('s3-image-upload','S3ImageController@imageUploadPost');
	
    Route::get('/create-offer/{property_id}', 'CreateOfferController@index')->name('create-offer-page');
    Route::post('/save/offer', 'CreateOfferController@saveOffer')->name('add-offer-page');
    Route::post('/save/offer/dpamount', 'CreateOfferController@saveOfferDpamount')->name('add-offer-page');
    Route::get('/download/contract/selling', 'CreateOfferController@downloadContractSelling');
    Route::get('/download/contract/rental', 'CreateOfferController@downloadContractRental');
    Route::post('/offer/chat/message', 'CreateOfferController@saveMessage');
    Route::post('/offer/chat/update', 'CreateOfferController@updateChat');

    Route::get('cancel/offer/{id}', 'CreateOfferController@cancelOffer');
    Route::get('modify/offer/{id}', 'CreateOfferController@modifyOffer');
    Route::post('/offer/send/query', 'CreateOfferController@sendQuery');
    Route::post('/property/switch/collection', 'CollectionsController@switchCollection');
    Route::post('/collection/property/chat', 'PropertyChatController@propertyChat');
    Route::post('/collection/property/chat/send/message', 'PropertyChatController@saveMessage');
    Route::post('/collection/property/chat/update', 'PropertyChatController@updateChat');
    Route::post('property/chat/remove/user', 'PropertyChatController@removeUser');
	
	// ajax LOG routes
		Route::any('/log/morgatelog', 'LogsController@morgatelog')->name("mortagelog");
		Route::any('/log/tablogs', 'LogsController@tablogs')->name("tablog");

    Route::get('/property/evolution/{id}', 'User\UserController@property_evolution')->name('property');
	Route::post('/request/help/addproperty', 'PropertyController@add_help')->name("add-property-help");
	
	Route::post('/user/offer_chart_data', 'User\UserController@offer_chart_data')->name("offer_chart_data");
	Route::any('user/wishlistarray', 'CollectionsController@wishlistarray')->name("wishlistarray");
	Route::post('/user/week_offer_chart_data', 'User\UserController@week_offer_chart_data')->name("week_offer_chart_data");

	Route::post('/request_contact', 'HomeController@request_contact')->name("request_contact");

	Route::any('property/compare', 'PropertyController@compare_property');

});

//Invitation Routes
Route::post('/property/send/invitation', 'InvitationController@sendInvitation');
Route::get('/property/invitation/{token}', 'InvitationController@propertyInvitation');
