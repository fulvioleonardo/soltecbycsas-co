<?php

namespace Modules\Sale\Models;

use Modules\Factcolombia1\Models\Tenant\{
    TypeDocument,
    StateDocument,
    DetailDocument,
    Currency,
    PaymentForm,
    PaymentMethod,
};
use App\Models\Tenant\{
    StateType,
    User,
    Establishment,
    Quotation,
};
use App\Models\Tenant\ModelTenant;
use Modules\Inventory\Models\InventoryKardex;


class Remission extends ModelTenant
{

    protected $table = 'co_remissions';

    protected $fillable = [

        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'state_type_id',

        'date_of_issue',
        'time_of_issue',

        'customer_id',
        'customer',

        'prefix',
        'number',
        'currency_id',

        'date_expiration',
        'observation',

        'sale',
        'total_tax',
        'taxes',
        'total_discount',
        'subtotal',
        'total',

        'payment_form_id',
        'payment_method_id',
        'time_days_credit',
        'filename',
        'quotation_id',

    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'taxes' => 'object',
    ];


    public function getCurrencyTypeIdAttribute()
    {
        return $this->currency->name;
    }

    public function items() 
    {
        return $this->hasMany(RemissionItem::class);
    }

    public function currency() 
    {
        return $this->belongsTo(Currency::class);
    }

    public function getTaxesCollectAttribute() 
    {
        return collect($this->taxes);
    }

    public function payment_form()
    {
        return $this->belongsTo(PaymentForm::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->number;
    }

    public function getSeriesAttribute()
    {
        return $this->prefix;
    }

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function person() 
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function getCompanyAttribute()
    {
        return Company::first();
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    public function payments()
    {
        return $this->hasMany(RemissionPayment::class);
    }

    public function scopeWhereCurrency($query, $currency_id)
    {
        return $query->where('currency_id', $currency_id);
    }

    public function getRowResource()
    {
        return [
            
            'id' => $this->id,
            'user_id' => $this->user_id,
            'external_id' => $this->external_id,
            'establishment_id' => $this->establishment_id,
            'establishment' => $this->establishment,
            'state_type_id' => $this->state_type_id,
            'state_type_description' => $this->state_type->description,
            'number_full' => $this->number_full,
            'quotation_number_full' => optional($this->quotation)->number_full,

            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'time_of_issue' => $this->time_of_issue,

            'customer_id' => $this->customer_id,
            'customer_number' => $this->customer->number,
            'customer_name' => $this->customer->name,
            'user_name' => $this->user->name,

            'prefix' => $this->prefix,
            'number' => $this->number,
            'currency_id' => $this->currency_id,
            'currency_name' => $this->currency->name,

            'date_expiration' => $this->date_expiration,
            'observation' => $this->observation,

            'sale' => $this->sale,
            'total_tax' => $this->total_tax,
            'taxes' => $this->taxes,
            'total_discount' => $this->total_discount,
            'subtotal' => $this->subtotal,
            'total' => $this->total,

            'payment_form_id' => $this->payment_form_id,
            'payment_method_id' => $this->payment_method_id,
            'time_days_credit' => $this->time_days_credit,
            'filename' => $this->filename,

        ];
    }

}
