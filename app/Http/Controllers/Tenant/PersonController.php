<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\PersonRequest;
use App\Http\Resources\Tenant\PersonCollection;
use App\Http\Resources\Tenant\PersonResource;
use App\Imports\PersonsImport;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\PersonType;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

use Modules\Factcolombia1\Models\Tenant\{
    TypeIdentityDocument,
    TypePerson,
    TypeRegime,
    TypeObligation,
    Country as CoCountry,
};
use App\Exports\PersonExport;
use Carbon\Carbon;
use Goutte\Client as ClientScrap;



class PersonController extends Controller
{
    protected $nameclient;

    public function index($type)
    {
        $api_service_token = config('configuration.api_service_token');
        return view('tenant.persons.index', compact('type','api_service_token'));
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número'
        ];
    }

    public function records($type, Request $request)
    {
      //  return 'sd';
        $records = Person::where($request->column, 'like', "%{$request->value}%")
                            ->where('type', $type)
                            ->orderBy('name');

        return new PersonCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.customers.form');
    }

    public function tables()
    {
        // $countries = Country::whereActive()->orderByDescription()->get();
        // $departments = Department::whereActive()->orderByDescription()->get();
        // $provinces = Province::whereActive()->orderByDescription()->get();
        // $districts = District::whereActive()->orderByDescription()->get();
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $person_types = PersonType::get();
        // $locations = $this->getLocationCascade();
        $api_service_token = config('configuration.api_service_token');

        $typeIdentityDocuments = TypeIdentityDocument::all();
        $typeRegimes = TypeRegime::all();
        $typePeople = TypePerson::all();
        $typeObligations = TypeObligation::all();
        $countries = CoCountry::all();

        return compact('countries', 'identity_document_types','person_types','api_service_token', 'typeIdentityDocuments', 'typeRegimes',
                        'typePeople', 'typeObligations');
    }

    public function record($id)
    {
        $record = new PersonResource(Person::findOrFail($id));

        return $record;
    }

    public function store(PersonRequest $request)
    {

        if($request->state){
            if($request->state != "ACTIVO"){
                return [
                    'success' => false,
                    'message' =>'El estado del contribuyente no es activo, no puede registrarlo',
                ];
            }
        }

        $id = $request->input('id');
        $person = Person::firstOrNew(['id' => $id]);
        $person->fill($request->all());
        $person->save();

        $person->addresses()->delete();
        $addresses = $request->input('addresses');
        foreach ($addresses as $row)
        {
            $person->addresses()->updateOrCreate( ['id' => $row['id']], $row);
        }

        $person_type = ($person->type == 'customers') ? 'Cliente':'Proveedor';

        return [
            'success' => true,
            'message' => ($id)? "{$person_type} editado con éxito":"{$person_type} registrado con éxito",
            'id' => $person->id
        ];
    }

    public function destroy($id)
    {
        try {

            $person = Person::findOrFail($id);
            $person_type = ($person->type == 'customers') ? 'Cliente':'Proveedor';
            $person->delete();

            return [
                'success' => true,
                'message' => $person_type.' eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El {$person_type} esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el {$person_type}"];

        }

    }


    /**
     * Eliminar todos los registros que no tienen transacciones asociadas
     *
     * @return array
     */
    public function deleteAll($type)
    {

        $quantity_deleted = 0;
        $persons = Person::whereType($type)->select('id', 'name')->get();

        foreach ($persons as $person) {

            // si tienen registros asociados no se eliminan
            try {

                $person->delete();
                $quantity_deleted++;

            } catch (Exception $e){
            }

        }

        return [
            'success' => true,
            'message' => "{$quantity_deleted} registro(s) eliminados"
        ];

    }


    //==========================================================================================================================
    //    Modificar en el archivo: vendor\maatwebsite\excel\src\Reader.php para que la funcion: read quede de la siguiente manera:

    //        public function read($import, $filePath, string $readerType = null, string $disk = null)
    //        {
    //            $this->reader = $this->getReader($import, $filePath, $readerType, $disk);

    //            if ($import instanceof WithChunkReading) {
    //                return (new ChunkReader)->read($import, $this, $this->currentFile);
    //            }

    //            try {
    //                $this->loadSpreadsheet($import, $this->reader);

    //                ($this->transaction)(function () use ($import) {
    //                    foreach ($this->sheetImports as $index => $sheetImport) {
    //                        if($index == 0){
    //                            if ($sheet = $this->getSheet($import, $sheetImport, $index)) {
    //                                $sheet->import($sheetImport, $sheet->getStartRow($sheetImport));
    //                                $sheet->disconnect();
    //                            }
    //                        }
    //                    }
    //                });

    //                $this->afterImport($import);
    //            } catch (Throwable $e) {
    //                $this->raise(new ImportFailed($e));
    //                throw $e;
    //            }

    //            return $this;
    //        }

    //    Lo anterior con el fin de que solo se importe la primera hoja de cada archivo de excel.
    //==========================================================================================================================

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $filePath = $request->file('file')->getPathname();
                $import = new PersonsImport($filePath);
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()." Modificar en el archivo: vendor\maatwebsite\excel\src\Reader.php funcion: read, verificar procedimiento en el archivo: app\Http\Controllers\Tenant\PersonController.php funcion: import"
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

    public function getLocationCascade()
    {
        $locations = [];
        $departments = Department::where('active', true)->get();
        foreach ($departments as $department)
        {
            $children_provinces = [];
            foreach ($department->provinces as $province)
            {
                $children_districts = [];
                foreach ($province->districts as $district)
                {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => $district->description
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => $province->description,
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => $department->description,
                'children' => $children_provinces
            ];
        }

        return $locations;
    }


    public function enabled($type, $id)
    {

        $person = Person::findOrFail($id);
        $person->enabled = $type;
        $person->save();

        $type_message = ($type) ? 'habilitado':'inhabilitado';

        return [
            'success' => true,
            'message' => "Cliente {$type_message} con éxito"
        ];

    }


    public function coExport($type)
    {
        $records = Person::where('type', $type)
                            ->get();

        $name = $type == "customers" ? "Clientes":"Proveedores";

        return (new PersonExport)
                ->records($records)
                ->download($name.Carbon::now().'.xlsx');

    }

    public  function setNameClient($name)
    {
        $this->nameclient = $name;
    }

    public function searchName($nit)
    {
        $client = new ClientScrap();
        $crawler = $client->request('GET', "https://www.einforma.co/servlet/app/portal/ENTP/prod/LISTA_EMPRESAS/razonsocial/{$nit}");
        $crawler->filter('h1[class="title01"]')->each(function($node) {
            //dd($node->text());
            $this->setNameClient($node->text());
        });

        /*each(function ($node) use ($datos) {
          //  print $node->text()."\n";
            array_push($datos, $node->html());
            dd($node->text());
        });*/

        return [
            'data' => $this->nameclient
        ];
    }


    /**
     * Busqueda de cliente por id
     *
     * @param  int $id
     * @return array
     */
    public function searchCustomerById($id)
    {

        return [
            'customers' => Person::where('id', $id)
                                    ->take(1)
                                    ->get()->transform(function($row){
                                        return $row->getRowSearchResource();
                                    })
        ];

    }

    /**
     * Busqueda de clientes
     * Si no ingresan datos para búsqueda, retorna los 10 primeros (usar en método tables)
     *
     * Usado en:
     * RemissionController
     *
     * @param  Request $request
     * @return array
     */
    public function searchCustomers(Request $request)
    {

        if(!$request->has('input'))
        {
            $customers = Person::whereType('customers')->take(10);
        }
        else
        {
            $customers = Person::whereFilterSearchCustomer($request->input);
        }

        return [
            'customers' => $customers->get()->transform(function($row){
                return $row->getRowSearchResource();
            })
        ];

    }


    /**
     * Busqueda de proveedores
     * Si no ingresan datos para búsqueda, retorna los 10 primeros (usar en método tables)
     *
     * Usado en:
     * RemissionController
     *
     * @param  Request $request
     * @return array
     */
    public function searchSuppliers(Request $request)
    {
        $suppliers = (!$request->has('input')) ? Person::whereType('suppliers')->take(Person::RECORDS_ON_TABLE) : Person::whereFilterSearchSupplier($request->input);

        return [
            'suppliers' => $suppliers->get()->transform(function($row){
                return $row->getRowSearchResource();
            })
        ];
    }


    /**
     * Busqueda de registro por id
     *
     * @param  int $id
     * @return array
     */
    public function searchPersonById($id)
    {
        return Person::where('id', $id)
                    ->take(1)
                    ->get()->transform(function($row){
                        return $row->getRowSearchResource();
                    });
    }

}
