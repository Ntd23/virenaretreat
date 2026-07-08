<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\Vendor\Models\AffiliateCommission;
use Modules\Vendor\Models\AffiliatePayment;

class AffiliatePaymentWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$this->hasValidApiKey($request)) {
            Log::warning('Unauthorized SePay affiliate payment webhook.', [
                'has_authorization_header' => $request->headers->has('Authorization'),
            ]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $transferType = strtolower((string) data_get($payload, 'transferType', data_get($payload, 'transfer_type', '')));
        $transactionId = data_get($payload, 'id');
        $code = (string) data_get($payload, 'code', '');
        $content = (string) data_get($payload, 'content', data_get($payload, 'description', ''));

        if ($this->isTestWebhook($payload)) {
            Log::info('Ignored SePay affiliate payment test webhook.', [
                'transaction_id' => $transactionId,
                'code' => $code,
                'content' => $content,
            ]);

            return $this->success();
        }

        if (!$this->isAffiliateTransferType($transferType, $code, $content) || empty($transactionId)) {
            Log::info('Ignored SePay affiliate payment webhook.', [
                'transfer_type' => $transferType,
                'transaction_id' => $transactionId,
                'code' => $code,
                'content' => $content,
            ]);

            return $this->success();
        }

        if (AffiliatePayment::where('sepay_transaction_id', $transactionId)->exists()) {
            Log::info('Duplicate SePay affiliate payment webhook ignored.', [
                'transaction_id' => $transactionId,
            ]);

            return $this->success();
        }

        DB::transaction(function () use ($payload, $transactionId, $code, $content) {
            if (AffiliatePayment::where('sepay_transaction_id', $transactionId)->lockForUpdate()->exists()) {
                return;
            }

            $amount = $this->parseAmount(data_get($payload, 'transferAmount', 0));

            $payment = $this->findWaitingPayment($code, $content);
            if (!$payment) {
                Log::warning('SePay affiliate payment webhook has no matching waiting payment.', [
                    'transaction_id' => $transactionId,
                    'code' => $code,
                    'content' => $content,
                    'amount' => $amount,
                ]);

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

                Log::warning('SePay affiliate payment amount mismatch.', [
                    'transaction_id' => $transactionId,
                    'payment_code' => $payment->payment_code,
                    'expected_amount' => $payment->amount,
                    'transfer_amount' => $amount,
                ]);

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

            Log::info('SePay affiliate payment marked as paid.', [
                'transaction_id' => $transactionId,
                'payment_code' => $payment->payment_code,
                'commission_id' => $payment->commission_id,
            ]);
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

    protected function hasValidApiKey(Request $request)
    {
        $storedApiKey = setting_item('sepay_api_key');
        if (empty($storedApiKey)) {
            return true;
        }

        $header = (string) $request->header('Authorization', '');
        $apiKey = '';

        if (stripos($header, 'Apikey ') === 0) {
            $apiKey = trim(substr($header, 7));
        } elseif (stripos($header, 'Bearer ') === 0) {
            $apiKey = trim(substr($header, 7));
        }

        return $apiKey !== '' && hash_equals((string) $storedApiKey, $apiKey);
    }

    protected function isAffiliateTransferType($transferType, $code, $content)
    {
        if ($transferType === 'out') {
            return true;
        }

        return $transferType === 'in' && $this->hasAffiliatePaymentCode($code, $content);
    }

    protected function hasAffiliatePaymentCode($code, $content)
    {
        return preg_match('/AF{1,2}PAY\d{8}/i', $code . ' ' . $content) === 1;
    }

    protected function isTestWebhook(array $payload)
    {
        $transactionId = (string) data_get($payload, 'id', '');
        $referenceCode = (string) data_get($payload, 'referenceCode', data_get($payload, 'reference_code', ''));

        return str_starts_with(strtoupper($transactionId), 'TEST_')
            || str_starts_with(strtoupper($referenceCode), 'FT_TEST');
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
