<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopyTrader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'email',
        'profile_picture',
        'cumulative_profit',
        'profit_rate_per_week',
        'online_status',
        'total_winning_trades',
        'total_losing_trades',
    ];

    /**
     * Get the URL of the copy trader's profile picture.
     */
    public function getProfilePictureUrlAttribute()
    {
        return asset($this->profile_picture);
    }
}
