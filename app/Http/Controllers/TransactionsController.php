<?php

namespace App\Http\Controllers;

class TransactionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show all transactions
     * @return /Iluminate/Http/Response
     * 
     */
    public function index()
    {
        return response()->json(['message' => 'Transactions']);
    }

    //
}
