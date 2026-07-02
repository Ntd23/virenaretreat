<?php

namespace App\Models;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AdvertisementRequest extends Model
{
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_APPROVED_WAIT_PAYMENT = 'approved_wait_payment';
    const STATUS_PAYMENT_WAITING_CONFIRM = 'payment_waiting_confirm';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PAYMENT_RECEIVED = 'payment_received';
    const STATUS_WAITING_QUEUE = 'waiting_queue';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'advertisement_position_id',
        'title',
        'description',
        'content',
        'media_urls',
        'target_url',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'duration',
        'link_url',
        'placement',
        'start_date',
        'end_date',
        'note',
        'status',
        'admin_note',
        'rejection_reason',
        'reject_reason',
        'original_price',
        'discount_amount',
        'final_price',
        'approved_by',
        'approved_at',
        'confirmed_by',
        'confirmed_at',
        'running_at',
        'completed_at',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'approved_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'running_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment()
    {
        return $this->hasOne(AdvertisementPayment::class, 'advertisement_request_id');
    }

    public function position()
    {
        return $this->belongsTo(AdvertisementPosition::class, 'advertisement_position_id');
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function isPriceLocked()
    {
        return in_array($this->status, [
            self::STATUS_PAYMENT_WAITING_CONFIRM,
            self::STATUS_PAYMENT_RECEIVED,
            self::STATUS_WAITING_QUEUE,
            self::STATUS_RUNNING,
            self::STATUS_COMPLETED,
        ], true) || in_array(optional($this->payment)->payment_status, [
            AdvertisementPayment::STATUS_WAITING_CONFIRM,
            AdvertisementPayment::STATUS_PAID,
        ], true);
    }

    public function firstMediaUrl()
    {
        $mediaUrls = $this->media_urls ?: [];

        if (!is_array($mediaUrls)) {
            $mediaUrls = json_decode($mediaUrls, true) ?: [];
        }

        return collect($mediaUrls)->filter()->first();
    }

    public function getDurationLabelAttribute()
    {
        return $this->duration ?: '-';
    }

    public static function runningAds($placement = null, $limit = 3)
    {
        return static::query()
            ->with('position')
            ->where('status', self::STATUS_RUNNING)
            ->when($placement, function ($query) use ($placement) {
                $query->where(function ($query) use ($placement) {
                    $query->where('placement', $placement)
                        ->orWhereHas('position', function ($query) use ($placement) {
                            $query->where('code', $placement)
                                ->orWhere('placement', $placement);
                        });
                });
            })
            ->whereNotNull('media_urls')
            ->latest('running_at')
            ->latest('updated_at')
            ->limit($limit * 3)
            ->get()
            ->filter(function ($advertisement) {
                return (bool) $advertisement->firstMediaUrl();
            })
            ->take($limit)
            ->values();
    }

    public function completeAndPromoteNext($actorId = null)
    {
        return DB::transaction(function () use ($actorId) {
            $runningAdvertisement = static::query()
                ->whereKey($this->id)
                ->lockForUpdate()
                ->first();

            if (!$runningAdvertisement || $runningAdvertisement->status !== self::STATUS_RUNNING) {
                return [
                    'completed' => null,
                    'promoted' => null,
                ];
            }

            $positionId = $runningAdvertisement->advertisement_position_id;

            $runningAdvertisement->update([
                'status' => self::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);

            return [
                'completed' => $runningAdvertisement->fresh(['user', 'payment', 'position']),
                'promoted' => $positionId ? self::promoteNextWaitingForPosition($positionId, $actorId) : null,
            ];
        });
    }

    public static function completeExpiredRunningAds()
    {
        $normalizedCount = self::normalizeOverCapacityRunningAds();

        $expiredAdvertisements = static::query()
            ->where('status', self::STATUS_RUNNING)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', now()->toDateString())
            ->orderBy('end_date')
            ->orderBy('running_at')
            ->get();

        $completedCount = 0;
        $promotedCount = 0;

        foreach ($expiredAdvertisements as $advertisement) {
            $result = $advertisement->completeAndPromoteNext();

            if ($result['completed']) {
                $completedCount++;
            }

            if ($result['promoted']) {
                $promotedCount++;
            }
        }

        $emptySlotPromotions = self::promoteWaitingForEmptyPositions();

        return [
            'completed' => $completedCount,
            'promoted' => $promotedCount + $emptySlotPromotions,
            'normalized' => $normalizedCount,
        ];
    }

    public static function normalizeOverCapacityRunningAds()
    {
        $normalizedCount = 0;
        $positionIds = static::query()
            ->where('status', self::STATUS_RUNNING)
            ->whereNotNull('advertisement_position_id')
            ->distinct()
            ->pluck('advertisement_position_id');

        foreach ($positionIds as $positionId) {
            $capacity = self::getPositionRunningCapacity($positionId);
            $runningAdvertisements = static::query()
                ->where('advertisement_position_id', $positionId)
                ->where('status', self::STATUS_RUNNING)
                ->orderByRaw('running_at IS NULL')
                ->orderBy('running_at')
                ->orderBy('id')
                ->get();

            $overflowAdvertisements = $runningAdvertisements->slice($capacity);

            foreach ($overflowAdvertisements as $advertisement) {
                $advertisement->update([
                    'status' => self::STATUS_WAITING_QUEUE,
                    'running_at' => null,
                ]);
                $normalizedCount++;
            }
        }

        return $normalizedCount;
    }

    public static function promoteWaitingForEmptyPositions($actorId = null)
    {
        $positionIds = static::query()
            ->where('status', self::STATUS_WAITING_QUEUE)
            ->whereNotNull('advertisement_position_id')
            ->distinct()
            ->pluck('advertisement_position_id');

        $promotedCount = 0;

        foreach ($positionIds as $positionId) {
            while (!self::isPositionRunningFull($positionId)) {
                if (!self::promoteNextWaitingForPosition($positionId, $actorId)) {
                    break;
                }

                $promotedCount++;
            }
        }

        return $promotedCount;
    }

    public static function promoteNextWaitingForPosition($positionId, $actorId = null)
    {
        if (self::isPositionRunningFull($positionId)) {
            return null;
        }

        $nextAdvertisement = static::query()
            ->where('advertisement_position_id', $positionId)
            ->where('status', self::STATUS_WAITING_QUEUE)
            ->orderByRaw('confirmed_at IS NULL')
            ->orderBy('confirmed_at')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->first();

        if (!$nextAdvertisement) {
            return null;
        }

        $durationDays = self::getAdvertisementDurationDays($nextAdvertisement);
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->addDays($durationDays - 1);

        $nextAdvertisement->update([
            'status' => self::STATUS_RUNNING,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $durationDays . ' ngày',
            'confirmed_by' => $actorId ?: $nextAdvertisement->confirmed_by,
            'confirmed_at' => now(),
            'running_at' => now(),
        ]);

        return $nextAdvertisement->fresh(['user', 'payment', 'position']);
    }

    public static function isPositionRunningFull($positionId)
    {
        return static::query()
            ->where('advertisement_position_id', $positionId)
            ->where('status', self::STATUS_RUNNING)
            ->count() >= self::getPositionRunningCapacity($positionId);
    }

    protected static function getPositionRunningCapacity($positionId)
    {
        $position = AdvertisementPosition::query()->find($positionId);

        if (!$position) {
            return 1;
        }

        return $position->code === 'large_banner' ? 3 : 1;
    }

    protected static function getAdvertisementDurationDays(AdvertisementRequest $advertisement)
    {
        if ($advertisement->start_date && $advertisement->end_date) {
            return max(1, $advertisement->start_date->copy()->startOfDay()->diffInDays($advertisement->end_date->copy()->startOfDay()) + 1);
        }

        if (preg_match('/(\d+)/', (string) $advertisement->duration, $matches)) {
            return max(1, (int) $matches[1]);
        }

        return 1;
    }
}
