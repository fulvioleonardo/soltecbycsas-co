<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\SoapType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CompanyRequest;
use App\Http\Resources\Tenant\CompanyResource;
use Illuminate\Http\Request;
use Modules\Factcolombia1\Models\Tenant\Company as CoCompany;
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceTenantCompany
};
use Illuminate\Support\Facades\Storage;
use Validator;


class CompanyController extends Controller
{
    public function create()
    {
        return view('tenant.companies.form');
    }

    public function tables()
    {
        $soap_sends = config('tables.system.soap_sends');
        $soap_types = SoapType::all();
        return compact('soap_types', 'soap_sends');
    }

    public function record()
    {
        $company = CoCompany::active();
        $record = new CompanyResource($company);
        return $record;
    }

    public function store(CompanyRequest $request)
    {
        $id = $request->input('id');
        $company = CoCompany::find($id);
        $company->fill($request->all());
        $company->save();

        return [
            'success' => true,
            'message' => 'Empresa actualizada'
        ];
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $company = CoCompany::active();

            $type = $request->input('type');
            $establishment_id = $request->input('establishment_id');
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = $type.'_'.$company->identification_number.'.'.$ext;
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' =>  'Tipo de archivo no permitido',
                ];
            }

            // if (($type === 'logo')) request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            $file->storeAs(($type === 'logo') ? 'public/uploads/logos' : 'certificates', $name);

            // if (($type === 'logo_store')) request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            $file->storeAs(($type === 'logo_store') ? 'public/uploads/logos' : 'certificates', $name);

            // if (($type === 'logo_login')) request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            $file->storeAs(($type === 'jpg_firma_facturas') ? 'public/uploads/logos' : 'certificates', $name);

            $file->storeAs(($type === 'establishment_logo') ? 'public/uploads/logos' : 'certificates', $establishment_id."_".$name);

            $sucursal = \App\Models\Tenant\Establishment::where('id', $establishment_id)->first();
            if($type === 'establishment_logo'){
                $sucursal->establishment_logo = $establishment_id.'_'.$name;
                $sucursal->save();
            }
            else
                if($type === 'logo_login')
                {
                    $file->storeAs(($type === 'logo_login') ? 'public/uploads/logos' : 'certificates', $name);
                    $company_t = Company::active();
                    $company_t->$type = $name;
                    $company_t->save();
                }
                else
                {
                    $company->$type = $name;
                    $company->save();
                    $company_t = Company::active();
                    $company_t->$type = $name;
                    $company_t->save();
                }

            if($type == 'logo')
            {
                //$path = 'public/uploads/logos/'.$name;
                $contents = Storage::get('public/uploads/logos/'.$name);

                $cor = ServiceTenantCompany::firstOrFail();
                $payload = json_encode(['logo' => base64_encode( $contents ) ]);
                $base_url = config('tenant.service_fact');
                $ch = curl_init("{$base_url}ubl2.1/config/logo");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS,($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    "Authorization: Bearer {$cor->api_token}"
                ));
                $response = curl_exec($ch);
                curl_close($ch);
            }

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name' => $name,
                'type' => $type
            ];
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }
}
