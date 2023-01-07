<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'invoice_id',
        'currency',
        'amount',
        'status',
    ];

    public function billing(): HasOne
    {
        return $this->hasOne(OrderBilling::class, 'order_id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(OrderProduct::class, 'order_id');
    }
}