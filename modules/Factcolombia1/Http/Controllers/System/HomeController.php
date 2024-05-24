<?php

namespace Modules\Factcolombia1\Http\Controllers\System;

use Modules\Factcolombia1\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:admin');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('factcolombia1::app.system.home');
    }

    public function indexDocument()
    {
        return view('factcolombia1::app.system.document');
    }
}
