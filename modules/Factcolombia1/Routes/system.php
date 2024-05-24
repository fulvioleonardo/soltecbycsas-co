<?php

$currentHostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

/*
|--------------------------------------------------------------------------
| Auth:Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "auth" middleware group. Now create something great!
|
*/

if ($currentHostname == null) {
    $app_url = config('tenant.prefix_url') == null ? config('tenant.app_url_base') : config('tenant.prefix_url').'.'.config('tenant.app_url_base');
    Route::domain($app_url)->group(function() {
        Route::middleware('auth:admin')->group(function() {
            Route::get('/', function () {
                return redirect()->route('companies');
            });

            Route::get('/companies', 'System\HomeController@index')->name('companies');
            Route::get('/companies/document', 'System\HomeController@indexDocument')->name('documents');


            Route::prefix('/system')->group(function() {
                Route::post('/company', 'System\CompanyController@store')->name('system.company');
                Route::post('/companyAll', 'System\CompanyController@all');
                Route::put('/company/{company}', 'System\CompanyController@update');
                Route::delete('/company/{company}', 'System\CompanyController@destroy');
                Route::get('/companyTables', 'System\CompanyController@tables')->name('system.tables');
                Route::get('/companyCascade', 'System\CompanyController@cascade')->name('system.cascade');
                Route::get('/company/informationDocument/{nit}', 'System\CompanyController@getInformationDocument')->name('system.information_document');
                Route::get('/company/informationDocument/{nit}/{desde}', 'System\CompanyController@getInformationDocument')->name('system.information_document');
                Route::get('/company/informationDocument/{nit}/{desde}/{hasta}', 'System\CompanyController@getInformationDocument')->name('system.information_document');
            });
        });
    });
}
