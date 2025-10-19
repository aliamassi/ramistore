<?php

namespace App\Http\Controllers\Panel;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
class BaseController extends Controller
{
    //
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        global $route;
//        $route = Route::current()->getName();
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|Admin|null
     */
    protected function admin()
    {
        return Auth::guard('panel')->user();
    }
}
