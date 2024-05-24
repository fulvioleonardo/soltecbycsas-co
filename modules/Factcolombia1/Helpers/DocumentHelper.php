<?php

namespace Modules\Factcolombia1\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\Person;
use App\Models\Tenant\Item;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use Illuminate\Support\Str;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Factcolombia1\Models\Tenant\{
    Tax,
};
use Modules\Finance\Traits\FinanceTrait;
use Exception;


class DocumentHelper
{
    use FinanceTrait;

    protected $apply_change;

    public static function createDocument($request, $nextConsecutive, $correlative_api, $company, $response, $response_status, $type_environment_id)
    {
//\Log::debug("1");
        $establishment = EstablishmentInput::set(auth()->user()->establishment_id);
        $shipping_two_steps = ($type_environment_id == 2);
        $document = new Document();
        $document->prefix = $nextConsecutive->prefix;
        $document->number = $correlative_api;
        $document->user_id = auth()->id();
        $document->external_id = Str::uuid()->toString();
        $document->establishment_id = auth()->user()->establishment_id;
        $document->establishment = $establishment;
        $document->soap_type_id = Company::active()->soap_type_id;
//        $document->calculationrate = $request->calculationrate ? $request->calculationrate : 0;
//        $document->calculationratedate = $request->calculationratedate ? $request->calculationratedate : Carbon::parse("1900-01-01")->format('Y-m-d');
//        $document->incoterm_id = $request->incoterm_id ? $request->incoterm_id : null;
//\Log::debug("2");
        $document->send_server = false;
        $document->type_environment_id = $type_environment_id;
        $document->shipping_two_steps = $shipping_two_steps;
        $document->type_document_id = $request->type_document_id;
        $document->type_invoice_id = $request->type_invoice_id;
        $document->customer_id = $request->customer_id;
        $document->customer = Person::with('typePerson', 'typeRegime', 'identity_document_type', 'country', 'department', 'city')->findOrFail($request->customer_id);
        $document->currency_id = $request->currency_id;
        $document->date_expiration = $request->date_expiration ? Carbon::parse("{$request->date_expiration}") : Carbon::parse($request->date_issue)->format('Y-m-d');
        $document->date_of_issue = Carbon::parse($request->date_issue)->format('Y-m-d');
        $document->time_of_issue = Carbon::now()->format('H:i:s');
        $document->observation = $request->observation;
        $document->reference_id = $request->reference_id;
        $document->note_concept_id = $request->note_concept_id;
        $document->sale = $request->sale;
        $document->total_discount = $request->total_discount;
//\Log::debug("3");
        $document->taxes = $request->taxes;
        $document->total_tax = $request->total_tax;
        $document->subtotal = $request->subtotal;
        $document->total = $request->total;
        $document->version_ubl_id = $company->version_ubl_id;
        $document->ambient_id = $company->ambient_id;
        $document->payment_form_id = $request->payment_form_id;
        $document->payment_method_id = $request->payment_method_id;
        $document->time_days_credit = $request->time_days_credit;
        $document->response_api = $response;
        $document->response_api_status = $response_status;
        $document->correlative_api = $correlative_api;
        $document->sale_note_id = $request->sale_note_id;
        $document->quotation_id = $request->quotation_id;
//\Log::debug("4");
        $document->xml = $request->xml;
        $document->cufe = $request->cufe;
//\Log::debug("4.1");
        $document->order_reference = self::getOrderReference($request);
//\Log::debug("4.2");
        $document->health_fields = self::getHealthfields($request);
//\Log::debug("4.3");
//\Log::debug($request);

//\Log::debug(json_encode($document));
        try{
            $document->save();
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
        }
//\Log::debug("4.4");
        $existen_items = $document->items;
        $existen_items->each->delete();
//\Log::debug("5");
//\Log::debug($request->items);
        foreach ($request->items as $item) {
            $exist_record = false;
            $record_item = Item::find((key_exists('item_id', $item)) ? $item['item_id'] : 0);
//\Log::debug($record_item);
            if($record_item === null){
//                \Log::debug(1);
                $exist_record = false;
                $record_item = Item::where('internal_id', $item['code'])->get();
                if(count($record_item) == 0){
//                    \Log::debug(11);
                    $exist_record = false;
                }
                else{
//                    \Log::debug(12);
                    $exist_record = true;
                    $record_item = Item::where('internal_id', $item['code'])->firstOrFail();
                }
            }
            else{
//                \Log::debug(2);
                $exist_record = true;
            }
//            \Log::debug(json_encode($exist_record));
//            \Log::debug($item);
            if(!$exist_record){
                $record_item = new Item();
                $record_item->name = (key_exists('description', $item)) ? $item['description'] : $item['item']['name'];
//                \Log::debug("A");
                $record_item->second_name = (key_exists('description', $item)) ? $item['description'] : $item['item']['name'];
                $record_item->description = (key_exists('description', $item)) ? $item['description'] : $item['item']['name'];
                $record_item->item_type_id = "01";
                $record_item->internal_id = $item['code'];
                $record_item->tax_id = 8;
                $record_item->purchase_tax_id = 8;
                $record_item->unit_type_id = 10;
                $record_item->currency_type_id = 170;
                $record_item->sale_unit_price = (key_exists('price_amount', $item)) ? $item['price_amount'] : $item['item']['sale_unit_price'];
//                \Log::debug("B");
                $record_item->amount_plastic_bag_taxes = 0.1;
                $record_item->is_set = 0;
                $record_item->model = $item['code'];
                $record_item->image = "imagen-no-disponible.jpg";
                $record_item->image_medium = "imagen-no-disponible.jpg";
                $record_item->image_small = "imagen-no-disponible.jpg";
                $record_item->stock = 0;
                $record_item->stock_min = 1;
                $record_item->percentage_perception = 0;
                $record_item->active = 1;
                $record_item->status = 1;
//                \Log::debug("C");
//                \Log::debug($record_item);
                $record_item->save();
                $item['item_id'] = $record_item->id;
//                \Log::debug("D");
//                \Log::debug($item);
//                $record_item = Item::where('internal_id', $item['code'])->firstOrFail();
            }
//            \Log::debug($item);
//            $record_item = Item::find($item['item_id']);
//            \Log::debug($record_item);
            $json_item = [
                'name' => $record_item->name,
                'description' => $record_item->description,
                'internal_id' => $record_item->internal_id,
                'unit_type' => (key_exists('item', $item)) ? $item['item']['unit_type'] : $record_item->unit_type,
                'unit_type_id' => (key_exists('item', $item)) ? $item['item']['unit_type_id'] : $record_item->unit_type_id,
                'presentation' => (key_exists('item', $item)) ? (isset($item['item']['presentation']) ? $item['item']['presentation'] : []) : [],
                'amount_plastic_bag_taxes' => $record_item->amount_plastic_bag_taxes ? $record_item->amount_plastic_bag_taxes : 0,
                'is_set' => $record_item->is_set,
                'lots' => (isset($item['item']['lots'])) ? $item['item']['lots'] : [],
                'IdLoteSelected' => (isset($item['IdLoteSelected']) ? $item['IdLoteSelected'] : null)
            ];
//            \Log::debug($record_item);
//            \Log::debug($item);
//            \Log::debug($json_item);
//            \Log::debug(array_merge($item, $json_item));
//\Log::debug($item);
//\Log::debug($record_item);
            $document->items()->create([
                'document_id' => $document->id,
                'item_id' => key_exists('item_id', $item) ? $item['item_id'] : $record_item->id,
                'item' => array_merge($item, $json_item),
                'unit_type_id' => (key_exists('item', $item)) ? $item['item']['unit_type_id'] : $record_item->unit_type_id,
                'quantity' => floatval((key_exists('quantity', $item)) ? $item['quantity'] : $item['invoiced_quantity']),
                'unit_price' => floatval(isset($item['price']) ? $item['price'] : $record_item->sale_unit_price),
                'tax_id' => isset($item['tax_id']) ? $item['tax_id'] : $record_item->tax_id,
                'tax' => Tax::find(isset($item['tax_id']) ? $item['tax_id'] : $record_item->tax_id),
                'total_tax' => isset($item['total_tax']) ? $item['total_tax'] : $item['price_amount'] - $item['line_extension_amount'],
                'subtotal' => floatval(isset($item['subtotal']) ? $item['subtotal'] : $item['line_extension_amount']),
                'discount' => isset($item['discount']) ? $item['discount'] : 0,
                'total' => isset($item['total']) ? $item['total'] : (isset($item['subtotal']) ? $item['subtotal'] : $item['line_extension_amount']) + (isset($item['total_tax']) ? $item['total_tax'] : $item['price_amount'] - $item['line_extension_amount']),
                'total_plastic_bag_taxes' => 0,
                'warehouse_id' => null,
            ]);
//            \Log::debug("E");
//\Log::debug("7");
        }
//\Log::debug("8");
        return $document;
    }

    public function savePayments($document, $payments){

        if($payments){

            $total = $document->total;
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
                        "sale_note_id" => null,
                        "date_of_payment" => $row['date_of_payment'],
                        "payment_method_type_id" => $row['payment_method_type_id'],
                        "reference" => $row['reference'],
                        "payment_destination_id" => isset($row['payment_destination_id']) ? $row['payment_destination_id'] : null,
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

                $record = $document->payments()->create($row);

                //considerar la creacion de una caja chica cuando recien se crea el cliente
                if(isset($row['payment_destination_id'])){
                    $this->createGlobalPayment($record, $row);
                }

            }
        }
    }

    public static function getOrderReference($request)
    {
        $order_reference = null;

        if ($request->order_reference)
        {
            if (isset($request['order_reference']['issue_date_order']) && isset($request['order_reference']['id_order']))
            {
                $order_reference = [
                    'id_order' => $request['order_reference']['id_order'],
                    'issue_date_order' => $request['order_reference']['issue_date_order'],
                ];
            }
        }
        return $order_reference;
    }

    public static function getHealthFields($request)
    {
        $health_fields = null;
        if ($request->health_fields)
        {
            if (isset($request->health_fields['invoice_period_start_date']) && isset($request->health_fields['invoice_period_end_date']))
            {
                $health_fields = [
                    'invoice_period_start_date' => $request->health_fields['invoice_period_start_date'],
                    'invoice_period_end_date' => $request->health_fields['invoice_period_end_date'],
                    'health_type_operation_id' => 1,
                    'users_info' => $request->health_users,
                ];
            }
        }
        return $health_fields;
    }

    /**
     * Genera un arreglo con la data necesaria para insertar en el detalle del documento
     *
     * Usado en:
     * RemissionController
     *
     * @param  array $inputs
     * @return array
    */
    public static function getDataItemFromInputs($inputs)
    {

        $items = [];

        foreach ($inputs['items'] as $item) {

            $json_item = [
                'name' => $item['item']['name'],
                'description' => $item['item']['description'],
                'internal_id' => $item['item']['internal_id'],

                'unit_type' => $item['item']['unit_type'],
                'unit_type_id' => $item['item']['unit_type_id'],
                'presentation' => (key_exists('item', $item)) ? (isset($item['item']['presentation']) ? $item['item']['presentation']:[]):[],

                'is_set' => $item['item']['is_set'],
                'lots' => (isset($item['item']['lots'])) ? $item['item']['lots']:[],
                'IdLoteSelected' => ( isset($item['IdLoteSelected']) ? $item['IdLoteSelected'] : null )
            ];

            $items [] = [
                'item_id' => $item['item_id'],
                // 'item' => array_merge($item, $json_item),
                'item' => $json_item,
                'unit_type_id' => $item['unit_type_id'],
                'quantity' => $item['quantity'],
                'unit_price' => isset($item['price']) ? $item['price'] : $item['unit_price'],
                'tax_id' => $item['tax_id'],
                'tax' => Tax::find($item['tax_id']),
                'total_tax' => $item['total_tax'],
                'subtotal' => $item['subtotal'],
                'discount' => $item['discount'],
                'total' => $item['total']
            ];

        }

        return $items;
    }


    /**
     *
     * Actualizar mensaje de respuesta al consultar zipkey
     *
     * @param  string $response_message_query_zipkey
     * @param  Document $document
     * @return void
     */
    public function updateMessageQueryZipkey($response_message_query_zipkey, Document $document)
    {
        $document->update([
            'response_message_query_zipkey' => $response_message_query_zipkey
        ]);
    }

    /**
     *
     * Actualizar estado dependiendo de la validación al enviar a la dian
     *
     * @param  int $state_document_id
     * @param  Document $document
     * @return void
     */
    public function updateStateDocument($state_document_id, Document $document)
    {
        $document->update([
            'state_document_id' => $state_document_id
        ]);
    }

    /**
     *
     * @param  bool $success
     * @param  string $message
     * @return array
     */
    public function responseMessage($success, $message)
    {
        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function throwException($message)
    {
        throw new Exception($message);
    }

    /**
     *
     * Aplicar formato
     *
     * @param  $value
     * @param  int $decimals
     * @return string
     */
    public static function applyNumberFormat($value, $decimals = 2)
    {
        return number_format($value, $decimals, ".", "");
    }

}
