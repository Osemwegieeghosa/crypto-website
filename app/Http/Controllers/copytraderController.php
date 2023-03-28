<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\CopyTrader;

use App\Http\Controllers\Controller;

class copytraderController extends Controller
{
    public function index()
    {
        $copyTraders = copytrader::all();
        return view('user.copytrader', compact('copyTraders'));
    }
    public function copy(Request $request)
{
    $copyTraderId = $request->input('copy_trader_id');
    $copyTrader = CopyTrader::findOrFail($copyTraderId);
    $copyTrader->is_copied = true;
    $copyTrader->save();
    
    return response()->json([
        'success' => true
    ]);
}

}