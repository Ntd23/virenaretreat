<?php

namespace Modules\Booking\Listeners;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Events\BookingUpdatedEvent;
use App\User;

class AffiliateCommissionListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof BookingCreatedEvent) {
            $this->handleCreated($event);
        } elseif ($event instanceof BookingUpdatedEvent) {
            $this->handleUpdated($event);
        }
    }

    /**
     * Ghi nhận hoa hồng tạm tính khi Booking được tạo
     */
    protected function handleCreated(BookingCreatedEvent $event)
    {
        $booking = $event->booking;
        $referrerId = Cookie::get('affiliate_referrer_id');

        if ($referrerId && is_numeric($referrerId)) {
            // Không tính hoa hồng nếu tự mua qua link của chính mình
            if ($booking->customer_id == $referrerId) {
                return;
            }

            $service = $booking->service;
            if ($service && isset($service->is_affiliate) && $service->is_affiliate == 1) {
                $commissionType = $service->affiliate_commission_type;
                $commissionValue = $service->affiliate_commission_value;
                $commissionAmount = 0;

                if ($commissionType === 'percent') {
                    $commissionAmount = $booking->total * ($commissionValue / 100);
                } elseif ($commissionType === 'fixed') {
                    $commissionAmount = $commissionValue;
                }

                if ($commissionAmount > 0) {
                    DB::table('affiliate_commissions')->updateOrInsert(
                        ['booking_id' => $booking->id],
                        [
                            'referrer_id' => $referrerId,
                            'commission_type' => $commissionType,
                            'commission_rate' => $commissionValue,
                            'commission_amount' => $commissionAmount,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Cập nhật trạng thái hoa hồng khi Booking thay đổi trạng thái
     */
    protected function handleUpdated(BookingUpdatedEvent $event)
    {
        $booking = $event->booking;
        
        $commission = DB::table('affiliate_commissions')
            ->where('booking_id', $booking->id)
            ->first();

        if ($commission) {
            if ($booking->status === 'completed' && $commission->status === 'pending') {
                // Duyệt hoa hồng
                DB::table('affiliate_commissions')
                    ->where('id', $commission->id)
                    ->update([
                        'status' => 'approved',
                        'updated_at' => now()
                    ]);
            } elseif (in_array($booking->status, ['cancelled', 'cancel']) && $commission->status === 'pending') {
                // Hủy hoa hồng
                DB::table('affiliate_commissions')
                    ->where('id', $commission->id)
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => now()
                    ]);
            }
        }
    }
}
