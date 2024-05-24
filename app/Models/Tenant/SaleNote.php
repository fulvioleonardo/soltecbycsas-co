<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use Modules\Factcolombia1\Models\Tenant\{
    Currency,
};

class SaleNote extends ModelTenant
{
    protected $with = ['state_type', 'currency', 'items', 'payments'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',

        'prefix',

        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'exchange_rate_sale',
        
        'total',
        'filename',
        'total_canceled',
        'quotation_id',
        'order_note_id',
        'apply_concurrency',
        'type_period',
        'quantity_period',
        'automatic_date_of_issue',
        'enabled_concurrency',
        'series',
        'number',
        'paid',
        'license_plate',
        
        //co
        'currency_id',
        'sale',
        'taxes',
        'total_tax',
        'subtotal',
        'total_discount',

    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'automatic_date_of_issue' => 'date',
        'taxes' => 'object',
    ];


    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }

    public function getIdentifierAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }


    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    //legacy
    public function currency_type() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    
    public function getCurrencyTypeIdAttribute()
    {
        return $this->currency->name;
    }

    public function items()
    {
        return $this->hasMany(SaleNoteItem::class);
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    public function payments()
    {
        return $this->hasMany(SaleNotePayment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }


    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function getNumberFullAttribute()
    {
        $number_full = ($this->series && $this->number) ? $this->series.'-'.$this->number : $this->prefix.'-'.$this->id;

        return $number_full;
    }


    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    
    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01','03','05','07','13']);
    }

    public function scopeWhereNotChanged($query)
    {
        return $query->where('changed', false);
    }
    

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    
    public function scopeWhereCurrency($query, $currency_id)
    {
        return $query->where('currency_id', $currency_id);
    }

}
