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
Route::get('callback.php', 'CallbackController@callback');

Route::get('user/login', 'UserController@showLoginForm')->name('login');
Route::post('user/login', 'UserController@doLogin');
Route::get('user/logout', 'UserController@doLogout');

Route::get('users', 'UserController@index');
Route::get('users/create', 'UserController@create');
Route::post('users/create', 'UserController@store');
Route::get('users/edit', 'UserController@edit');
Route::put('users/edit', 'UserController@update');
Route::delete('users/delete', 'UserController@destroy');
Route::get('users/ajax', 'UserController@getAjax');
Route::get('users/ajax/search', 'UserController@searchUser');

Route::get('/test', 'CooperationController@test');
Route::get('/', 'CooperationController@soonEndsList');
Route::get('cooperations', 'CooperationController@index');
Route::get('cooperations/soon-ends', 'CooperationController@soonEndsList');
Route::get('cooperations/create', 'CooperationController@create');
Route::post('cooperations/create', 'CooperationController@store');
Route::post('cooperations/create-temp', 'CooperationController@storeTemp');
Route::get('cooperations/display', 'CooperationController@display');
Route::get('cooperations/edit', 'CooperationController@edit');
Route::put('cooperations/edit', 'CooperationController@update');
Route::put('cooperations/edit-temp', 'CooperationController@updateTemp');
Route::delete('cooperations/delete', 'CooperationController@destroy');
Route::get('cooperations/download-document', 'CooperationController@downloadDocument');
Route::get('cooperations/ajax', 'CooperationController@getAjax');
Route::get('cooperations/ajaxCoopSoonEnds', 'CooperationController@getAjaxCoopSoonEnds');
Route::get('cooperations/ajaxCoopApprove', 'CooperationController@getAjaxCoopApprove');
Route::get('cooperations/ajax/is-having-relation', 'CooperationController@isHavingRelation');
Route::get('cooperations/ajax/get-document', 'CooperationController@getAjaxDocument');
Route::get('cooperations/ajax/cooperation-detail', 'CooperationController@getAjaxCoopDetail');
Route::get('cooperations/ajax/get-study-program', 'CooperationController@getStudyProgram');
Route::get('cooperations/approve-list', 'CooperationController@approveList');
Route::get('cooperations/approve', 'CooperationController@approve');
Route::post('cooperations/approve', 'CooperationController@approveStore');

Route::get('partners', 'PartnerController@index');
Route::get('partners/create', 'PartnerController@create');
Route::post('partners/create', 'PartnerController@store');
Route::get('partners/edit', 'PartnerController@edit');
Route::put('partners/edit', 'PartnerController@update');
Route::delete('partners/delete', 'PartnerController@destroy');
Route::get('partners/ajax', 'PartnerController@getAjax');

Route::get('areas_of_coop', 'AreaCoopController@index');
Route::post('areas_of_coop/create', 'AreaCoopController@store');
Route::post('areas_of_coop/edit', 'AreaCoopController@update');
Route::delete('areas_of_coop/delete', 'AreaCoopController@destroy');
Route::get('areas_of_coop/ajax', 'AreaCoopController@getAjax');

Route::get('units', 'UnitController@index');
Route::get('units/ajax', 'UnitController@getAjax');

Route::get('api/1.0/cooperations/search', 'ApiController@searchCoop');
Route::get('api/1.0/partner/search', 'ApiController@searchPartner');
Route::get('api/1.0/cooperations/{id}', 'ApiController@find');

Route::get('report', 'ReportController@index');
Route::post('report', 'ReportController@getReport');
Route::get('report/downloadExcel', 'ReportController@downloadExcel');
