<?php

namespace App\Imports;

use App\Models\Tenant\Document;
use App\Models\Tenant\Person;
use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use DateTime;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Modules\Factcolombia1\Http\Controllers\Tenant\DocumentController;
use Modules\Factcolombia1\Http\Requests\Tenant\DocumentRequest;
use Modules\Factcolombia1\Models\Tenant\PaymentForm;
use Modules\Factcolombia1\Models\Tenant\PaymentMethod;
use Exception;

class CoDocumentsImport implements ToCollection
{
    use Importable;
    protected $data;

    public function throwException($message)
    {
        throw new Exception($message);
    }

    public function ExcelDateToPHP($value){
        $BaseDate = new \DateTime('1900-01-01');
        $PHPDate = clone $BaseDate;
        $PHPDate->modify('+' . ($value - 2) . ' days');
        return $PHPDate->format('Y-m-d');
    }

    public function ExcelTimeToPHP($value){
        $daySeconds = 86400; // 60 segundos * 60 minutos * 24 horas
        $secondsFrom19070 = $value * $daySeconds;
        $PHPTime = new \DateTime('@' . $secondsFrom19070);
        return $PHPTime->format('H:i:s');
    }

    public function validate(Collection $rows){
        unset($rows[0]);
        $row_number = 1;
        foreach ($rows as $row){
            $document = Document::where('prefix', $row[4])->where('number', $row[0])->get();
            if(count($document) > 0)
                $this->throwException('Registro ... '.$row_number.', Error en el campo prefix + number, El documento '.$row[4].'-'.$row[0].' ya fue registrado en la base de datos...');
            $person = Person::where('number', $row[10])->get();
            if(count($person) == 0)
                $this->throwException('Registro ... '.$row_number.', Error en el campo customer, No existe el documento '.$row[10].' en la base de datos...');
            $item = Item::where('internal_id', $row[23])->get();
            if(count($item) == 0)
                $this->throwException('Registro ... '.$row_number.', Error en el campo code, No existe el item '.$row[23].' en la base de datos...');
            $actual_date = new DateTime(Carbon::now()->format('Y-m-d'));
            $document_date = new DateTime(Carbon::parse(str_replace("/", "-", $this->ExcelDateToPHP($row[1])))->format('Y-m-d'));
            $interval = $actual_date->diff($document_date);
            if($interval->days >= 10)
                $this->throwException('Registro ... '.$row_number.', Error en el campo fecha, No puede ser mayor o igual a 10 dias antes de la fecha actual...');
            $row_number++;
        }
    }

    public function collection(Collection $rows){
        $total = count($rows);
        $registered = 0;
        $send = new DocumentController();
        $request = new DocumentRequest();
        unset($rows[0]);
        $previos_prefix_number = "";
        $this->validate($rows);
        foreach ($rows as $row){
            if($row[4].$row[0] != $previos_prefix_number){
                if($previos_prefix_number != ""){
//                    \Log::debug(json_encode($json));
                    $send->store($request, json_encode($json));
                    sleep(5);
                }
                $previos_prefix_number = $row[4].$row[0];
                $number = $row[0];
                $date = $this->ExcelDateToPHP($row[1]);
                $time = $this->ExcelTimeToPHP($row[2]);
                $resolution_number = $row[3];
                $prefix = $row[4];
                $notes = $row[27];
                $establishment_name = $row[5];
                $establishment_address = $row[6];
                $establishment_phone = $row[7];
                $establishment_municipality = $row[8];
                $establishment_email = $row[9];
                $head_note = $row[26];
                $foot_note = $row[27];
                $person = Person::where('number', $row[10])->firstOrFail();
                $customer = [
                    'customer_id' => $person->id,
                    'identification_number' => $person->code,
                    'dv' => $person->dv,
                    'name' => $person->name,
                    'municipality_id_fact' => $person->city_id,
                    'phone' => $person->telephone,
                    'address' => $person->address,
                    'email' => $person->email,
                    'type_organization_id' => $person->type_person_id,
                    'type_document_identification_id' => $person->identity_document_type_id,
                    'type_liability_id' => $person->type_obligation_id,
                    'type_regime_id' => $person->type_regime_id,
                    'merchant_registration' => "00000000",
                ];
                $payment_form = [
                    'payment_form_id' => is_string($row[11]) ? PaymentForm::where('name', 'like', '%'.str_replace('_x000D_', '', $row[11]).'%')->firstOrFail()->id : $row[11],
                    'payment_method_id' => is_string($row[12]) ? PaymentMethod::where('name', 'like', '%'.str_replace('_x000D_', '', $row[12]).'%')->firstOrFail()->id : $row[12],
                    'payment_due_date' => $this->ExcelDateToPHP($row[13]),
                    'duration_measure' => $row[14],
                ];
                $legal_monetary_totals = [
                    'line_extension_amount' => $row[15],
                    'tax_exclusive_amount' => $row[16],
                    'tax_inclusive_amount' => $row[17],
                    'allowance_total_amount' => $row[18],
                    'charge_total_amount' => $row[19],
                    'payable_amount' => $row[20],
                ];
                if($row[28] != '' && $row[29] != '' && $row[30] != ''){
                    $health_fields = [
                        'invoice_period_start_date' => $this->ExcelDateToPHP($row[28]),
                        'invoice_period_end_date' => $this->ExcelDateToPHP($row[29]),
                    ];

                    $us_i = explode("%", $row[30]);
                    $users_info = [];
                    foreach($us_i as $u){
                        $u_i = explode(",", $u);
                        $arr_u_i = [
                            'provider_code' => $u_i[0],
                            'health_type_document_identification_id' => $u_i[1],
                            'identification_number' => $u_i[2],
                            'surname' => $u_i[3],
                            'second_surname' => $u_i[4],
                            'first_name' => $u_i[5],
                            'middle_name' => $u_i[6],
                            'health_type_user_id' => $u_i[7],
                            'health_contracting_payment_method_id' => $u_i[8],
                            'health_coverage_id' => $u_i[9],
                            'autorization_numbers' => $u_i[10],
                            'mipres' => $u_i[11],
                            'mipres_delivery' => $u_i[12],
                            'contract_number' => $u_i[13],
                            'policy_number' => $u_i[14],
                            'co_payment' => $u_i[15],
                            'moderating_fee' => $u_i[16],
                            'recovery_fee' => $u_i[17],
                            'shared_payment' => $u_i[18],
                        ];
                        $users_info[] = $arr_u_i;
                    }
                }
                $invoice_lines = [];
            }
            $item = Item::where('internal_id', $row[23])->firstOrFail();
            $invoice_line = [
                'item_id' => $item->id,
                'unit_type_id' => $item->unit_type_id,
                'quantity' => $row[21],
                'unit_price' => $row[24],
                'tax_id' => $item->tax_id,
                'total_tax' => ($row[24] * $row[21]) - $row[22],
                'subtotal' => $row[22],
                'discount'  => 0,
                'total' => ($row[24] * $row[21]) - 0,
                'unit_measure_id' => $item->unit_type->code,
                'invoiced_quantity' => $row[21],
                'line_extension_amount' => $row[22],
                'free_of_charge_indicator' => false,
                'description' => $item->description,
                'code' => $row[23],
                'type_item_identification_id' => 4,
                'price_amount' => $row[24],
                'base_quantity' => 1,
            ];

            $invoice_lines[] = $invoice_line;

            $json = array(
                'number' => $number,
                'type_document_id' => 1,
                'date' => $date,
                'time' => $time,
                'resolution_number' => $resolution_number,
                'prefix' => $prefix,
                'notes' => $notes,
                'establishment_name' => $establishment_name,
                'establishment_address' => $establishment_address,
                'establishment_phone' => $establishment_phone,
                'establishment_municipality' => $establishment_municipality,
                'establishment_email' => $establishment_email,
                'customer' => $customer,
                'payment_form' => $payment_form,
                'legal_monetary_totals' => $legal_monetary_totals,
                'invoice_lines' => $invoice_lines,
                'head_note' => $head_note,
                'foot_note' => $foot_note,
            );
            $registered += 1;
            $this->data = compact('total', 'registered');
            if($row[28] != '' && $row[29] != '' && $row[30] != ''){
                $json['health_fields'] = $health_fields;
                $json['users_info'] = $users_info;
            }
        }
        if($row[28] != '' && $row[29] != '' && $row[30] != ''){
            $json['health_fields'] = $health_fields;
            $json['users_info'] = $users_info;
        }
        \Log::debug(json_encode($json));
        $send->store($request, json_encode($json));
        $this->data = compact('total', 'registered');
    }

    public function getData()
    {
        return $this->data;
    }
}
