<?php


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

Route::get('/document-received/{cufe}/{state}', 'Api\Tenant\DocumentReceivedController@documentReceived')->name('document.received');

$currentHostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($currentHostname) {
    Route::domain($currentHostname->fqdn)->group(function() {
        Route::middleware('auth:api')->group(function() {
            Route::prefix('co-documents')->group(function() {
                Route::get('tables', 'Api\Tenant\DocumentController@tables');
                Route::post('', 'Api\Tenant\DocumentController@store');
                Route::get('items-search', 'Api\Tenant\DocumentController@searchItems');
                Route::get('documents-search', 'Api\Tenant\DocumentController@searchDocuments');
                Route::get('customer-search', 'Api\Tenant\DocumentController@searchCustomers');
            });
        });
    });
}
