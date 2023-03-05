<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'customer_name',
        'customer_phone',
        'table_number',
        'total_price',
        'payment_method',
        'payment_status',
        'transaction_status'
    ];

}
