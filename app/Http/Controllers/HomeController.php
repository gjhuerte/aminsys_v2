<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->accesslevel == 0)
        {
            return redirect('backup');
        }

        if(Auth::user()->accesslevel == 1)
        {
            return redirect('inventory/supply');
        }

        if(Auth::user()->accesslevel == 2)
        {
            return redirect('purchaseorder');
        }

        if(Auth::user()->accesslevel == 3)
        {

            return redirect('request');

        }
    }
}
