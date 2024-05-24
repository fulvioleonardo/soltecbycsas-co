<?php

namespace Modules\Factcolombia1\Helpers;

use Illuminate\Support\Facades\Log;
use Exception;

class HttpConnectionApi
{

    protected $api_token;
    protected $base_url;

    public function __construct($api_token)
    {
        $this->api_token = $api_token;
        $this->base_url = config('tenant.service_fact');
    }

    public function sendRequestToApi($url, $params, $method = 'PUT')
    {
        try {
            $ch = curl_init("{$this->base_url}{$url}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer {$this->api_token}"
            ));

            $response = curl_exec($ch);
            $curl_error = curl_error($ch);
            if($curl_error) return $this->responseMessage(false, 'Error en la petición a la Api');

            // dd($response);

            return json_decode($response, true);

        }catch (Exception $e)
        {
            return $this->responseError($e);
        }

    }

    public function responseMessage($success, $message)
    {
        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function responseError($e)
    {
        Log::error("Line: {$e->getLine()} - File: {$e->getFile()} Message: {$e->getMessage()}");

        return [
            'success' => false,
            'message' => "Error desconocido: {$e->getMessage()}",
        ];
    }


    public function get($url)
    {

        try {

            $ch = curl_init("{$this->base_url}{$url}");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer {$this->api_token}"
            ));

            $response = curl_exec($ch);
            $curl_error = curl_error($ch);

            if($curl_error) return $this->responseMessage(false, 'Error en la petición a la Api');

            return json_decode($response, true);

        }catch (Exception $e)
        {
            return $this->responseError($e);
        }

    }

    public function parseErrorsToString($errors)
    {

        $message = "Error de validación Api: ";

        foreach ($errors as $key => $value) {
            $message .= implode(', ', $value)." - ";
        }

        return $message;
    }

}
