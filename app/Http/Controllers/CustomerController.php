<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class CustomerController extends Controller
{
    public function index(){
        return view('customer.home');
    }
}
