<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payroll\Models\{
    Worker
};
use Modules\Payroll\Http\Resources\{
    WorkerCollection,
    WorkerResource,
};
use Modules\Payroll\Http\Requests\WorkerRequest;
use Modules\Factcolombia1\Models\TenantService\{
    TypeWorker,
    SubTypeWorker,
    PayrollTypeDocumentIdentification,
    PayrollPeriod,
    TypeContract,
    Municipality
};
use Modules\Factcolombia1\Models\Tenant\{
    PaymentMethod,
};


class WorkerController extends Controller
{
    
    public function index()
    {
        return view('payroll::workers.index');
    }

    public function columns()
    {
        return [
            'first_name' => 'Nombre',
            'identification_number' => 'Número',
            'code' => 'Código',
        ];
    }

    public function tables()
    {
        return [
            'type_workers' => TypeWorker::get(),
            'sub_type_workers' => SubTypeWorker::get(),
            'payroll_type_document_identifications' => PayrollTypeDocumentIdentification::get(),
            'type_contracts' => TypeContract::get(),
            'payroll_periods' => PayrollPeriod::get(),
            'payment_methods' => PaymentMethod::get(),
            'municipalities' => Municipality::get(),
        ];
    }

    
    public function records(Request $request)
    {
        $records = Worker::where($request->column, 'like', "%{$request->value}%")->latest();

        return new WorkerCollection($records->paginate(config('tenant.items_per_page')));
    }
 
    public function record($id)
    {
        $record = new WorkerResource(Worker::findOrFail($id));

        return $record;
    }

    public function store(WorkerRequest $request)
    {
        $id = $request->input('id');
        $record = Worker::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Empleado editado con éxito':'Empleado registrado con éxito',
            'id' => $record->id
        ];
    }

    public function destroy($id)
    {
        $record = Worker::findOrFail($id);
        $record->delete(); 

        return [
            'success' => true,
            'message' => 'Empleado eliminado con éxito'
        ];
    }

    
    public function searchWorkers(Request $request)
    {
        return [
            'workers' => Worker::whereFilterSearch($request)->get()->transform(function($row){
                return $row->getSearchRowResource();
            })
        ];
    }
    
    public function searchWorkerById($id)
    {
        return [
            'workers' => Worker::where('id', $id)->take(1)->get()->transform(function($row){
                return $row->getSearchRowResource();
            })
        ];
    }


}
