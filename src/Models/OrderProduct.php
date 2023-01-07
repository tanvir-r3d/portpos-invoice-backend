<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    protected $table = 'order_products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'name',
        'description',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }
}