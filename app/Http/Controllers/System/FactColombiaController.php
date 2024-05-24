<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Modules\Factcolombia1\app\Http\Controllers\System\LoginController;

class FactColombiaController extends Controller
{

    /**
     * Show the application's Facturador Colombia 1 login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return app(LoginController::class)->ShowLoginForm();
    }
}
