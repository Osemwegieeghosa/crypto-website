<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CopyTrader;
use App\Models\User;

class addcopytraderController extends Controller
{
    public function create()
    {
        return view('admin.copytrader.create');
    }

    public function store(Request $request)
    {
        // Validate the form input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cumulative_profit' => 'required|numeric',
            'profit_rate_per_week' => 'required|numeric',
            'online_status' => 'required|boolean',
            'total_winning_trades' => 'required|numeric',
            'total_losing_trades' => 'required|numeric',
        ]);

        // Create a new copy trader
        $copy_trader = new CopyTrader();
        $copy_trader->name = $validatedData['name'];
        $copy_trader->username = $validatedData['username'];
        $copy_trader->email = $validatedData['email'];
        $copy_trader->cumulative_profit = $validatedData['cumulative_profit'];
        $copy_trader->profit_rate_per_week = $validatedData['profit_rate_per_week'];
        $copy_trader->online_status = $validatedData['online_status'];
        $copy_trader->total_winning_trades = $validatedData['total_winning_trades'];
        $copy_trader->total_losing_trades = $validatedData['total_losing_trades'];

        // Upload the profile picture and save the path to the database
        $profilePicturePath = $request->file('profile_picture')->store('public/profile_pictures');
        $copy_trader->profile_picture = $profilePicturePath;

        $copy_trader->save();

        // Redirect the admin back to the copy trader list page
        return redirect()->route('admin.vendors');
    }
}
