<?php

namespace Modules\Payroll\Traits; 

use Illuminate\Support\Facades\Log;
use Exception;

trait UtilityTrait
{ 
    
    /**
     * Retorna array con mensaje de error y registra detalle en el log
     *
     * @param  string $message
     * @param  Exception $exception
     * @return array
     */
    public function getErrorFromException($message, Exception $exception) {

        $this->setErrorLog($exception);

        return [
            'success' => false,
            'message' => $message
        ];

    }

    
    public function setErrorLog($exception)
    {
        Log::error("Code: {$exception->getCode()} - Line: {$exception->getLine()} - Message: {$exception->getMessage()} - File: {$exception->getFile()}");
    }


}
