<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Item;
use App\Http\Resources\Tenant\ItemCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\User;
use App\Models\Tenant\Person;
use Illuminate\Support\Str;
use App\Models\Tenant\Order;
use App\Models\Tenant\ItemsRating;
use App\Models\Tenant\ConfigurationEcommerce;
use Modules\Ecommerce\Http\Resources\ItemBarCollection;
use stdClass;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tenant\CulqiEmail;
use App\Http\Controllers\Tenant\Api\ServiceController;
use Illuminate\Support\Facades\Validator;
use App\Models\Tenant\Document;
use Modules\Factcolombia1\Models\Tenant\{
    Tax,
};
use App\Models\Tenant\PaymentMethodType;


class EcommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct(){
        return view()->share('records', Item::where('apply_store', 1)->orderBy('id', 'DESC')->take(2)->get());
    }

    public function index()
    {
      $dataPaginate['dataPaginate'] = Item::where([['apply_store', 1], ['internal_id','!=', null]])->paginate(15);
      return view('ecommerce::index', $dataPaginate);
    }

    public function category(Request $request)
    {
      $dataPaginate['dataPaginate'] = Item::select('i.*')
        ->where([['i.apply_store', 1], ['i.internal_id','!=', null], ['it.tag_id', $request->category]])
        ->from('items as i')
        ->join('item_tags as it', 'it.item_id','i.id')->paginate(15);
      return view('ecommerce::index', $dataPaginate);
    }

    public function item($id)
    {
        $row = Item::find($id);
        $sale_unit_price = $row->sale_unit_price;

        $record = (object)[
            'id' => $row->id,
            'internal_id' => $row->internal_id,
            'unit_type_id' => $row->unit_type_id,
            'description' => $row->description,
            'name' => $row->name,
            'second_name' => $row->second_name,
            'sale_unit_price' => $sale_unit_price,
            'currency_type_id' => $row->currency_type_id,
            // 'sale_unit_price' => $row->sale_unit_price,
            'tax_id' => $row->tax_id,
            'currency_type_symbol' => $row->currency_type->symbol,
            'image' =>  $row->image,
            'image_medium' => $row->image_medium,
            'image_small' => $row->image_small,
            'tags' => $row->tags->pluck('tag_id')->toArray(),
            'images' => $row->images,
            'attributes' => $row->attributes ? $row->attributes : []
        ];

        return view('ecommerce::items.record', compact('record'));
    }

    public function items()
    {
        $records = Item::where('apply_store', 1)->get();
        return view('ecommerce::items.index', compact('records'));
    }

    public function itemsBar()
    {
        $records = Item::where('apply_store', 1)->get();
        // return new ItemCollection($records);
        return new ItemBarCollection($records);

    }

    public function partialItem($id)
    {
        $record = Item::find($id);
        return view('ecommerce::items.partial', compact('record'));
    }

    public function detailCart()
    {
        $configuration = ConfigurationEcommerce::first();
        return view('ecommerce::cart.detail', compact('configuration'));
    }

    public function pay()
    {
        return view('ecommerce::cart.pay');
    }

    public function showLogin()
    {
        return view('ecommerce::user.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
           return[
               'success' => true,
               'message' => 'Login Success'
           ];
        }
        else{
            return[
                'success' => false,
                'message' => 'Usuario o Password incorrectos'
            ];
        }

    }

    public function logout()
    {
        Auth::logout();
        return[
            'success' => true,
            'message' => 'Logout Success'
        ];
    }

    public function storeUser(Request $request)
    {
        try{

            $verify = User::where('email', $request->email)->first();
            if($verify)
            {
                return [
                    'success' => false,
                    'message' => 'Email no disponible'
                ];
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->establishment_id = 1;
            $user->type = 'client';
            $user->api_token = str_random(50);
            $user->password = bcrypt($request->pswd);
            $user->save();
            $user->modules()->sync([10]);

            $credentials = [ 'email' => $user->email, 'password' => $request->pswd ];
            Auth::attempt($credentials);
            return [
                'success' => true,
                'message' => 'Usuario registrado'
            ];
        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
        }

    }

    public function transactionFinally(Request $request)
    {
        try{
            $user = auth()->user();
            //1. confirmar dato de compriante en order
            $order_generated = Order::find($request->orderId);
            $order_generated->document_external_id = $request->document_external_id;
            $order_generated->number_document = $request->number_document;
            $order_generated->save();

            $user->update(['identity_document_type_id' => $request->identity_document_type_id, 'number'=>$request->number]);
            return [
                'success' => true,
                'message' => 'Order Actualizada',
                'order_total' => $order_generated->total
            ];
        }
        catch(Exception $e)
        {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
        }

    }

    public function transactionFinally2(Request $request)
    {
        try{
            $user = auth()->user();

            $document = Document::find($request->documentId);

            //1. confirmar dato de compriante en order
            $order_generated = Order::find($request->orderId);
            $order_generated->document_external_id = $document->external_id;
            $order_generated->number_document = $document->prefix."-".$document->number;
            $order_generated->status_order_id = $request->status_order_id;
            $order_generated->save();

            return [
                'success' => true,
                'message' => 'Order Actualizada',
                'order_total' => $order_generated->total
            ];
        }
        catch(Exception $e)
        {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
        }

    }

    public function paymentCash(Request $request)
    {
        $validator = Validator::make($request->customer, [
            'identification_number' => 'required|numeric',
            'telephone' => 'required|numeric',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $payment_method_type_id = $request->payment_method_type_id ?? null;

        try {

            $user = auth()->user();
            $order = Order::create([
                'external_id' => Str::uuid()->toString(),
                'customer' =>  $request->customer,
                'shipping_address' => 'direccion 1',
                'items' =>  $request->items,
                'total' => $request->precio_culqi,
                // 'reference_payment' => 'efectivo',
                'reference_payment' => is_null($payment_method_type_id) ? 'efectivo' : null,
                'status_order_id' => 1,
                'payment_method_type_id' => $payment_method_type_id,
            ]);

            $customer_email = $user->email;
            $document = new stdClass;
            $document->client = $user->name;
            $document->product = $request->producto;
            $document->total = $request->precio_culqi;
            $document->items = $request->items;

//            Mail::to($customer_email)->send(new CulqiEmail($document));
            return [
                'success' => true,
                'order' => $order
            ];

        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' =>  $e->getMessage()
            ];
        }
      }


    public function ratingItem(Request $request)
    {
        if(auth()->user())
        {
            $user_id = auth()->id();
            $row = ItemsRating::firstOrNew( ['user_id' => $user_id, 'item_id' => $request->item_id ] );
            $row->value = $request->value;
            $row->save();
            return[
                'success' => false,
                'message' => 'Rating Guardado'
            ];
        }
        return[
            'success' => false,
            'message' => 'No se guardo Rating'
        ];

    }

    public function getRating($id)
    {
        if(auth()->user())
        {
            $user_id = auth()->id();
            $row = ItemsRating::where('user_id', $user_id)->where('item_id', $id)->first();
            return[
                'success' => true,
                'value' => ($row) ? $row->value : 0,
                'message' => 'Valor Obtenido'
            ];
        }
        return[
            'success' => false,
            'value' => 0,
            'message' => 'No se obtuvo valor'
        ];

    }

    private function getExchangeRateSale(){

        $exchange_rate = app(ServiceController::class)->exchangeRateTest(date('Y-m-d'));

        return (array_key_exists('sale', $exchange_rate)) ? $exchange_rate['sale'] : 1;


    }

    public function saveDataUser(Request $request)
    {
        $user = auth()->user();

        if ($request->address) {
            $user->address = $request->address;
        }
        if ($request->telephone) {
            $user->telephone = $request->telephone;
        }

        if(!$user->identity_document_type_id)
        {
            $user->identity_document_type_id = 3;
        }

        if ($request->identification_number) {
            $user->telephone = $request->identification_number;
        }

        $user->save();

        return ['success' => true];

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

                return [];

                break;
        }
    }


    public function tables()
    {
        return [
            'payment_method_types' => PaymentMethodType::whereShowInEcommerce()->get()
        ];
    }

}
