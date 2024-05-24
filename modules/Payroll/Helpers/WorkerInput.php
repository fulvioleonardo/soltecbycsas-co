<?php

namespace Modules\Payroll\Helpers;

use Modules\Payroll\Models\{
    Worker
};

class WorkerInput
{
    public static function set($worker_id)
    {
        
        $worker = Worker::findOrFail($worker_id);

        return [
            'type_worker_id' => $worker->type_worker_id,
            'type_worker' => [
                'id' => $worker->type_worker_id,
                'name' => $worker->type_worker->name,
            ],
            'sub_type_worker_id' => $worker->sub_type_worker_id,
            'sub_type_worker' => [
                'id' => $worker->sub_type_worker_id,
                'name' => $worker->sub_type_worker->name,
            ],
            'payroll_type_document_identification_id' => $worker->payroll_type_document_identification_id,
            'payroll_type_document_identification' => [
                'id' => $worker->payroll_type_document_identification_id,
                'name' => $worker->payroll_type_document_identification->name,
            ], 
            'municipality_id' => $worker->municipality_id,
            'municipality' => [
                'id' => $worker->municipality_id,
                'name' => $worker->municipality->name,
            ], 
            'type_contract_id' => $worker->type_contract_id,
            'type_contract' => [
                'id' => $worker->type_contract_id,
                'name' => $worker->type_contract->name,
            ], 
            'code' => $worker->code,
            'high_risk_pension' => $worker->high_risk_pension,
            'identification_number' => $worker->identification_number,
            'surname' => $worker->surname,
            'second_surname' => $worker->second_surname,
            'first_name' => $worker->first_name,
            'address' => $worker->address,
            'integral_salarary' => $worker->integral_salarary,
            'salary' => $worker->salary,
        ];
    }
}