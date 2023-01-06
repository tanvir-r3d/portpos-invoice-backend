<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string email
 * @property string username
 * @property string token
 * @property string password
 * @property Carbon created_at
 * @property Carbon update_at
 */
class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => password_hash($value, PASSWORD_DEFAULT),
        );
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(JWTToken::class, 'user_id', 'id');
    }

    public function hasValidToken()
    {
        return $this->tokens()->whereNull('expires_at')->first();
    }
}