<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;


use Mail;

class WithdrawalConfirmationController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'withdrawal_option' => 'required',
            'amount' => 'required|numeric'
        ]);

        $email = $request->input('email');
        $withdrawal_option = $request->input('withdrawal_option');
        $amount = $request->input('amount');
        
        $content = '<h1>Withdrawal Confirmation</h1>';
        $content .= '<p>Dear valued customer,</p>';
        $content .= '<p>Your withdrawal request has been received and is being processed. We will notify you when the withdrawal is complete.</p>';
        $content .= '<p>Withdrawal details:</p>';
        $content .= '<p>Withdrawal option: '.$withdrawal_option.'</p>';
        $content .= '<p>Amount: '.$amount.'</p>';
        $content .= '<p>Thank you for choosing CassoExchange.</p>';
        $content .= '<p>Best regards,</p>';
        $content .= '<p>CassoExchange Team</p>';
        
        Mail::send([], [], function ($message) use ($email, $content) {
            $message->to($email)
                ->subject('Withdrawal Confirmation')
                ->setBody($content, 'text/html');
            $message->from('noreply@cassoexchange.com', 'CassoExchange');
        });
    }
}
