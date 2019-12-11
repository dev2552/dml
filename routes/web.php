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
Route::get('/', 'HomeController@index')->name('index');

Route::get('lock','UserController@lock')->name('lock')->middleware('auth');
Route::post('unlock','UserController@unlock')->name('unlock')->middleware('auth');


Route::middleware(['auth','lock'])->group(function(){

	Route::get('/home', function(){return view('home');})->name('home');
	
	Route::get('registrars','RegistrarController@index')->name('registrars.index');
	Route::get('domains','DomainController@index')->name('domains.index');
	Route::get('servers','ServerController@index')->name('servers.index');
	Route::get('providers','ProviderController@index')->name('providers.index');
	Route::get('ips','IpController@index')->name('ips.index');
	Route::get('requests','RequestController@index')->name('requests.index');
	Route::get('payments','PaymentController@index')->name('payments.index');
	Route::get('subDomains','SubDomainController@index')->name('subDomains.index');
	Route::get('users','UserController@index')->name('users.index');
	Route::put('users/{user}','UserController@updateProfile')->name('users.update');
	Route::get('groups','GroupController@index')->name('groups.index');
	Route::get('settings','UserController@settings')->name('settings');
	Route::get('profile','UserController@profile')->name('profile');
	
	Route::get('logout','UserController@logout')->name('logout');
	Route::get('linkRS','ServerController@linkRS')->name('linkRS');

	Route::get('configureDNS','DNSController@configureDNS')->name('configureDNS');
	Route::post('api/clearDomain/{id}','DNSController@clearDomain');
	Route::post('api/updateDNS','DNSController@updateDNS');
	Route::get('api/getDomainsList/{id}','DNSController@getDomainsList');
	Route::post('api/setIpList','DNSController@setIpList');
	
	
	Route::get('api/getUnreadNotificationsCount','NotificationController@getUnreadNotificationsCount');
	Route::get('markAsRead','NotificationController@markAsRead')->name('markAsRead');
	Route::get('notifications/search','NotificationController@search')->name('notifications.search');
	Route::resource('notifications','NotificationController');

	//registrars
	Route::post('api/fetchRegistrars','RegistrarController@fetch');
	Route::post('api/addRegistrar','RegistrarController@store');
	Route::put('api/updateRegistrar/{id}','RegistrarController@update');
	Route::delete('api/removeRegistrar/{id}','RegistrarController@destroy');
	Route::post('api/filterRegistrars','RegistrarController@filter');
	Route::get('api/getRegistrars','RegistrarController@getRegistrars');

	//domains
	Route::post('api/fetchDomains','DomainController@fetch');
	Route::post('api/addDomain','DomainController@store');
	Route::post('api/addOneDomain','DomainController@add');
	Route::put('api/updateDomain/{id}','DomainController@update');
	Route::delete('api/removeDomain/{id}','DomainController@destroy');
	Route::post('api/filterDomains','DomainController@filter');
	Route::post('api/exportDomains','DomainController@export');
	Route::get('api/getNewDomains','DomainController@getNewDomains');
	Route::get('api/getDomains','DomainController@getDomains');

	//groups
	Route::post('api/addGroup','GroupController@store');
	Route::post('api/filterGroups','GroupController@filter');
	Route::put('api/updateGroup/{id}','GroupController@update');
	Route::delete('api/deleteGroup/{id}','GroupController@destroy');
	Route::get('api/getGroups','GroupController@getAll');

	//providers
	Route::post('api/storeProvider','ProviderController@store');
	Route::post('api/filterProviders','ProviderController@filter');
	Route::put('api/updateProvider/{id}','ProviderController@update');
	Route::delete('api/deleteProvider/{id}','ProviderController@destroy');
	Route::get('api/exportProviders','ProviderController@export');
	Route::get('api/getProviders','ProviderController@getAll');
	Route::get('api/providers/{id}','ProviderController@show');

	//servers
	Route::post('api/storeServer','ServerController@store');
	Route::post('api/getServers','ServerController@fetch');
	Route::put('api/updateServer/{id}','ServerController@update');
	Route::post('api/exportServers','ServerController@export');
	Route::delete('api/deleteServer/{id}','ServerController@delete');
	Route::get('api/getAllServers','ServerController@getAllServers');
	Route::post('api/renewalServers','ServerController@renewalServers');
	Route::post('api/toggleStatus','ServerController@toggleStatus');
	Route::post('api/toggleGroup','ServerController@toggleGroup');
	Route::get('api/getAllServers','ServerController@getAllServers');
	Route::post('api/serversToReturn','ServerController@serversToReturn');

	//users
	Route::post('api/storeUser','UserController@store');
	Route::post('api/filterUsers','UserController@filter');
	Route::put('api/updateUser/{id}','UserController@update');
	Route::delete('api/deleteUser/{id}','UserController@destroy');
	Route::get('api/getUsers','UserController@getUsers');

	//requests
	Route::post('api/storeRequest','RequestController@store');
	Route::post('api/listRequests','RequestController@list');
	Route::post('api/filterRequests','RequestController@filter');
	Route::post('api/filterByStatus','RequestController@filterByStatus');
	Route::post('api/filterByType','RequestController@filterByType');
	Route::post('api/filterByGroup','RequestController@filterByGroup');
	Route::post('api/filterByPriority','RequestController@filterByPriority');
	Route::get('api/getRequests','RequestController@getRequests');


	//status
	Route::post('api/storeStatus/{id}','StatusController@store');
	Route::post('api/addToServer/{id}','StatusController@addToServer');

	//linkRS
	Route::post('api/linkRS/save','LinkRSController@save');
	Route::post('api/linkRS/paginate','LinkRSController@paginate');

	//payments
	Route::post('api/storePayment','PaymentController@store');
	Route::post('api/filterPayments','PaymentController@filter');
	Route::put('api/updatePayment/{id}','PaymentController@update');
	Route::delete('api/deletePayment/{id}','PaymentController@destroy');
	Route::post('api/exportPayments','PaymentController@export');

	//Ips
	Route::post('api/storeIp','IpController@store');
	Route::post('api/filterIps','IpController@filter');
	Route::put('api/updateIp/{id}','IpController@update');
	Route::delete('api/deleteIp/{id}','IpController@destroy');
	Route::post('api/exportIps','IpController@export');
	Route::post('api/storeIps','IpController@storeMany');
	Route::get('api/getIps','IpController@getIps');

	//SubDomains
	Route::post('api/filterSubDomains','SubDomainController@filter');
	Route::post('api/listIps','SubDomainController@listIps');
	Route::put('api/updateSubDomain/{id}','SubDomainController@update');


	//home
	Route::get('api/servers/return','HomeController@serversToReturn');
	Route::get('api/servers/production/issue','HomeController@serversInProductionWithIssue');
	Route::get('api/servers/installing','HomeController@serversInstalling');
	Route::get('api/servers/expiration','HomeController@serversExpiration');
	Route::post('api/requests','HomeController@requests');
	Route::get('api/servers','HomeController@servers');
	Route::get('api/payments','HomeController@payments');

	//Roles
	Route::get('api/getRoles','RoleController@index');

	

	

});




