<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'user_id',
        'number',
        'label',
    ];
    protected $hidden = [
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}
