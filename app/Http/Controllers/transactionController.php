<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class transactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = transaction::where('customer_uid', $user->id)->get();

        // Pass the transactions to the view
        return view('user.transactions', ['transactions' => $transactions]);
    }
}
