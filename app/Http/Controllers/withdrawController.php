<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\STMP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
require_once($_SERVER['DOCUMENT_ROOT'].'/phpmailer/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/phpmailer/src/SMTP.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/phpmailer/src/Exception.php');

class WithdrawController extends Controller
{
    public function withdraw(Request $request)
    {
        // Get the withdrawal amount from the request
        $withdrawalAmount = $request->input('withdrawalAmount');

        // Deduct the withdrawal amount from the user's balance
        $user = Auth::user();
        $user->available_balance -= $withdrawalAmount;
        $user->total_withdrawal += $withdrawalAmount;
        $user->save();

        // Add the transaction to the database
        $transactions = new Transaction;
        $transactions->id = $user->id. rand(2, 490);
        $transactions->tx_id = 'TXDS-' . uniqid();
        $transactions->trade_type = 'Withdrawal';
        $transactions->amount = $withdrawalAmount;
        $transactions->remarks = 'withdrawal pending';
        $transactions->customer_uid = $user->id;
        $transactions->vendor_uid = $user->id;
        $transactions->rate = '0';
        $transactions->save();

        // Send an email to the user with the withdrawal fee
        $email = $user->email;
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'support@capitaltradex.net'; 
            $mail->Password   = 'Password123.'; 
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('support@capitaltradex.net', 'CapitalTradex');
            $mail->addAddress($email, $user->name);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Withdrawal Fee';
            $mail->Body    = '
                <p>Hello ' . $user->name . ',</p>
                <p>We received your request to withdraw funds from Capitaltradex. The funds will be deposited in your provided account and should be processed within 24-72 hours. You will be notified by email when we have completed your withdrawal, the next step is the payment of your commission which is 10% of your total profit.</p>
                <p><strong>Withdrawal Details:</strong></p>
                <p>Reference ID: ' . $transactions->tx_id . '</p>
                <p>Payment Method: Bank Transfer</p>
                <p>Withdrawal Amount: ' . $withdrawalAmount . ' USD</p>
                <p><strong>Note:</strong> If you did not make this withdrawal request, please contact us immediately before it is authorized by our team. If you have any questions, please feel free to contact us.</p>
                <br>
                <p>Best Regards,</p>
                <p>Team of Capitaltradex</p>';

            $mail->send();
            return redirect()->route('user.transactions')->with('success', 'Withdrawal successful!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Withdrawal failed! Please try again later.');
        }
    }
}
