<?php
namespace App\Http\Controllers\Tenant;

use App\Imports\ItemsImport;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\User;
use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CashRequest;
use App\Http\Resources\Tenant\CashCollection;
use App\Http\Resources\Tenant\CashResource;
use Modules\Item\Models\Category; //se agrega un nuevo Modelo
use App\Models\Tenant\Cash;
use App\Models\Tenant\CashDocument;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\ConfigurationPos;


class CashController extends Controller
{
    public function index()
    {
        return view('tenant.cash.index');
    }

    public function columns()
    {
        return [
            'income' => 'Ingresos',
            // 'expense' => 'Egresos',
        ];
    }

    public function records(Request $request)
    {
        $records = Cash::where($request->column, 'like', "%{$request->value}%")
                        ->whereTypeUser()
                        ->orderBy('date_opening');


        return new CashCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $user = auth()->user();
        $type = $user->type;
        $users = array();

        switch($type)
        {
            case 'admin':
                $users = User::where('type', 'seller')->get();
                $users->push($user);
                break;
            case 'seller':
                $users = User::where('id', $user->id)->get();
                break;
        }

        $resolutions = ConfigurationPos::select('id', 'prefix', 'resolution_number')->get();

        return compact('users', 'user', 'resolutions');
    }

    public function opening_cash()
    {

        $cash = Cash::where([['user_id', auth()->user()->id],['state', true]])->first();

        return compact('cash');
    }

    public function opening_cash_check($user_id)
    {
        $cash = Cash::where([['user_id', $user_id],['state', true]])->first();
        return compact('cash');
    }


    public function record($id)
    {
        $record = new CashResource(Cash::findOrFail($id));

        return $record;
    }

    public function store(CashRequest $request) {

        $id = $request->input('id');
        $cash = Cash::firstOrNew(['id' => $id]);
        $cash->fill($request->all());

        if(!$id){
            $cash->date_opening = date('Y-m-d');
            $cash->time_opening = date('H:i:s');
        }

        $cash->save();

        return [
            'success' => true,
            'message' => ($id)?'Caja actualizada con éxito':'Caja aperturada con éxito'
        ];

    }

    public function close($id) {

        $cash = Cash::findOrFail($id);

        $cash->date_closed = date('Y-m-d');
        $cash->time_closed = date('H:i:s');

        $final_balance = $cash->getSumCashFinalBalance();
        $cash->final_balance = round($final_balance + $cash->beginning_balance, 2);
        $cash->income = round($final_balance, 2);
        $cash->state = false;
        $cash->save();

        return [
            'success' => true,
            'message' => 'Caja cerrada con éxito',
        ];

        // $final_balance = 0;

        // foreach ($cash->cash_documents as $cash_document) {


        //     if($cash_document->sale_note){

        //         // $final_balance += ($cash_document->sale_note->currency_type_id == 'PEN') ? $cash_document->sale_note->total : ($cash_document->sale_note->total * $cash_document->sale_note->exchange_rate_sale);

        //         $final_balance += $cash_document->sale_note->total;

        //     }
        //     else if($cash_document->document){

        //         // $final_balance += ($cash_document->document->currency_type_id == 'PEN') ? $cash_document->document->total : ($cash_document->document->total * $cash_document->document->exchange_rate_sale);

        //         $final_balance += $cash_document->document->total;

        //     }
        //     else if($cash_document->expense_payment){

        //         // $final_balance -= ($cash_document->expense_payment->expense->currency_type_id == 'PEN') ? $cash_document->expense_payment->payment:($cash_document->expense_payment->payment  * $cash_document->expense_payment->expense->exchange_rate_sale);
        //         $final_balance -= $cash_document->expense_payment->payment;

        //     }

        // }

    }


    public function cash_document(Request $request) {

        $cash = Cash::where([['user_id',auth()->user()->id],['state',true]])->first();
        $cash->cash_documents()->create($request->all());

        return [
            'success' => true,
            'message' => 'Venta con éxito',
        ];
    }


    public function destroy($id)
    {
        $cash = Cash::findOrFail($id);

        if($cash->global_destination->count() > 0){
            return [
                'success' => false,
                'message' => 'No puede eliminar la caja, tiene transacciones relacionadas'
            ];
        }

        $cash->delete();

        return [
            'success' => true,
            'message' => 'Caja eliminada con éxito'
        ];
    }
    //Se modifica la funcion report()
    public function report($cashId, $only_head = null) {
        $cash = Cash::findOrFail($cashId);
        $company = Company::first();

        // Se Calcula $cashEgress, similar al de la funcion que estaba.
        $cashEgress = $cash->cash_documents->sum(function ($cashDocument) {
            return $cashDocument->expense_payment ? $cashDocument->expense_payment->payment : 0;
        });

        // Se Recupera $expensePayments, similar como estaba.
        $expensePayments = $cash->cash_documents->filter(function ($doc) {
            return !is_null($doc->expense_payment_id);
        })->map->expense_payment;

        // Inicialización de $methods_payment.
        $methods_payment = PaymentMethodType::all()->map(function($row) {
            return (object)[
                'id' => $row->id,
                'name' => $row->description,
                'sum' => 0
            ];
        });

        // Se recuperan las categorías
        $categories = Category::all()->pluck('name', 'id');

        // Se Recupera la Resolución
        $resolutions_maquinas = ConfigurationPos::select('cash_type', 'plate_number', 'electronic')->get();

        set_time_limit(0); // Aumentar el tiempo de ejecución si los reportes son grandes.

        // Se Pasan todas las variables necesarias a la vista.
        $pdf = PDF::loadView('tenant.cash.report_pdf', compact("cash", "company", "methods_payment", "cashEgress", "categories", "resolutions_maquinas", "expensePayments", "only_head"));

        $filename = "Reporte_POS - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";

        return $pdf->stream($filename . '.pdf');
    }

    public function report_general()
    {

        $cashes = Cash::select('id')->whereDate('date_opening', date('Y-m-d'))->pluck('id');
        $cash_documents =  CashDocument::with('document_pos')->whereNotNull('document_pos_id')->whereIn('cash_id', $cashes)->get();

        $company = Company::first();
        set_time_limit(0);

        $pdf = PDF::loadView('tenant.cash.report_general_pdf', compact("cash_documents", "company"));
        $filename = "Reporte_POS";
        return $pdf->download($filename.'.pdf');

    }

    public function report_products($id)
    {
        $cash = Cash::findOrFail($id);
        $company = Company::first();
        $cash_documents =  CashDocument::select('document_id')->where('cash_id', $cash->id)->get();

        $source = DocumentItem::with('document')->whereIn('document_id', $cash_documents)->get();

        $documents = collect($source)->transform(function($row){
            return [
                'id' => $row->id,
                'number_full' => $row->document->number_full,
                'description' => $row->item->description,
                'quantity' => $row->quantity,
            ];
        });


        $pdf = PDF::loadView('tenant.cash.report_product_pdf', compact("cash", "company", "documents"));

        $filename = "Reporte_POS_PRODUCTOS - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";

        return $pdf->stream($filename.'.pdf');



    }



}
