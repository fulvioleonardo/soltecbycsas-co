<?php

$currentHostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "auth" middleware group. Now create something great!
|
*/

if ($currentHostname) {
    Route::domain($currentHostname->fqdn)->group(function() {
        Route::middleware('auth')->group(function() {
            Route::get('/', function () {
                return redirect()->route('client');
            });

            Route::get('/test_api_dian', 'Tenant\ConfigurationController@testApiDian');

//            Route::post('/company', 'Tenant\ConfigurationController@company');
            Route::post('/countries', 'Tenant\ConfigurationController@countries');
            Route::post('/departments/{country}', 'Tenant\ConfigurationController@departments');
            Route::post('/cities/{department}', 'Tenant\ConfigurationController@cities');
            Route::post('/concepts/{type_document}', 'Tenant\ConfigurationController@concepts');

            Route::get('/client', 'Tenant\HomeController@index')->name('client');

            Route::prefix('/client')->group(function() {
                Route::get('/configuration', 'Tenant\ConfigurationController@index')->name('tenant.configuration');
                Route::get('/configuration/documents', 'Tenant\ConfigurationController@document')->name('tenant.configuration.documents');
                Route::post('/configurationAll', 'Tenant\ConfigurationController@all');
//                Route::post('/configuration/storeServiceCompanie', 'Tenant\ConfigurationController@storeServiceCompanie');

                Route::get('/configuration/production', 'Tenant\ConfigurationController@production')->name('tenant.configuration.production');
                Route::post('/configuration/production/changeEnvironmentProduction', 'Tenant\ConfigurationController@changeEnvironmentProduction');
                Route::post('/configuration/production/queryTechnicalKey', 'Tenant\ConfigurationController@queryTechnicalKey');


//                Route::post('/configuration/storeServiceCompanieSoftware', 'Tenant\ConfigurationController@storeServiceSoftware');
//                Route::post('/configuration/storeServiceCompanieCertificate', 'Tenant\ConfigurationController@storeServiceCertificate');
//                Route::post('/configuration/storeServiceCompanieResolution', 'Tenant\ConfigurationController@storeServiceResolution');


                Route::get('/configurationAll', 'Tenant\ConfigurationController@all');
                Route::put('/configuration/company/{company}', 'Tenant\ConfigurationController@updateCompany');
                Route::post('/configuration/type_document/{type_document}', 'Tenant\ConfigurationController@updateTypeDocument');
                Route::post('/configuration/logo', 'Tenant\ConfigurationController@uploadLogo');

                Route::get('/taxes', 'Tenant\TaxController@index')->name('tenant.tax');
                Route::post('/taxesAll', 'Tenant\TaxController@all');
                Route::post('/taxes', 'Tenant\TaxController@store');
                Route::put('/taxes/{tax}', 'Tenant\TaxController@update');
                Route::delete('/taxes/{tax}', 'Tenant\TaxController@destroy');
                Route::get('/taxes/export', 'Tenant\TaxController@export');

                Route::get('/clients', 'Tenant\ClientController@index')->name('tenant.client');
                Route::post('/clientsAll', 'Tenant\ClientController@all');
                Route::post('/clients', 'Tenant\ClientController@store');
                Route::put('/clients/{client}', 'Tenant\ClientController@update');
                Route::delete('/clients/{client}', 'Tenant\ClientController@destroy');
//                Route::get('/clients/formatImport', 'Tenant\ClientController@formatImport');
//                Route::put('/clients/import/excel', 'Tenant\ClientController@import');
//                Route::get('/clients/export', 'Tenant\ClientController@export');

//                Route::get('/items', 'Tenant\ItemController@index')->name('tenant.item');
                Route::post('/itemsAll', 'Tenant\ItemController@all');
                Route::post('/items', 'Tenant\ItemController@store');
                Route::put('/items/{item}', 'Tenant\ItemController@update');
                Route::delete('/items/{item}', 'Tenant\ItemController@destroy');
//                Route::get('/items/formatImport', 'Tenant\ItemController@formatImport');
//                Route::put('/items/import/excel', 'Tenant\ItemController@import');
//                Route::get('/items/export', 'Tenant\ItemController@export')->name('tenant.items.export');

                Route::get('/documents', 'Tenant\DocumentController@index')->name('tenant.document');
                Route::get('/documents/create', 'Tenant\DocumentController@create')->name('tenant.document.form');
                Route::post('/documentsAll', 'Tenant\DocumentController@all');
                Route::post('/documents', 'Tenant\DocumentController@store');
                Route::post('/documents/note', 'Tenant\DocumentController@storeNote');

                Route::put('/documents/query/{document}', 'Tenant\DocumentController@query');
                Route::get('/documents/download/{type}/{document}', 'Tenant\DocumentController@download');
                Route::post('/documents/sendEmail/{document}/{client}', 'Tenant\DocumentController@sendEmail');
                Route::get('/documents/downloadxml/{id}', 'Tenant\DocumentController@downloadxml');


                Route::get('/quotations', 'Tenant\QuotationController@index')->name('tenant.quotation');
                Route::get('/quotations/create', 'Tenant\QuotationController@create')->name('tenant.quotation.form');
                Route::post('/quotations/createAll', 'Tenant\QuotationController@all');
                Route::post('/quotationsAll', 'Tenant\QuotationController@all');
                Route::post('/quotations', 'Tenant\QuotationController@store');
                Route::put('/quotations/{quotation}', 'Tenant\QuotationController@update');
                Route::post('/quotations/{quotation}/to-bill', 'Tenant\QuotationController@toBill');
                Route::get('/quotations/download/pdf/{quotation}', 'Tenant\QuotationController@download');

                Route::get('/report-taxes', 'Tenant\ReportTaxController@index')->name('tenant.report.taxes');
                Route::post('/report-taxes', 'Tenant\ReportTaxController@data');
                Route::get('/report-taxes/export', 'Tenant\ReportTaxController@export');
            });
        });
    });
}
