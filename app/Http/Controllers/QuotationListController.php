<?php

namespace App\Http\Controllers;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationListController extends Controller
{
    //
    public function quotationlist()
    {
        //1. ambil semua data dari tabel quotation
        $quotations = Quotation::latest()->get();
        return view('quotationlist', compact('quotations'));
    }
}
