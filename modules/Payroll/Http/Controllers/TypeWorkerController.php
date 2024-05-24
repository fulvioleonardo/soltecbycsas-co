<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Factcolombia1\Models\TenantService\{
    TypeWorker
};
use Modules\Payroll\Http\Resources\{
    TypeWorkerCollection,
    TypeWorkerResource,
};
use Modules\Payroll\Http\Requests\TypeWorkerRequest;


class TypeWorkerController extends Controller
{
    
    public function index()
    {
        return view('payroll::type-workers.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'code' => 'Código',
        ];
    }

    public function records(Request $request)
    {
        $records = TypeWorker::where($request->column, 'like', "%{$request->value}%")->latest();

        return new TypeWorkerCollection($records->paginate(config('tenant.items_per_page')));
    }
 
    public function record($id)
    {
        $record = new TypeWorkerResource(TypeWorker::findOrFail($id));

        return $record;
    }

    public function store(TypeWorkerRequest $request)
    {
        $id = $request->input('id');
        $record = TypeWorker::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Tipo de empleado editado con éxito':'Tipo de empleado registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $record = TypeWorker::findOrFail($id);
        $record->delete(); 

        return [
            'success' => true,
            'message' => 'Tipo de empleado eliminado con éxito'
        ];
    }

}
