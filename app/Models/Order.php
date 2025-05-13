<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_name',
        'weight',
        'date',
        'service_type',
        'status',
        'payment_method',
        'payment_status',
        'amount',
        'special_instructions',
        'is_archived',
        'pricing_details'
    ];

    protected $casts = [
        'service_type' => 'array',
        'pricing_details' => 'array',
        'date' => 'datetime',
    ];

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function updateStatus(string $newStatus)
    {
        $this->statusLogs()->create([
            'status' => $newStatus,
            'changed_at' => now(),
            // Removed user_id from here
        ]);

        $this->status = $newStatus;
        $this->save();
    }
}