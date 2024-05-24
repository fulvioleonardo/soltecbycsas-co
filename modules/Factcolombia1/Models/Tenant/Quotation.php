<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_quotations';
    
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'client' => 'object',
        'taxes' => 'object'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['state_quote_id', 'client_id', 'client', 'currency_id', 'observation', 'sale', 'total_discount', 'taxes', 'total_tax', 'subtotal', 'total', 'version_ubl_id', 'ambient_id'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
    * Get the state quote belongs to
    */
    public function state_quote() {
        return $this->belongsTo(StateQuote::class);
    }
    
    /**
    * Get the currency belongs to
    */
    public function currency() {
        return $this->belongsTo(Currency::class);
    }
    
    /**
    * Get the detail quotations has many
    */
    public function detail_quotations() {
        return $this->hasMany(DetailQuotation::class);
    }
}
