<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;

class investmentController extends Controller
{
    public function index()
    {
        return view('user.investment', ['title' => 'investment']);
    }
}