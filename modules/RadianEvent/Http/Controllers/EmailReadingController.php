<?php

namespace Modules\RadianEvent\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Factcolombia1\Helpers\HttpConnectionApi;
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceCompany
};
use Exception;
use Modules\RadianEvent\Models\{
    ReceivedDocument,
    EmailReading,
};
use Modules\RadianEvent\Http\Resources\{
    EmailReadingCollection
};


class EmailReadingController extends Controller
{
    
    public function index()
    {
        return view('radianevent::process-emails.index');
    }
    
    public function columns()
    {
        return [
            'start_date' => 'Fecha inicio',
            'start_time' => 'Hora inicio',
            'email_user' => 'Correo',
        ];
    }

    
    public function details($id)
    {
        $record = EmailReading::with('details')->findOrFail($id);

        return $record->details->transform(function($row){
            return $row->getRowResource();
        });
    }


    public function records(Request $request)
    {
        $records = EmailReading::where($request->column, 'like', "%{$request->value}%");

        return new EmailReadingCollection($records->latest()->paginate(config('tenant.items_per_page')));
    }



}
