<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AdvertisementPayment extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_WAITING_CONFIRM = 'waiting_confirm';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'advertisement_request_id',
        'user_id',
        'payment_code',
        'qr_url',
        'amount',
        'paid_amount',
        'payment_method',
        'payment_status',
        'sepay_transaction_id',
        'sepay_gateway',
        'sepay_code',
        'sepay_content',
        'sepay_transfer_content',
        'sepay_transaction_date',
        'sepay_payload',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'sepay_payload' => 'array',
        'sepay_transaction_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function advertisementRequest()
    {
        return $this->belongsTo(AdvertisementRequest::class, 'advertisement_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
