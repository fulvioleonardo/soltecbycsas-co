<?php
namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sale\Http\Resources\RemissionPaymentCollection;
use App\Models\Tenant\PaymentMethodType;
use Exception, Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Finance\Traits\FinanceTrait; 
use Modules\Sale\Models\{
    Remission,
    RemissionPayment
};
use Modules\Sale\Http\Requests\RemissionPaymentRequest;


class RemissionPaymentController extends Controller
{

    use FinanceTrait;

    public function records($remission_id)
    {
        $records = RemissionPayment::where('remission_id', $remission_id)->get();

        return new RemissionPaymentCollection($records);
    }

    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::all(),
            'payment_destinations' => $this->getPaymentDestinations()
        ];
    }

    public function remission($remission_id)
    {
        $remission = Remission::find($remission_id);

        $total_paid = collect($remission->payments)->sum('payment');
        $total = $remission->total;
        $total_difference = round($total - $total_paid, 2);

        return [
            'number_full' => $remission->number_full,
            'total_paid' => $total_paid,
            'total' => $total,
            'total_difference' => $total_difference
        ];

    }

    public function store(RemissionPaymentRequest $request)
    {

        $id = $request->input('id');

        DB::connection('tenant')->transaction(function () use ($id, $request) {

            $record = RemissionPayment::firstOrNew(['id' => $id]);
            $record->fill($request->all());
            $record->save();
            $this->createGlobalPayment($record, $request->all());

        });

        return [
            'success' => true,
            'message' => ($id)?'Pago editado con éxito':'Pago registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = RemissionPayment::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }
 
}
