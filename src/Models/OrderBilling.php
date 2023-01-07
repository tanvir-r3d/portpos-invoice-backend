<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBilling extends Model
{
    protected $table = 'order_billings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'phone',
        'address_street',
        'address_city',
        'address_state',
        'address_zipcode',
        'address_country',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }
}