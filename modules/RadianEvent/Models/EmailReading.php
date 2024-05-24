<?php

namespace Modules\RadianEvent\Models; 

use App\Models\Tenant\{
    ModelTenant,
};
use Illuminate\Database\Eloquent\Builder;


class EmailReading extends ModelTenant
{

    protected $table = 'co_email_reading';

    protected $fillable = [
        
        'email_user',
        'start_date',
        'start_time',

        'end_date',
        'end_time',

        'success',
        'errors',
        'imap_server',

        'search_end_date',
        'search_start_date',

    ];


    public function details()
    {
        return $this->hasMany(EmailReadingDetail::class, 'co_email_reading_id');
    }


    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'email_user' => $this->email_user,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
    
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
    
            'success' => $this->success,
            'errors' => $this->errors,
            'imap_server' => $this->imap_server,

            'search_start_date' => $this->search_start_date,
            'search_end_date' => $this->search_end_date,

        ];
    }

}
