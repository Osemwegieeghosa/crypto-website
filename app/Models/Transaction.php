<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_uid',
        'vendor_uid',
        'trade_type',
        'rate',
        'amount',
        'remarks',
        'tx_id',
    ];


    public function getVendor(){
        return User::where('uid', $this->vendor_uid)->first();
    }

    public function getCustomer(){
        return User::where('uid', $this->customer_uid)->first();
    }
    public function user()
{
    return $this->belongsTo(User::class, 'customer_uid');
}

}
