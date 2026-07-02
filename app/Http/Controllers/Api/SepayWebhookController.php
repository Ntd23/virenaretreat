<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementPayment;
use App\Models\AdvertisementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SepayWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $transferType = strtolower((string) data_get($payload, 'transferType', data_get($payload, 'transfer_type')));
        $transactionId = $this->getTransactionId($payload);
        $amount = $this->parseAmount(data_get($payload, 'transferAmount', data_get($payload, 'amount', 0)));
        $code = (string) data_get($payload, 'code', '');
        $content = (string) data_get($payload, 'content', data_get($payload, 'description', ''));
        $gateway = data_get($payload, 'gateway') ?: data_get($payload, 'gatewayName') ?: data_get($payload, 'bank_brand_name');
        $transactionDate = data_get($payload, 'transactionDate') ?: data_get($payload, 'transaction_date') ?: data_get($payload, 'time');

        if ($transferType !== 'in') {
            return response()->json([
                'message' => 'Ignored non-in transaction.',
            ]);
        }

        if ($this->transactionAlreadyProcessed($transactionId)) {
            return response()->json([
                'message' => 'Transaction already processed.',
            ]);
        }

        return DB::transaction(function () use ($payload, $transactionId, $transferType, $amount, $code, $content, $gateway, $transactionDate) {
            $payment = $this->findPayment($code, $content);

            if (!$payment) {
                Log::warning('SePay advertisement transaction has no matching payment code.', [
                    'sepay_transaction_id' => $transactionId,
                    'transfer_type' => $transferType,
                    'amount' => $amount,
                    'code' => $code,
                    'content' => $content,
                    'payload' => $payload,
                ]);

                return response()->json([
                    'message' => 'No matching payment code found.',
                    'matched' => false,
                ], 202);
            }

            $payment->update([
                'paid_amount' => $amount,
                'payment_method' => 'sepay',
                'payment_status' => AdvertisementPayment::STATUS_WAITING_CONFIRM,
                'sepay_transaction_id' => $transactionId,
                'sepay_gateway' => $gateway,
                'sepay_code' => $code,
                'sepay_content' => $content,
                'sepay_transfer_content' => $content,
                'sepay_transaction_date' => $transactionDate ? date('Y-m-d H:i:s', strtotime($transactionDate)) : null,
                'sepay_payload' => $payload,
                'paid_at' => null,
            ]);

            $payment->advertisementRequest()->update([
                'status' => AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM,
            ]);

            return response()->json([
                'message' => 'Webhook processed.',
                'matched' => true,
                'payment_status' => AdvertisementPayment::STATUS_WAITING_CONFIRM,
            ]);
        });
    }

    protected function getTransactionId(array $payload)
    {
        $transactionId = data_get($payload, 'id')
            ?: data_get($payload, 'transactionId')
            ?: data_get($payload, 'referenceCode')
            ?: data_get($payload, 'reference_code');

        return $transactionId ?: 'payload_' . hash('sha256', json_encode($payload));
    }

    protected function parseAmount($amount)
    {
        if (is_numeric($amount)) {
            return (float) $amount;
        }

        return (float) preg_replace('/[^\d.-]/', '', (string) $amount);
    }

    protected function transactionAlreadyProcessed($transactionId)
    {
        return AdvertisementPayment::where('sepay_transaction_id', $transactionId)->exists();
    }

    protected function findPayment($code, $content)
    {
        if ($code === '' && $content === '') {
            return null;
        }

        return AdvertisementPayment::query()
            ->where(function ($query) use ($code, $content) {
                if ($code !== '') {
                    $query->whereRaw('? LIKE CONCAT("%", payment_code, "%")', [$code]);
                }

                if ($content !== '') {
                    $query->orWhereRaw('? LIKE CONCAT("%", payment_code, "%")', [$content]);
                }
            })
            ->with('advertisementRequest')
            ->lockForUpdate()
            ->first();
    }
}
