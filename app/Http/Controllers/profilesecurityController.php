<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;

class profilesecurityController extends Controller
{
    public function index()
    {
        return view('user.profilesecurity', ['title' => 'profilesecurity']);
    }
}