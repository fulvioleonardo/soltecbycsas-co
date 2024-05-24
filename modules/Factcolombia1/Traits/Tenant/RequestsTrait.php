<?php

namespace Modules\Factcolombia1\Traits\Tenant;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

/**
 * 
 */
trait RequestsTrait
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator) {
        $validator->errors()
            ->add('form_error', $this->form);
        
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
