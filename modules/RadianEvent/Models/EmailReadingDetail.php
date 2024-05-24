<?php

namespace Modules\RadianEvent\Models; 

use App\Models\Tenant\{
    ModelTenant,
};
use Illuminate\Database\Eloquent\Builder;


class EmailReadingDetail extends ModelTenant
{

    protected $table = 'co_email_reading_details';
    public $timestamps = false;

    protected $fillable = [

        'co_email_reading_id',
        'email_user',
        'email_id',
        'subject',
        'from_host',
        'from_name',
        'from_address',
        'sender_host',
        'api_validation_response',
        
    ];


    public function email_reading()
    {
        return $this->belongsTo(EmailReading::class, 'co_email_reading_id');
    }
    
    public function received_document()
    {
        return $this->hasOne(ReceivedDocument::class, 'co_email_reading_detail_id');
    }


    public function getApiValidationResponseAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setApiValidationResponseAttribute($value)
    {
        $this->attributes['api_validation_response'] = (is_null($value))?null:json_encode($value);
    }


    public function getRowResource()
    {
        $message_api = null;

        if($this->api_validation_response)
        {
            if($this->api_validation_response->success)
            {
                $message_api = "Correo procesado correctamente: {$this->api_validation_response->response_api->xml}";
            }
            else
            {
                $message_api = "Error al procesar correo: {$this->api_validation_response->response_api->message}";
            }
        }

        return [
            'id' => $this->id,
            'co_email_reading_id' => $this->co_email_reading_id,
            'email_user' => $this->email_user,
            'email_id' => $this->email_id,
            'subject' => $this->subject,
            'from_host' => $this->from_host,
            'from_name' => $this->from_name,
            'from_address' => $this->from_address,
            'sender_host' => $this->sender_host,
            'message_api' => $message_api,
            'api_validation_response' => $this->api_validation_response,
        ];
    }

}
