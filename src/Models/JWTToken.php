<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JWTToken extends Model
{
    protected $table = 'jwt_tokens';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'user_id',
        'token_title',
        'token',
        'restrictions',
        'permissions',
        'last_used_at',
        'expires_at',
        'refreshed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}