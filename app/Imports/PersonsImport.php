<?php

namespace App\Imports;

use App\Models\Tenant\Person;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Modules\Factcolombia1\Models\Tenant\TypePerson;
use Modules\Factcolombia1\Models\Tenant\TypeRegime;
use Modules\Factcolombia1\Models\TenantService\TypeDocumentIdentification;
use Modules\Factcolombia1\Models\Tenant\City;

class PersonsImport implements ToCollection
{
    use Importable;

    protected $data;
    protected $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            foreach ($rows as $row)
            {
                $type = request()->input('type');
                $type_person_id = is_string($row[0]) ? TypePerson::where('name', 'like', '%'.str_replace('_x000D_', '', $row[0]).'%')->firstOrFail()->id : $row[0];
                $type_regime_id = is_string($row[1]) ? TypeRegime::where('name', 'like', '%'.str_replace('_x000D_', '', $row[1]).'%')->firstOrFail()->id : $row[1];
                $identity_document_type_id = is_string($row[2]) ? TypeDocumentIdentification::where('name', 'like', '%'.str_replace('_x000D_', '', $row[2]).'%')->firstOrFail()->id : $row[2];
                $number = $row[3];
                $dv = $row[4];
                $code = $row[5];
                $name = $row[6];
                $country_id = 47;
                $city_id = $row[7];
                $department_id = City::where('id', $city_id)->firstOrFail()->department_id;
                $address = $row[8];
                $telephone = $row[9];
                $email = $row[10];

                $person = Person::where('type', $type)
                                ->where('identity_document_type_id', $identity_document_type_id)
                                ->where('number', $number)
                                ->first();

                if(!$person) {
                    Person::create([
                        'type' => $type,
                        'type_person_id' => $type_person_id,
                        'identity_document_type_id' => $identity_document_type_id,
                        'type_regime_id' => $type_regime_id,
                        'number' => $number,
                        'dv' => $dv,
                        'code' => $code,
                        'name' => $name,
                        'country_id' => $country_id,
                        'department_id' => $department_id,
                        'city_id' => $city_id,
                        'address' => $address,
                        'telephone' => $telephone,
                        'email' => $email,
                    ]);
                    $registered += 1;
                }else{
                    $person->update([
                        'type' => $type,
                        'type_person_id' => $type_person_id,
                        'identity_document_type_id' => $identity_document_type_id,
                        'type_regime_id' => $type_regime_id,
                        'number' => $number,
                        'dv' => $dv,
                        'code' => $code,
                        'name' => $name,
                        'country_id' => $country_id,
                        'department_id' => $department_id,
                        'city_id' => $city_id,
                        'address' => $address,
                        'telephone' => $telephone,
                        'email' => $email,
                    ]);
                    $registered += 1;
                }
            }
            $this->data = compact('total', 'registered');
    }

    public function getData()
    {
        return $this->data;
    }
}
