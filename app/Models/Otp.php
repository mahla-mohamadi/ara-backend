<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'phone_id',
        'otp_code',
        'expires_at',
    ];
    protected $hidden = [
        'phone_id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }
}
