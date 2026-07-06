<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Vendor\Models\AffiliateCommission;
use Modules\Vendor\Models\AffiliatePayment;

class AffiliatePaymentWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $transferType = strtolower((string) data_get($payload, 'transferType', data_get($payload, 'transfer_type', '')));
        $transactionId = data_get($payload, 'id');

        if ($transferType !== 'out' || empty($transactionId)) {
            return $this->success();
        }

        if (AffiliatePayment::where('sepay_transaction_id', $transactionId)->exists()) {
            return $this->success();
        }

        DB::transaction(function () use ($payload, $transactionId) {
            if (AffiliatePayment::where('sepay_transaction_id', $transactionId)->lockForUpdate()->exists()) {
                return;
            }

            $code = (string) data_get($payload, 'code', '');
            $content = (string) data_get($payload, 'content', '');
            $amount = $this->parseAmount(data_get($payload, 'transferAmount', 0));

            $payment = $this->findWaitingPayment($code, $content);
            if (!$payment) {
                return;
            }

            $sepayData = [
                'sepay_transaction_id' => $transactionId,
                'sepay_reference_code' => data_get($payload, 'referenceCode', data_get($payload, 'reference_code')),
                'sepay_transfer_content' => $content,
                'sepay_transfer_amount' => $amount,
                'sepay_transaction_date' => $this->parseDate(data_get($payload, 'transactionDate', data_get($payload, 'transaction_date'))),
                'sepay_bank_name' => data_get($payload, 'gateway', data_get($payload, 'gatewayName')),
                'sepay_account_number' => data_get($payload, 'accountNumber', data_get($payload, 'account_number')),
            ];

            if (round((float) $amount, 2) !== round((float) $payment->amount, 2)) {
                $payment->update(array_merge($sepayData, [
                    'status' => AffiliatePayment::STATUS_FAILED,
                ]));

                return;
            }

            $paidAt = now();
            $payment->update(array_merge($sepayData, [
                'status' => AffiliatePayment::STATUS_PAID,
                'paid_at' => $paidAt,
            ]));

            $commissionUpdates = [
                'status' => AffiliateCommission::STATUS_PAID,
                'updated_at' => $paidAt,
            ];

            if (Schema::hasColumn('affiliate_commissions', 'paid_at')) {
                $commissionUpdates['paid_at'] = $paidAt;
            }

            AffiliateCommission::where('id', $payment->commission_id)
                ->lockForUpdate()
                ->update($commissionUpdates);
        });

        return $this->success();
    }

    protected function findWaitingPayment($code, $content)
    {
        if ($code === '' && $content === '') {
            return null;
        }

        return AffiliatePayment::query()
            ->where('status', AffiliatePayment::STATUS_WAITING_CONFIRM)
            ->where(function ($query) use ($code, $content) {
                if ($code !== '') {
                    $query->whereRaw('? LIKE CONCAT("%", payment_code, "%")', [$code]);
                }
                if ($content !== '') {
                    $query->orWhereRaw('? LIKE CONCAT("%", payment_code, "%")', [$content]);
                }
            })
            ->lockForUpdate()
            ->first();
    }

    protected function parseAmount($amount)
    {
        if (is_numeric($amount)) {
            return (float) $amount;
        }

        return (float) preg_replace('/[^\d.-]/', '', (string) $amount);
    }

    protected function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        $timestamp = strtotime($date);

        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }

    protected function success()
    {
        return response()->json(['success' => true]);
    }
}
