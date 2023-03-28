<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class faqController extends Controller
{
    public function index()
    {
        return view('about', ['title' => 'About']);
    }
}