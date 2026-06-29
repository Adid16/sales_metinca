<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PoListController extends Controller
{
    //
    public function polistint()
    {
        //load data disini jika perlu

        return view('polistint');
    }
}
