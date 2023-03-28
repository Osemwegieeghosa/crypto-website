<?php


use Mail;

class WithdrawalController extends Controller
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
        
        Mail::send('emails.withdrawal', ['email' => $email, 'withdrawal_option' => $withdrawal_option, 'amount' => $amount], function ($message) use ($email) {
            $message->from('support@cassoexchange.co', 'Withdrawal Confirmation');
            $message->to($email);
            $message->subject('Withdrawal Confirmation');
        });
    }
}
