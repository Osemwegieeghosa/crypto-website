<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class dashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard', ['title' => 'Dashboard']);
    }

    public function transactions(){
        return view('user.transactions', [
            'title' => 'Transactions',
            'transactions' => Auth::user()->getTransactions()
        ]);
    }
}