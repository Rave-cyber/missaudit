<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Transaction.php
class Transaction extends Model
{
    protected $fillable = [
    'customer_name',
    'weight',
    'order_date',
    'services',
    'payment_method',
    'amount',
    'special_instructions',
    'user_id'
];

protected $casts = [
    'services' => 'array',
    'order_date' => 'date',
];}