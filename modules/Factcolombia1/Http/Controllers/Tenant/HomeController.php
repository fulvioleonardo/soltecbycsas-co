<?php

namespace Modules\Factcolombia1\Http\Controllers\Tenant;

use Modules\Factcolombia1\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Factcolombia1\Models\Tenant\{
    Document,
    Client
};

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

        $allClients = count(Client::all());
        $clientsToday = count(Client::whereDate('created_at', '=', date('Y-m-d'))->get());

        $allDocuments = count(Document::all());
        $documentsToday = count(Document::whereDate('created_at', '=', date('Y-m-d'))->get());

        return view('app.tenant.home')
            ->with('allclients', $allClients)
            ->with('clientstoday', $clientsToday)
            ->with('alldocuments', $allDocuments)
            ->with('documentstoday', $documentsToday);
    }

    
}
