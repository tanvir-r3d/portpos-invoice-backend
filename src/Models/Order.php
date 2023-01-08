<?php

namespace App\Models;

use Carbon\Carbon;
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

    const STATUS = [
        'Pending',
        'Paid',
        'Fulfilled',
        'Refund',
    ];

    protected $appends = [
        'created', 'status_name', 'customer_name',
        'customer_email', 'customer_phone'
    ];

    public function getCreatedAttribute(): string
    {
        return Carbon::make($this->attributes['created_at'])->format('d/m/y H:i');
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUS[$this->attributes['status']];
    }

    public function getCustomerNameAttribute()
    {
        return $this->billing->name ?? '';
    }

    public function getCustomerEmailAttribute()
    {
        return $this->billing->email ?? '';
    }

    public function getCustomerPhoneAttribute()
    {
        return $this->billing->phone ?? '';
    }

    public function billing(): HasOne
    {
        return $this->hasOne(OrderBilling::class, 'order_id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(OrderProduct::class, 'order_id');
    }
}