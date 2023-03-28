<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StakingController extends Controller
{
    public function index()
    {
        return view('user.staking');
    }

    public function submit(Request $request)
    {
        // Validate the form input
        $request->validate([
            'amount' => 'required|numeric|min:0|max:' . auth()->user()->available_balance,
            'duration' => 'required|numeric|min:1|max:365', // Maximum duration is one year (365 days)
        ]);

        // Handle the staking logic
        $stakingAmount = $request->input('amount');
        $stakingDuration = $request->input('duration');
        $profit = $stakingAmount * 0.1 * $stakingDuration; // Change this to calculate the actual profit
        // Deduct the staked amount from the user's available balance and add it to their total invested amount
        $user = auth()->user();
        $user->available_balance -= $stakingAmount;
        $user->investment_balance += $stakingAmount;
        $user->save();
        // Do something with the staking amount, duration, and profit, like create a new staking transaction in the database

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Staking confirmed!');
    }
}
