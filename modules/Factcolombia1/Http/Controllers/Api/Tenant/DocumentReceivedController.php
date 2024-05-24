<?php

namespace Modules\Factcolombia1\Http\Controllers\Api\Tenant;

use Illuminate\Contracts\Encryption\DecryptException;
use Modules\Factcolombia1\Http\Controllers\Controller;
use Modules\Factcolombia1\Models\Tenant\Document;
use Illuminate\Http\Request;

class DocumentReceivedController extends Controller
{
    public function documentReceived(Request $request) {
        try {
            if (Document::whereNull('acknowledgment_received')->where('cufe', decrypt($request->cufe))->update(['acknowledgment_received' => decrypt($request->state)])) return 'Acuse de recibido registrado con éxito.';
            
            return 'El acuse de recibido ya fue registrado.';
        }
        catch (DecryptException $e) {
            return 'El acuse de recibido ya fue registrado.';
        }
    }
}
