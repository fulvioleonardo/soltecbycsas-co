<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use Modules\Sale\Models\SaleOpportunity;
use Modules\Sale\Models\QuotationPayment;
use Modules\Sale\Models\Contract;
use Modules\Factcolombia1\Models\Tenant\{
    Currency,
};
use Modules\Sale\Models\{
    Remission
};

class Quotation extends ModelTenant
{
    protected $with = ['soap_type', 'state_type', 'currency', 'items', 'payments'];

    protected $fillable = [
        'id',
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'payment_method_type_id',

        'prefix',

        'date_of_issue',
        'time_of_issue',
        'date_of_due',
        'delivery_date',
        'customer_id',
        'customer',
        'exchange_rate_sale',
        'total',
        'filename',
        'shipping_address',
        'description',
        'sale_opportunity_id',
        'changed',
        'account_number',
        'terms_condition',

        //co
        'currency_id',
        'sale',
        'taxes',
        'total_tax',
        'total_discount',
        'subtotal',

    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_due' => 'date',
        'delivery_date' => 'date',
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
        return $this->hasMany(QuotationItem::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function sale_notes()
    {
        return $this->hasMany(SaleNote::class);
    }

    public function remission()
    {
        return $this->hasOne(Remission::class);
    }

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function sale_opportunity()
    {
        return $this->belongsTo(SaleOpportunity::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01']);
    }

    public function payments()
    {
        return $this->hasMany(QuotationPayment::class);
    }

    public function scopeWhereNotChanged($query)
    {
        return $query->where('changed', false);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
