<?php

namespace Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AffiliateCommission extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    protected $table = 'affiliate_commissions';

    protected $fillable = [
        'booking_id',
        'referrer_id',
        'commission_type',
        'commission_rate',
        'commission_amount',
        'status',
        'approved_at',
        'approved_by',
        'paid_at',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->hasOne(AffiliatePayment::class, 'commission_id');
    }

    public function ensurePayment()
    {
        if ($this->payment) {
            return $this->payment;
        }

        if (!Schema::hasTable('affiliate_payments')) {
            return null;
        }

        return $this->payment()->firstOrCreate(
            ['commission_id' => $this->id],
            [
                'booking_id' => $this->booking_id,
                'referrer_id' => $this->referrer_id,
                'amount' => $this->commission_amount,
                'payment_code' => static::makePaymentCode($this->id),
                'status' => AffiliatePayment::STATUS_WAITING_CONFIRM,
            ]
        );
    }

    public static function makePaymentCode($commissionId)
    {
        return 'AFPAY' . str_pad((string) $commissionId, 8, '0', STR_PAD_LEFT);
    }
}
