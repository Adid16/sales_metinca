<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContractListController extends Controller
{
    //
    public function contractlist()
    {
        //load data disini jika perlu

        return view('contractlist');
    }
}
