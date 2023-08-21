<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'payer',
        'payee',
        'wallet_id',
        'amount',
        'description',
        'type',
        'status',
        'status_message'
    ];

    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'payer');
    }

    public function recipient(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'payee');
    }
}
