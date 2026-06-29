<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserCustomerListController extends Controller
{
    public function usercustomerlist()
    {
        $users = User::where('role','=','customer')->get();
        return view ('usercustomerlist', compact('users'));
    }
}
