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

Route::middleware(['auth'])->group(function(){

	Route::resource('/', 'HomeController');

	Route::get('inventory/supply/rsmi','SupplyInventoryController@rsmi');
	Route::get('inventory/supply/rsmi/{month}','SupplyInventoryController@rsmiPerMonth');
	Route::get('inventory/supply/rsmi/total/bystocknumber/{month}','SupplyInventoryController@rsmiByStockNumber');
	Route::get('logout', 'Auth\LoginController@logout');

	Route::resource('inventory/supply','SupplyInventoryController');

	Route::get('get/supply/{supply}/balance','SupplyInventoryController@getSupplyWithRemainingBalance');

	Route::get('get/inventory/supply/stocknumber/all','StockCardController@getAllStockNumber');

	Route::get('get/inventory/supply/stocknumber','StockCardController@getSupplyStockNumber');

	Route::get('get/office/code/all','OfficeController@getAllCodes');

	Route::get('get/supply/inventory/stockcard/months/all','SupplyInventoryController@getAllMonths');

	Route::get('get/office/code','OfficeController@getOfficeCode');

	Route::resource('maintenance/supply','SupplyController');

	Route::resource('maintenance/office','OfficeController');

	Route::resource('maintenance/item/type','ItemTypeController');

	Route::post('get/supplyledger/checkifexisting',[
		'as' => 'supplyledger.checkifexisting',
		'uses' => 'SupplyLedgerController@checkIfExisting'
	]);

	Route::post('get/supplyledger/copy',[
		'as' => 'supplyledger.copy',
		'uses' => 'SupplyLedgerController@copy'
	]);

	Route::get('settings',['as'=>'settings.edit','uses'=>'SessionsController@edit']);

	Route::post('settings',['as'=>'settings.update','uses'=>'SessionsController@update']);

});

Route::middleware(['auth','amo'])->group(function(){

	Route::get('inventory/supply/stockcard/batch/form/accept',[
			'as' => 'supply.stockcard.batch.accept.form',
			'uses' => 'StockCardController@batchAcceptForm'
	]);

	Route::get('inventory/supply/stockcard/batch/form/release',[
			'as' => 'supply.stockcard.batch.release.form',
			'uses' => 'StockCardController@batchReleaseForm'
	]);

	Route::post('inventory/supply/stockcard/batch/accept',[
			'as' => 'supply.stockcard.batch.accept',
			'uses' => 'StockCardController@batchAccept'
	]);

	Route::post('inventory/supply/stockcard/batch/release',[
			'as' => 'supply.stockcard.batch.release',
			'uses' => 'StockCardController@batchRelease'
	]);

	Route::get('inventory/supply/{id}/stockcard/release','StockCardController@releaseForm');

	Route::resource('inventory/supply.stockcard','StockCardController');


});

Route::middleware(['auth','accounting'])->group(function(){

	Route::get('inventory/supply/{id}/supplyledger/release','SupplyLedgerController@releaseForm');

	Route::resource('inventory/supply.supplyledger','SupplyLedgerController');


});

Route::middleware(['auth','admin'])->group(function(){
	Route::get('audittrail','AuditTrailController@index');
	Route::resource('account','AccountsController');
	Route::post('account/password/reset','AccountsController@resetPassword');

	Route::put('account/access/update',[
			'as' => 'account.accesslevel.update',
			'uses' => 'AccountsController@changeAccessLevel'
 		]);
});

Auth::routes();
