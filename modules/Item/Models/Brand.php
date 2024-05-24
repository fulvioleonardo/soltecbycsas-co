<?php

namespace Modules\Item\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;

class Brand extends ModelTenant
{

    protected $fillable = [ 
        'name',
    ];
 
    public function items()
    {
        return $this->hasMany(Item::class);
    }
 

    /**
     *
     * @return array
     */
    public function getSearchDataResource()
    { 
        return [
            'id' => $this->id,
            'search_name' => $this->name,
        ];
    }
    

    /**
     * 
     * Filtro para busqueda
     *
     * @param  Builder $query
     * @param  Request $request
     * @return Builder
     */
    public function scopeWhereFilterSearchData($query, $request)
    {
        $query->generalWhereLikeColumn('name', $request->input);

        return $query->orderBy('name');
    }
    
    
    /**
     * 
     * Filtros opcionales para componente de busqueda
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeOptionalFiltersSearchData($query)
    {
        return $query;
    }

}