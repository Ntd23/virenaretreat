<?php

namespace Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatePayment extends Model
{
    const STATUS_WAITING_CONFIRM = 'waiting_confirm';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';

    protected $table = 'affiliate_payments';

    protected $fillable = [
        'commission_id',
        'booking_id',
        'referrer_id',
        'amount',
        'payment_code',
        'status',
        'sepay_transaction_id',
        'sepay_reference_code',
        'sepay_transfer_content',
        'sepay_transfer_amount',
        'sepay_transaction_date',
        'sepay_bank_name',
        'sepay_account_number',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sepay_transfer_amount' => 'decimal:2',
        'sepay_transaction_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function commission()
    {
        return $this->belongsTo(AffiliateCommission::class, 'commission_id');
    }
}
