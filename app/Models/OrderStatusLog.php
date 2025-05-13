<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'user_id',
        'changed_at'
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
