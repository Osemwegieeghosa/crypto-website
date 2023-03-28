<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Transaction;

class GeneralService 
{
    public function generateUserUid(){
        do {
            $uid = random_int(1000000, 9999999); 
        } 
        while (User::where('uid', $uid)->first());
        return $uid;
    }

    public function generateTxid(){
        do {
            $txId = "Tx-";
            for($count = 0; $count < 4; $count++){
                $txId .= strtoupper(Str::random(4)) . ($count < 3 ? "-" : "");
            }
        } 
        while (Transaction::where('tx_id', $txId)->first());
        return $txId;
    }
}