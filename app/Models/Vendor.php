<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'rate',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getTrades(){
        return Transaction::where('vendor_uid', $this->user->uid)->get();
    }
}
