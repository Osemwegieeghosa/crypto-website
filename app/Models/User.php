<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'uid',
        'available_balance',
    ];

    /**For hidding/encrypting the stored password */
    // public function setPasswordAttribute($value)
    // {
    //    $this->attributes['password'] = bcrypt($value);
    // }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function vendor(){
        return $this->hasOne(Vendor::class);
    }

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }

    public function isAdmin(){
        return str_contains($this->email, '@capitaltradex.net');
    }

    public function getTransactions(){
        return Transaction::where('customer_uid', $this->uid)->get();
    }
    
}
