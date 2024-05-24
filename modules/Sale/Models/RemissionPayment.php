<?php

namespace Modules\Sale\Models;

use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\ModelTenant;

class RemissionPayment extends ModelTenant
{
    
    protected $table = 'co_remission_payments';

    protected $with = ['payment_method_type'];
    
    public $timestamps = false;

    protected $fillable = [
        'remission_id',
        'date_of_payment',
        'payment_method_type_id',
        'reference',
        'change',
        'payment',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
    ];

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }
 
    public function associated_record_payment()
    {
        return $this->belongsTo(Remission::class, 'remission_id');
    }

    public function getRowResource()
    {
        return [
            
            'id' => $this->id,
            'remission_id' => $this->remission_id,
            'date_of_payment' => $this->date_of_payment->format('d/m/Y'),
            'payment_method_type_id' => $this->payment_method_type_id,
            'payment_method_type_description' => $this->payment_method_type->description,
            'destination_description' => ($this->global_payment) ? $this->global_payment->destination_description:null,
            'reference' => $this->reference,
            'change' => $this->change,
            'payment' => $this->payment,
        ];
    }

}