<?php

namespace App\Models\Tenant;

use Modules\Expense\Models\Expense;
use Modules\Expense\Models\ExpensePayment;

class CashDocument extends ModelTenant
{
    // protected $with = ['document'];

    public $timestamps = false;

    protected $fillable = [
        'cash_id',
        'document_id',
        'sale_note_id',
        // 'purchase_id',
        // 'expense_id',
        'expense_payment_id',
        'document_pos_id'
    ];


    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class);
    }

    public function expense_payment()
    {
        return $this->belongsTo(ExpensePayment::class);
    }

    public function document_pos()
    {
        return $this->belongsTo(DocumentPos::class);
    }

    /**
     * 
     * Retornar el modelo asociado dependiendo del campo foráneo que no tenga valor null
     *
     * Usado en:
     * Cash - Cierre de caja chica
     * 
     */
    // public function getMorphModel()
    // {
    //     if(!is_null($this->document_pos_id)) return $this->document_pos;
        
    //     if(!is_null($this->document_id)) return $this->document;

    //     if(!is_null($this->sale_note_id)) return $this->sale_note;
        
    //     if(!is_null($this->expense_payment_id)) return $this->expense_payment;
    // }


    // public function purchase()
    // {
    //     return $this->belongsTo(Purchase::class);
    // }

    // public function expense()
    // {
    //     return $this->belongsTo(Expense::class);
    // }

}
