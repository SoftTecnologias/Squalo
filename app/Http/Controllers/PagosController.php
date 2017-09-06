<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PagosController extends Controller
{
    public function getInfo(Request $request){
        dd($request->inicial);
    }
}
