<?php

use Illuminate\Http\Request;

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

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

// //registrars
// Route::post('fetchRegistrars','RegistrarController@fetch');
// Route::post('addRegistrar','RegistrarController@store');
// Route::put('updateRegistrar/{id}','RegistrarController@update');
// Route::delete('removeRegistrar/{id}','RegistrarController@destroy');
// Route::post('filterRegistrars','RegistrarController@filter');

// //domains
// Route::post('fetchDomains','DomainController@fetch');
// Route::post('addDomain','DomainController@store');
// Route::put('updateDomain/{id}','DomainController@update');
// Route::delete('removeDomain/{id}','DomainController@destroy');
// Route::post('filterDomains','DomainController@filter');

// //groups
// Route::post('addGroup','GroupController@store');
// Route::post('filterGroups','GroupController@filter');
// Route::put('updateGroup/{id}','GroupController@update');

// //providers
// Route::post('storeProvider','ProviderController@store');
// Route::post('filterProviders','ProviderController@filter');
// Route::put('updateProvider/{id}','ProviderController@update');
// Route::delete('deleteProvider/{id}','ProviderController@destroy');

// //servers
// Route::post('storeServer','ServerController@store');
// Route::post('getServers','ServerController@fetch');
// Route::put('updateServer/{id}','ServerController@update');

// //users
// Route::post('storeUser','UserController@store');
// Route::post('filterUsers','UserController@filter');
// Route::put('updateUser/{id}','UserController@update');
// Route::delete('deleteUser/{id}','UserController@destroy');

// //requests
// Route::post('storeRequest','RequestController@store');
// Route::post('listRequests','RequestController@list');
// Route::post('filterRequests','RequestController@filter');
// Route::post('filterByStatus','RequestController@filterByStatus');
// Route::post('filterByType','RequestController@filterByType');
// Route::post('filterByGroup','RequestController@filterByGroup');
// Route::post('filterByPriority','RequestController@filterByPriority');

// //status
// Route::post('storeStatus/{id}','StatusController@store');
// Route::post('addToServer/{id}','StatusController@addToServer');

// //linkRS
// Route::post('linkRS/save','LinkRSController@save');
// Route::post('linkRS/paginate','LinkRSController@paginate');

// //payments
// Route::post('storePayment','PaymentController@store');
// Route::post('filterPayments','PaymentController@filter');
// Route::put('updatePayment/{id}','PaymentController@update');
// Route::delete('deletePayment/{id}','PaymentController@destroy');

// //Ip
// Route::post('storeIp','IpController@store');
// Route::post('filterIps','IpController@filter');
// Route::put('updateIp/{id}','IpController@update');
// Route::delete('deleteIp/{id}','IpController@destroy');

