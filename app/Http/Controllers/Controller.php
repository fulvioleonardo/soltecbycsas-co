<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Tenant\{
    PersonController,
    ItemController,
};
use Modules\Factcolombia1\Models\Tenant\{
    Tax,
    Currency,
};
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public const TAKE_FOR_SEARCH_ID = 1;
    public const MIN_ITEMS_IN_SELECT = 10;
    public const MAX_ITEMS_IN_SELECT = 100;
    
    /**
     * 
     * Obtener datos generales
     * Usar para ventas y compras
     * 
     * @param  string $table
     * @return array
     */
    public function generalTable($table)
    { 

        if ($table === 'suppliers') 
        {
            $persons = app(PersonController::class)->searchSuppliers(new Request());
            return $persons['suppliers'];
        }

        if ($table === 'taxes') 
        {
            return Tax::all()->transform(function($row) {
                return $row->getSearchRowResource();
            });
        }

        if ($table === 'items') 
        {
            $items = app(ItemController::class)->searchItems(new Request());
            return $items['items'];
        }

        if ($table === 'currencies') 
        {
            return Currency::get();
        }

        return [];
    }

        
    /**
     *
     * @param  bool $success
     * @param  string $message
     * @return array
     */
    public function getGeneralResponse($success, $message)
    {
        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    
    /**
     * 
     * Busqueda de registros por coincidencia o id, data inicial,  para componente
     * Los modelos deben implementar los scopes/funciones requeridas
     *
     * @param  string $model
     * @param  Request $request
     * @return array
     */
    public function generalSearchData($model, Request $request)
    {
        $id = $request->id ?? null;
        $input = $request->input ?? null;
        
        $records = $model::query();

        if($id)
        {
            $records->where('id', $id)->take(self::TAKE_FOR_SEARCH_ID);
        }
        else if($input)
        {
            $records->whereFilterSearchData($request)
                    ->optionalFiltersSearchData()
                    ->take(self::MAX_ITEMS_IN_SELECT);
        }
        else
        {
            $records->optionalFiltersSearchData()
                    ->take(self::MIN_ITEMS_IN_SELECT);
        }

        return $records->get()
                        ->transform(function($row) {
                            return $row->getSearchDataResource();
                        });
    }

}
