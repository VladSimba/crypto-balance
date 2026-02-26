<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'tx_hash',
        'reference',
        'status',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
