<?php

namespace App\Models\Tenant;


use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\StatusOrder;


class Order extends ModelTenant
{
    use SoftDeletes;

    protected $fillable = [
        'external_id',
        'customer',
        'shipping_address',
        'items',
        'total',
        'reference_payment',
        'payment_method_type_id',
        'document_external_id',
        'number_document',
        'status_order_id'
    ];

    protected $casts = [
        'customer' => 'object',
        'items' => 'object'
    ];

    public function status_order()
    {
        return $this->belongsTo(StatusOrder::class);
    }

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function getPaymentMethodDescription()
    {
        return !is_null($this->payment_method_type_id) ? $this->payment_method_type->description : $this->reference_payment;
    }
}
