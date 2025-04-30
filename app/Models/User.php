<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes,HasRoles;
    protected $guard_name = ['api'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'national_code'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed'
        ];
    }

    public const STATUS_NEW = 'new';
    public const STATUS_UNVERIFIED = 'unverified';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_BLOCK = 'block';
    public static array $statuses = [
        self::STATUS_NEW,
        self::STATUS_VERIFIED,
        self::STATUS_UNVERIFIED,
        self::STATUS_BLOCK
    ];

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}
