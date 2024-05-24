<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNoteItem;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\Models\Tenant\Item;
use App\Models\Tenant\Series;
use App\Http\Resources\Tenant\DocumentPosCollection;
use App\Http\Resources\Tenant\SaleNoteResource;
use App\Http\Resources\Tenant\SaleNoteResource2;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\DocumentType;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use Modules\Factcolombia1\Models\Tenant\Company as CoCompany;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dispatch;
use App\Http\Requests\Tenant\SaleNoteRequest;
// use App\Models\Tenant\Warehouse;
use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Models\Tenant\PaymentMethodType;
use App\Mail\Tenant\SaleNoteEmail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\ItemLot;
use App\Models\Tenant\ItemWarehouse;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Item\Models\ItemLotsGroup;
use App\Models\Tenant\Configuration;
use Modules\Factcolombia1\Models\Tenant\{
    Currency,
    TypeDocument,
    Tax,
};
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceTenantCompany
};
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPos;
use App\Models\Tenant\DocumentPosItem;
use App\Models\Tenant\DocumentPosPayment;
use App\Models\Tenant\ConfigurationPos;
use App\Http\Resources\Tenant\DocumentPosResource;
use App\Models\Tenant\Cash;




class DocumentPosController extends Controller
{

    use StorageDocument, FinanceTrait;

    protected $sale_note;
    protected $company;
    protected $apply_change;

    public function index()
    {
        return view('tenant.pos.documents');
    }


    public function create($id = null)
    {
        return view('tenant.sale_notes.form', compact('id'));
    }

    public function create_refund($id)
    {
        return view('tenant.pos.refund', compact('id'));
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha de emisión',
        ];
    }

    public function columns2()
    {
        return [
            'series' => Series::whereIn('document_type_id', ['80'])->get(),
        ];
    }

    public function records(Request $request)
    {
        $records = DocumentPos::where($request->column, 'like', "%{$request->value}%")
                            ->latest('id');


        return new DocumentPosCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function searchCustomers(Request $request)
    {

        $customers = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('customers')->orderBy('name')
                            ->whereIsEnabled()
                            ->get()->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                    'address' =>  $row->address,
                                    'email' =>  $row->email,
                                    'telephone' =>  $row->telephone,
                                ];
                            });

        return compact('customers');
    }

    public function tables()
    {
        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        // $currency_types = CurrencyType::whereActive()->get();
        // $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        // $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $payment_method_types = PaymentMethodType::all();
        $series = collect(Series::all())->transform(function($row) {
            return [
                'id' => $row->id,
                'contingency' => (bool) $row->contingency,
                'document_type_id' => $row->document_type_id,
                'establishment_id' => $row->establishment_id,
                'number' => $row->number
            ];
        });
        $payment_destinations = $this->getPaymentDestinations();
        $currencies = Currency::all();
        $taxes = $this->table('taxes');

        return compact('customers', 'establishments','currencies', 'taxes','company','payment_method_types', 'series', 'payment_destinations');
    }

    public function changed($id)
    {
        $sale_note = DocumentPos::find($id);
        $sale_note->changed = true;
        $sale_note->save();
    }


    public function item_tables()
    {
        $taxes = $this->table('taxes');
        $items = $this->table('items');
        $categories = [];

        return compact('items', 'categories', 'taxes');
    }


    public function record($id)
    {
        $record = new DocumentPosResource(DocumentPos::findOrFail($id));

        return $record;
    }


    public function record2($id)
    {
        $record = new SaleNoteResource2(DocumentPos::findOrFail($id));

        return $record;
    }

    public function store(Request $request)
    {
        DB::connection('tenant')->beginTransaction();
        try{
//        DB::connection('tenant')->transaction(function () use ($request) {
            $data = $this->mergeData($request);
//            \Log::debug($request);
//            \Log::debug(json_encode($data));
            $customer = Person::where('number', $data['customer']['number'])->where('type', 'customers')->firstOrFail();
            $tax_totals = [];
            $invoice_lines = [];
            $tax_exclusive_amount = 0;
            foreach($data['items'] as $row){
                $invoice_lines[] = [
                    'unit_measure_id' => $row['item']['unit_type']['code'],
                    'invoiced_quantity' => $row['quantity'],
                    'line_extension_amount' => (string)($row['total'] - $row['total_tax']),
                    'free_of_charge_indicator' => false,
                    'description' => !empty($row['item']['description']) ? $row['item']['description'] : 'Sin descripción',
                    'notes' => null,
                    'code' => $row['item']['internal_id'],
                    'type_item_identification_id' => 4,
                    'price_amount' => $row['item']['edit_sale_unit_price'],
                    'base_quantity' => $row['quantity']
                ];
                if($row['item']['tax'] !== null){
                    $invoice_lines[count($invoice_lines) - 1]['tax_totals'] = [
                        [
                            'tax_id' => $row['item']['tax']['type_tax']['id'],
                            'tax_amount' => $row['total_tax'],
                            'taxable_amount' => number_format((float)($row['item']['sale_unit_price'] * $row['quantity']), 2, '.', ''),
                            'percent' => $row['item']['tax']['rate'],
                        ]
                    ];
                }
                if($row['item']['tax'] !== null){
                    $tax_id = $row['item']['tax']['type_tax']['id'];
                    $percent = $row['item']['tax']['rate'];
                    $tax_amount = $row['total_tax'];
                    $taxable_amount = $row['item']['sale_unit_price'] * $row['quantity'];
                    if (strpos($taxable_amount, '.') !== false){
                        // Si ya tiene dos decimales, no es necesario agregar más
                        $taxable_amount = number_format($taxable_amount, 2, '.', '');
                    } else {
                        // Si solo tiene un decimal, agregar un cero adicional
                        $taxable_amount = number_format($taxable_amount, 1, '.', '') . '0';
                    }
                    if(isset($tax_totals[$tax_id][$percent])) {
                        // Si ya existe, actualizar los valores
                        $tax_totals[$tax_id][$percent]['tax_amount'] += $tax_amount;
                        $tax_totals[$tax_id][$percent]['taxable_amount'] += $taxable_amount;
                    }
                    else {
                    // Si no existe, agregar un nuevo elemento
                        $tax_totals[] = [
                            'tax_id' => $tax_id,
                            'percent' => $percent,
                            'tax_amount' => $tax_amount,
                            'taxable_amount' => $taxable_amount,
                        ];
                    }
                    $tax_exclusive_amount += $tax_totals[count($tax_totals) - 1]['taxable_amount'];
                }
            }
            $data_invoice_pos = [
                'number' => $data['number'],
                'type_document_id' => 15,
                'date' => $data['date_of_issue'],
	            'time' => $data['time_of_issue'],
                'postal_zone_code' => '411001',
                'resolution_number' => $data['resolution_number'],
                'prefix' => $data['prefix'],
                'notes' => null,
                'sendmail' => true,
                'sendmailtome' => true,
                'software_manufacturer' => [
                    'name' => env('APP_OWNER_NAME'),
                    'business_name' => env('APP_BUSINESS_NAME'),
                    'software_name' => env('APP_NAME'),
                ],
                'buyer_benefits' => [
                    'code' => $data['customer']['number'],
                    'name' => $data['customer']['name'],
                    'points' => "0",
                ],
                'cash_information' => [
                    'plate_number' => $data['plate_number'],
                    'location' => auth()->user()->establishment->address,
                    'cashier' => auth()->user()->name,
                    'cash_type' => $data['cash_type'],
                    'sales_code' => $data['prefix'],
                    'subtotal' => $data['sale'],
                ],
                'customer' => [
                    'identification_number' => $data['customer']['number'],
                    'dv' => $data['customer']['dv'],
                    'name' => $data['customer']['name'],
                    'phone' => $data['customer']['telephone'],
                    'address' => $data['customer']['address'],
                    'email' => $data['customer']['email'],
                    'merchant_registration' => "0000000-00",
                    'type_document_identification_id' => $customer->identity_document_type_id,
                    'type_organization_id' => $customer->type_person_id,
                    'type_liability_id' => $customer->type_obligation_id,
                    'municipality_id_fact' => $customer->city_id,
                    'type_regime_id' => $customer->type_regime_id,
                ],
                'payment_form' => [
                    'payment_form_id' => 1,
                    'payment_method_id' => 30,
                    'payment_due_date' => $data['date_of_issue'],
                    'duration_measure' => "0",
                ],
                'legal_monetary_totals' => [
                    'line_extension_amount' => $data['sale'],
                    'tax_exclusive_amount' => (string)$tax_exclusive_amount,
                    'tax_inclusive_amount' => $data['total'],
                    'payable_amount' => $data['total'],
                ],
                'tax_totals' => $tax_totals,
                'invoice_lines' => $invoice_lines,
            ];
//            \Log::debug(json_encode($data_invoice_pos));
//            return [
//                'success' => false,
//                'message' => "Abortando...",
//                'data' => [
//                    'id' => null,
//                ]
//            ];
            // gestion DIAN
            if($data['electronic'] == true){
                $company = ServiceTenantCompany::firstOrFail();
                $id_test = $company->test_set_id_eqdocs;
                $base_url = config('tenant.service_fact');
                if($company->eqdocs_type_environment_id == 2 && $company->test_set_id_eqdocs != 'no_test_set_id'){
                    $ch = curl_init("{$base_url}ubl2.1/eqdoc/{$id_test}");
                }
                else
                    $ch = curl_init("{$base_url}ubl2.1/eqdoc");
                $data_document = json_encode($data_invoice_pos);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS,($data_document));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    "Authorization: Bearer {$company->api_token}"
                ));
                $response = curl_exec($ch);
//                \Log::debug($company->eqdocs_type_environment_id);
//                \Log::debug($company->test_set_id_eqdocs);
//                \Log::debug("{$base_url}ubl2.1/eqdoc");
//                \Log::debug($company->api_token);
//                \Log::debug($data_document);
//                \Log::debug($response);
                curl_close($ch);
                $response_model = json_decode($response);
                $zip_key = null;
                $invoice_status_api = null;

                if($company->eqdocs_type_environment_id == 2 && $company->test_set_id_eqdocs != 'no_test_set_id'){
                    if(property_exists($response_model, 'urlinvoicepdf') && property_exists($response_model, 'urlinvoicexml')){
                        if(!is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey)){
                            if(is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ErrorMessageList->XmlParamsResponseTrackId->Success)){
                                if($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ErrorMessageList->XmlParamsResponseTrackId->Success == 'false'){
                                    return [
                                        'success' => false,
                                        'message' => $response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ErrorMessageList->XmlParamsResponseTrackId->ProcessedMessage,
                                        'data' => [
                                            'id' => null,
                                        ],
                                    ];
                                }
                            }
                        }
                        else
                            if(is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey))
                                $zip_key = $response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey;
                    }
                    else{
                        return [
                            'success' => false,
                            'message' => "Error el Documento Equivalente POS Nro: {$data['series']}{$data['number']}, Errores: ",
                            'data' => [
                                'id' => null,
                            ],
                        ];
                    }

                    $response_status = null;
                    //\Log::debug($zip_key);
                    if($zip_key){
                        sleep(6);
                        $ch2 = curl_init("{$base_url}ubl2.1/status/zip/{$zip_key}");
                        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");

                        if(file_exists(storage_path('sendmail.api')))
                            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode(array("sendmail" => true, "is_payroll" => false, "is_eqdoc" => true)));
                        else
                            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode(array("sendmail" => false, "is_payroll" => false, "is_eqdoc" => true)));

                        curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Accept: application/json',
                            "Authorization: Bearer {$company->api_token}"
                        ));
                        $response_status = curl_exec($ch2);
                        //\Log::debug($response_status);
                        curl_close($ch2);
                        $response_status_decoded = json_decode($response_status);
                        if(property_exists($response_status_decoded, 'ResponseDian')){
                            if($response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->IsValid == "true"){
                                $data['ambient_id'] = $company->eqdocs_type_environment_id;
                                $data['request_api'] = $data_document;
                                $data['response_api'] = $response_status;
                                $data['cude'] = $response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->XmlDocumentKey;
                                $data['qr'] = $response_model->QRStr;
                            }
                            else{
                                if(isset($response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->ErrorMessage->string)){
                                    if(is_array($response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->ErrorMessage->string)){
                                        $mensajeerror = implode(",", $response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->ErrorMessage->string);
                                    }
                                    else{
                                        $mensajeerror = $response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->ErrorMessage->string;
                                    }
                                }
                                else{
                                    $mensajeerror = $response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->StatusDescription;
                                }

                                if($response_status_decoded->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->IsValid == 'false'){
                                    return [
                                        'success' => false,
                                        'message' => "Error el Documento Equivalente POS Nro: {$data['series']}{$data['number']}, Errores: ".$mensajeerror,
                                        'data' => [
                                            'id' => null,
                                        ],
                                    ];
                                }
                            }
                        }
                        else{
                            $mensajeerror = $response_status_decoded->message;
                            return [
                                'success' => false,
                                'message' => "Error el Documento Equivalente POS Nro: {$data['series']}{$data['number']} Errores: ".$mensajeerror,
                                'data' => [
                                    'id' => null,
                                ],
                            ];
                        }
                    }
                    else{
                        // \Log::debug("C");
                        return [
                            'success' => false,
                            'message' => "Error de ZipKey.",
                            'data' => [
                                'id' => null,
                            ],
                        ];
                    }
                }
                else{
                    if(property_exists($response_model, 'send_email_success')){
                        if($response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->IsValid == "true"){
                            $data['ambient_id'] = $company->eqdocs_type_environment_id;
                            $data['request_api'] = $data_document;
                            $data['response_api'] = json_encode($response_model);
                            $data['cude'] = $response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->XmlDocumentKey;
                            $data['qr'] = $response_model->QRStr;
                        }
                        else
                        {
                            if(is_array($response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string))
                                $mensajeerror = implode(",", $response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string);
                            else
                                $mensajeerror = $response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string;
                            if($response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->IsValid == 'false'){
                                if($invoice_json == NULL)
                                    return [
                                        'success' => false,
                                        'message' => "Error al Validar Factura Nro: {$data['series']}{$data['number']} Errores: ".$mensajeerror,
                                        'data' => [
                                            'id' => null,
                                        ],
                                    ];
                            }
                        }
                    }
                    else{
                        return [
                            'success' => false,
                            'message' => "Error al Validar Factura Nro: {$data['series']}{$data['number']} Errores: ".$response_model->message,
                            'data' => [
                                'id' => null,
                            ],
                        ];
                    }
                }
            }
            // gestion DIAN
            $this->sale_note =  DocumentPos::create($data);
            // $this->sale_note->payments()->delete();
            $this->deleteAllPayments($this->sale_note->payments);
            foreach($data['items'] as $row) {
                $item_id = isset($row['id']) ? $row['id'] : null;
                $sale_note_item = DocumentPosItem::firstOrNew(['id' => $item_id]);
                if(isset($row['item']['lots'])){
                    $row['item']['lots'] = isset($row['lots']) ? $row['lots']:$row['item']['lots'];
                }
                $sale_note_item->fill($row);
                $sale_note_item->document_pos_id = $this->sale_note->id;
                $sale_note_item->save();
                if(isset($row['lots'])){
                    foreach($row['lots'] as $lot) {
                        $record_lot = ItemLot::findOrFail($lot['id']);
                        $record_lot->has_sale = true;
                        $record_lot->update();
                    }
                }
                if(isset($row['IdLoteSelected']))
                {
                    $lot = ItemLotsGroup::find($row['IdLoteSelected']);
                    $lot->quantity = ($lot->quantity - $row['quantity']);
                    $lot->save();
                }
            }

            //pagos
            // foreach ($data['payments'] as $row) {
            //     $this->sale_note->payments()->create($row);
            // }
            $this->savePayments($this->sale_note, $data['payments']);
            $this->setFilename();
            $this->createPdf($this->sale_note,"ticket", $this->sale_note->filename);
//        });
        }catch(\Exception $e){
//            \Log::debug(json_encode([
//                'success' => false,
//                'message' => $e->getMessage(),
//                'line' => $e->getLine(),
//                'trace' => $e->getTrace(),
//            ]));
            DB::connection('tenant')->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        }
        DB::connection('tenant')->commit();

        return [
            'success' => true,
            'message' => "Documento Equivalente POS Nro: {$data['series']}{$data['number']} Procesado Correctamente.",
            'data' => [
                'id' => $this->sale_note->id,
            ],
        ];
    }

    public function destroy_sale_note_item($id)
    {
        $item = DocumentPosItem::findOrFail($id);

        if(isset($item->item->lots)){

            foreach($item->item->lots as $lot) {
                // dd($lot->id);
                $record_lot = ItemLot::findOrFail($lot->id);
                $record_lot->has_sale = false;
                $record_lot->update();
            }

        }

        $item->delete();

        return [
            'success' => true,
            'message' => 'eliminado'
        ];
    }

    public function mergeData($inputs)
    {
        $this->company = Company::active();
        $config = ConfigurationPos::first();
        $user = auth()->user();
        $cash = Cash::where('state', 1)
                    ->where('user_id', $user->id)
                    ->first();
        $config =  $cash->resolution;
        if(!$config){
            throw new Exception('Resolución no establecida en caja chica actual.');
        }
        $number = null;
        $document = DocumentPos::select('id', 'number')
                                ->where('prefix', $config->prefix)
                                ->orderBy('id', 'desc')
                                ->first();
        $number = ($document) ? (int)$document->number + 1 : 1;
        $values = [
            //'automatic_date_of_issue' => $automatic_date_of_issue,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => Person::with('typePerson', 'typeRegime', 'identity_document_type', 'country', 'department', 'city')->findOrFail($inputs['customer_id']),
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id,
            'state_type_id' => '01',
            'series' => $config->prefix,
            'resolution_number' => $config->resolution_number,
            'plate_number' => $config->plate_number,
            'cash_type' => $config->cash_type,
            'number' => $number,
            'prefix' => $config->prefix,
            'electronic' => (bool)$config->electronic,
        ];
        unset($inputs['series_id']);
        $inputs->merge($values);
        return $inputs->all();
    }

//    public function recreatePdf($sale_note_id)
//    {
//        $this->sale_note = SaleNote::find($sale_note_id);
//        $this->createPdf();
//    }

    private function setFilename(){

        $name = [$this->sale_note->series,$this->sale_note->number,date('Ymd')];
        $this->sale_note->filename = join('-', $name);
        $this->sale_note->save();

    }

    public function toPrint($external_id, $format) {

        $sale_note = DocumentPos::where('external_id', $external_id)->first();

        if (!$sale_note) throw new Exception("El código {$external_id} es inválido, no se encontro la nota de venta relacionada");

        $this->reloadPDF($sale_note, $format, $sale_note->filename);
        $temp = tempnam(sys_get_temp_dir(), 'sale_note');

        file_put_contents($temp, $this->getStorage($sale_note->filename, 'sale_note'));

        return response()->file($temp);
    }

    private function reloadPDF($sale_note, $format, $filename) {
        $this->createPdf($sale_note, $format, $filename);
    }

    public function createPdf($sale_note = null, $format_pdf = null, $filename = null) {

        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $this->company = CoCompany::active();
        $this->document = ($sale_note != null) ? $sale_note : $this->sale_note;

        $this->configuration = Configuration::first();
        $configuration = $this->configuration->formats;
        $base_template = $configuration;

        $html = $template->pdf($base_template, "document_pos", $this->company, $this->document, $format_pdf);

        if (($format_pdf === 'ticket') OR ($format_pdf === 'ticket_58')) {

            $width = ($format_pdf === 'ticket_58') ? 56 : 78 ;
            if(config('tenant.enabled_template_ticket_80')) $width = 76;

            $company_logo      = ($this->company->logo) ? 40 : 0;
            $company_name      = (strlen($this->company->name) / 20) * 10;
            $company_address   = (strlen($this->document->establishment->address) / 30) * 10;
            $company_number    = $this->document->establishment->telephone != '' ? '10' : '0';
            $customer_name     = strlen($this->document->customer->name) > '25' ? '10' : '0';
            $customer_address  = (strlen($this->document->customer->address) / 200) * 10;
            $p_order           = $this->document->purchase_order != '' ? '10' : '0';

            $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
            $total_free        = $this->document->total_free != '' ? '10' : '0';
            $total_unaffected  = $this->document->total_unaffected != '' ? '10' : '0';
            $total_exonerated  = $this->document->total_exonerated != '' ? '10' : '0';
            $total_taxed       = $this->document->total_taxed != '' ? '10' : '0';
            $quantity_rows     = count($this->document->items);
            $payments     = $this->document->payments()->count() * 2;

            $extra_by_item_description = 0;
            $discount_global = 0;
            foreach ($this->document->items as $it) {
                if(strlen($it->item->description)>100){
                    $extra_by_item_description +=24;
                }
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends = $this->document->legends != '' ? '10' : '0';


            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    $width,
                    100 +
                    (($quantity_rows * 8) + $extra_by_item_description) +
                    ($discount_global * 3) +
                    $company_logo +
                    $payments +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $total_exonerated +
                    $total_taxed],
                'margin_top' => 0,
                'margin_right' => 2,
                'margin_bottom' => 0,
                'margin_left' => 2
            ]);
        } else if($format_pdf === 'a5'){

            $company_name      = (strlen($this->company->name) / 20) * 10;
            $company_address   = (strlen($this->document->establishment->address) / 30) * 10;
            $company_number    = $this->document->establishment->telephone != '' ? '10' : '0';
            $customer_name     = strlen($this->document->customer->name) > '25' ? '10' : '0';
            $customer_address  = (strlen($this->document->customer->address) / 200) * 10;
            $p_order           = $this->document->purchase_order != '' ? '10' : '0';

            $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
            $total_free        = $this->document->total_free != '' ? '10' : '0';
            $total_unaffected  = $this->document->total_unaffected != '' ? '10' : '0';
            $total_exonerated  = $this->document->total_exonerated != '' ? '10' : '0';
            $total_taxed       = $this->document->total_taxed != '' ? '10' : '0';
            $quantity_rows     = count($this->document->items);
            $discount_global = 0;
            foreach ($this->document->items as $it) {
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends           = $this->document->legends != '' ? '10' : '0';


            $alto = ($quantity_rows * 8) +
                    ($discount_global * 3) +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $total_exonerated +
                    $total_taxed;
            $diferencia = 148 - (float)$alto;

            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    210,
                    $diferencia + $alto
                    ],
                'margin_top' => 2,
                'margin_right' => 5,
                'margin_bottom' => 0,
                'margin_left' => 5
            ]);


       } else {

            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');

            if ($pdf_font_regular != false) {
                $defaultConfig = (new ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                $pdf = new Mpdf([
                    'fontDir' => array_merge($fontDirs, [
                        app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                                DIRECTORY_SEPARATOR.'pdf'.
                                                DIRECTORY_SEPARATOR.$base_template.
                                                DIRECTORY_SEPARATOR.'font')
                    ]),
                    'fontdata' => $fontData + [
                        'custom_bold' => [
                            'R' => $pdf_font_bold.'.ttf',
                        ],
                        'custom_regular' => [
                            'R' => $pdf_font_regular.'.ttf',
                        ],
                    ]
                ]);
            }

        }

        $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                             DIRECTORY_SEPARATOR.'pdf'.
                                             DIRECTORY_SEPARATOR.$base_template.
                                             DIRECTORY_SEPARATOR.'style.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        /*if(config('tenant.pdf_template_footer')) {
            $html_footer = $template->pdfFooter($base_template);
            $pdf->SetHTMLFooter($html_footer);
        }*/

        $this->uploadFile($this->document->filename, $pdf->output('', 'S'), 'sale_note');
    }

    public function uploadFile($filename, $file_content, $file_type)
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }



    public function table($table)
    {
        switch ($table) {
            case 'taxes':

                return Tax::all()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'name' => $row->name,
                        'code' => $row->code,
                        'rate' =>  $row->rate,
                        'conversion' =>  $row->conversion,
                        'is_percentage' =>  $row->is_percentage,
                        'is_fixed_value' =>  $row->is_fixed_value,
                        'is_retention' =>  $row->is_retention,
                        'in_base' =>  $row->in_base,
                        'in_tax' =>  $row->in_tax,
                        'type_tax_id' =>  $row->type_tax_id,
                        'type_tax' =>  $row->type_tax,
                        'retention' =>  0,
                        'total' =>  0,
                    ];
                });
                break;

            case 'customers':

                $customers = Person::whereType('customers')->whereIsEnabled()->orderBy('name')->take(20)->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'address' =>  $row->address,
                        'email' =>  $row->email,
                        'telephone' =>  $row->telephone,
                    ];
                });
                return $customers;

                break;

            case 'items':

                $establishment_id = auth()->user()->establishment_id;
                $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
                $warehouse_id = ($warehouse) ? $warehouse->id:null;

                $items_u = Item::whereWarehouse()->whereIsActive()->whereNotIsSet()->orderBy('description')->get();

                $items_s = Item::where('unit_type_id','ZZ')->whereIsActive()->orderBy('description')->get();

                $items = $items_u->merge($items_s);

                return collect($items)->transform(function($row) use($warehouse_id, $warehouse){
                    $detail = $this->getFullDescription($row, $warehouse);
                    return [
                        'id' => $row->id,
                        'internal_id' => $row->internal_id,
                        'name' => $row->name,
                        'description' => $row->description,
                        'full_description' => $detail['full_description'],
                        'brand' => $detail['brand'],
                        'category' => $detail['category'],
                        'stock' => $detail['stock'],
                        'currency_type_id' => $row->currency_type_id,
                        'currency_type_symbol' => $row->currency_type->symbol,
                        'sale_unit_price' => round($row->sale_unit_price, 2),
                        'purchase_unit_price' => $row->purchase_unit_price,
                        'unit_type_id' => $row->unit_type_id,
                        'tax_id' => $row->tax_id,
                        'lots_enabled' => (bool) $row->lots_enabled,
                        'series_enabled' => (bool) $row->series_enabled,
                        'is_set' => (bool) $row->is_set,
                        'warehouses' => collect($row->warehouses)->transform(function($row) {
                            return [
                                'warehouse_id' => $row->warehouse->id,
                                'warehouse_description' => $row->warehouse->description,
                                'stock' => $row->stock,
                            ];
                        }),
                        'item_unit_types' => collect($row->item_unit_types)->transform(function($row) {
                            return [
                                'id' => $row->id,
                                'description' => "{$row->description}",
                                'item_id' => $row->item_id,
                                'unit_type_id' => $row->unit_type_id,
                                'unit_type' => $row->unit_type,
                                'quantity_unit' => $row->quantity_unit,
                                'price1' => $row->price1,
                                'price2' => $row->price2,
                                'price3' => $row->price3,
                                'price_default' => $row->price_default,
                            ];
                        }),
                        'lots' => $row->item_lots->where('has_sale', false)->where('warehouse_id', $warehouse_id)->transform(function($row) {
                            return [
                                'id' => $row->id,
                                'series' => $row->series,
                                'date' => $row->date,
                                'item_id' => $row->item_id,
                                'warehouse_id' => $row->warehouse_id,
                                'has_sale' => (bool)$row->has_sale,
                                'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code:null):null
                            ];
                        }),
                        'lots_group' => collect($row->lots_group)->transform(function($row){
                            return [
                                'id'  => $row->id,
                                'code' => $row->code,
                                'quantity' => $row->quantity,
                                'date_of_due' => $row->date_of_due,
                                'checked'  => false
                            ];
                        }),
                        'lot_code' => $row->lot_code,
                        'date_of_due' => $row->date_of_due,
                        'unit_type' => $row->unit_type,
                        'tax' => $row->tax,
                    ];
                });


                break;
            default:

                return [];

                break;
        }
    }


    public function getFullDescription($row, $warehouse){

        $desc = ($row->internal_id)?$row->internal_id.' - '.$row->name : $row->name;
        $category = ($row->category) ? "{$row->category->name}" : "";
        $brand = ($row->brand) ? "{$row->brand->name}" : "";

        if($row->unit_type_id != 'ZZ')
        {
            $warehouse_stock = ($row->warehouses && $warehouse) ? number_format($row->warehouses->where('warehouse_id', $warehouse->id)->first()->stock,2) : 0;
            $stock = ($row->warehouses && $warehouse) ? "{$warehouse_stock}" : "";
        }
        else{
            $stock = '';
        }


        $desc = "{$desc} - {$brand}";

        return [
            'full_description' => $desc,
            'brand' => $brand,
            'category' => $category,
            'stock' => $stock,
        ];
    }


    public function searchCustomerById($id)
    {

        $customers = Person::whereType('customers')
                    ->where('id',$id)
                    ->get()->transform(function($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number.' - '.$row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'address' =>  $row->address,
                            'email' =>  $row->email,
                            'telephone' =>  $row->telephone,
                        ];
                    });

        return compact('customers');
    }

    public function option_tables()
    {
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $series = Series::where('establishment_id',$establishment->id)->get();

        $type_documents = TypeDocument::query()
                            ->get()
                            ->each(function($typeDocument) {
                                $typeDocument->alert_range = (($typeDocument->to - 100) < (Document::query()
                                    ->hasPrefix($typeDocument->prefix)
                                    ->whereBetween('number', [$typeDocument->from, $typeDocument->to])
                                    ->max('number') ?? $typeDocument->from));

                                $typeDocument->alert_date = ($typeDocument->resolution_date_end == null) ? false : Carbon::parse($typeDocument->resolution_date_end)->subMonth(1)->lt(Carbon::now());
                            });

        return compact('series', 'type_documents');
    }

    public function email(Request $request)
    {
        $company = Company::active();
        $record = DocumentPos::find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new SaleNoteEmail($company, $record));

        return [
            'success' => true
        ];
    }


    public function dispatches()
    {
        $dispatches = Dispatch::latest()->get(['id','series','number'])->transform(function($row) {
            return [
                'id' => $row->id,
                'series' => $row->series,
                'number' => $row->number,
                'number_full' => "{$row->series}-{$row->number}",
            ];
        }); ;

        return $dispatches;
    }

    public function enabledConcurrency(Request $request)
    {

        $sale_note = DocumentPos::findOrFail($request->id);
        $sale_note->enabled_concurrency = $request->enabled_concurrency;
        $sale_note->update();

        return [
            'success' => true,
            'message' => ($sale_note->enabled_concurrency) ? 'Recurrencia activada':'Recurrencia desactivada'
        ];

    }

    public function anulate($id)
    {

        DB::connection('tenant')->transaction(function () use ($id) {

            $obj =  DocumentPos::find($id);
            $obj->state_type_id = 11;
            $obj->save();

            $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
            $warehouse = Warehouse::where('establishment_id',$establishment->id)->first();

            foreach ($obj->items as $item) {

                $quantity = $item->quantity;
                if($item->refund == 1)
                {
                    $quantity = -($item->quantity);
                }

                $item->document_pos->inventory_kardex()->create([
                    'date_of_issue' => date('Y-m-d'),
                    'item_id' => $item->item_id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $quantity //* ($item->refund ? -1 : 1),
                ]);

                $wr = ItemWarehouse::where([['item_id', $item->item_id],['warehouse_id', $warehouse->id]])->first();

                if($wr)
                {
                    $wr->stock =  $wr->stock + $quantity; // * ($item->refund ? -1 : 1));
                    $wr->save();
                }

                //habilito las series
                // ItemLot::where('item_id', $item->item_id )->where('warehouse_id', $warehouse->id)->update(['has_sale' => false]);
                $this->voidedLots($item);

            }

        });

        return [
            'success' => true,
            'message' => 'N. Venta anulada con éxito'
        ];


    }



    public function totals()
    {

        $records = DocumentPos::where([['state_type_id', '01']])->get();
        $total_pen = 0;
        $total_paid_pen = 0;
        $total_pending_paid_pen = 0;


        $total_pen = $records->sum('total');

        foreach ($records as $sale_note) {

            $total_paid_pen += $sale_note->payments->sum('payment');

        }

        $total_pending_paid_pen = $total_pen - $total_paid_pen;

        return [
            'total_pen' => number_format($total_pen, 2, ".", ""),
            'total_paid_pen' => number_format($total_paid_pen, 2, ".", ""),
            'total_pending_paid_pen' => number_format($total_pending_paid_pen, 2, ".", "")
        ];

    }

    public function downloadExternal($external_id)
    {
        $document = DocumentPos::where('external_id', $external_id)->first();
        $this->reloadPDF($document, 'ticket', null);
        return $this->downloadStorage($document->filename, 'sale_note');

    }


    private function savePayments($sale_note, $payments){

        $total = $sale_note->total;
        $balance = $total - collect($payments)->sum('payment');

        $search_cash = ($balance < 0) ? collect($payments)->firstWhere('payment_method_type_id', '01') : null;

        $this->apply_change = false;

        if($balance < 0 && $search_cash){

            $payments = collect($payments)->map(function($row) use($balance){

                $change = null;
                $payment = $row['payment'];

                if($row['payment_method_type_id'] == '01' && !$this->apply_change){

                    $change = abs($balance);
                    $payment = $row['payment'] - abs($balance);
                    $this->apply_change = true;

                }

                return [
                    "id" => null,
                    "document_id" => null,
                    "document_pos_id" => null,
                    "date_of_payment" => $row['date_of_payment'],
                    "payment_method_type_id" => $row['payment_method_type_id'],
                    "reference" => $row['reference'],
                    "payment_destination_id" => isset($row['payment_destination_id']) ? $row['payment_destination_id'] : null,
                    "payment_filename" => isset($row['payment_filename']) ? $row['payment_filename'] : null,
                    "change" => $change,
                    "payment" => $payment
                ];

            });
        }

        // dd($payments, $balance, $this->apply_change);

        foreach ($payments as $row) {

            if($balance < 0 && !$this->apply_change){
                $row['change'] = abs($balance);
                $row['payment'] = $row['payment'] - abs($balance);
                $this->apply_change = true;
            }

            $record_payment = $sale_note->payments()->create($row);

            if(isset($row['payment_destination_id'])){
                $this->createGlobalPayment($record_payment, $row);
            }

            if(isset($row['payment_filename'])){
                $record_payment->payment_file()->create([
                    'filename' => $row['payment_filename']
                ]);
            }

        }
    }


    private function voidedLots($item){

        $i_lots_group = isset($item->item->lots_group) ? $item->item->lots_group:[];

        $lot_group_selected = collect($i_lots_group)->first(function($row){
            return $row->checked;
        });

        if($lot_group_selected){

            $lot = ItemLotsGroup::find($lot_group_selected->id);
            $lot->quantity =  $lot->quantity + $item->quantity;
            $lot->save();

        }

        if(isset($item->item->lots)){

            foreach ($item->item->lots as $it) {

                if($it->has_sale == true){

                    $ilt = ItemLot::find($it->id);
                    $ilt->has_sale = false;
                    $ilt->save();

                }

            }
        }

    }

}
