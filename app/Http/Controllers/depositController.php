<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\User;

class depositController extends Controller
{
    public function index()
    {
        return view('user.deposit', ['title' => 'deposit']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $customer_uid = Auth::id();
        $amount = $validated['amount'];
        $tx_id = 'DPXT-' . uniqid();
        $id = $id = mt_rand(10, 9999);;

        $transaction = new Transaction();
        $transaction->tx_id = $tx_id;
        $transaction->id = $id;
        $transaction->customer_uid = $customer_uid;
        $transaction->vendor_uid = $customer_uid;
        $transaction->amount = $amount;
        $transaction->rate = $amount;
        $transaction->trade_type = 'deposit';
        $transaction->remarks = 'Deposit pending';
        $transaction->save();

        return redirect()->route('user.transactions')->with('success', 'Deposit successful!');
    }

    public function success()
    {
        return view('user.transactions');
    }

    public function transactions()
    {
        $transactions = Transaction::where('status', 'pending')->get();

        return view('admin.transactions', compact('transactions'));
    }

    public function confirm($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->remarks = 'completed';
        $transaction->save();

        $user = User::findOrFail($transaction->customer_uid);
        $user->total_deposit += $transaction->amount;
        $user->save();

        return redirect()->route('admin.transactions')->with('success', 'Transaction confirmed successfully.');
    }
}
