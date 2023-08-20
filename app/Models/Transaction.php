<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'user_id_from',
        'user_id_to',
        'wallet_id',
        'amount',
        'description',
        'type',
        'status',
        'status_message'
    ];
}
