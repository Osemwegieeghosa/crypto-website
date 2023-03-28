<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class indexController extends Controller

{
    public function index()
        {
            if (request()->is('/')) {
                return response()->file('index.html');
            } else {
                return view('index', ['title' => 'Home Page']);
            }
        }
}