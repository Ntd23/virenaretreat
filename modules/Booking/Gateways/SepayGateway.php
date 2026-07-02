<?php
namespace Modules\Booking\Gateways;

use App\Models\AdvertisementPayment;
use App\Models\AdvertisementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Modules\Core\Models\Settings;
use Exception;

class SepayGateway extends BaseGateway
{
    public $name = 'Chuyển khoản Ngân hàng tự động qua SePay';
    public $id = 'sepay';

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable SePay Gateway?')
            ],
            [
                'type'       => 'input',
                'id'         => 'name',
                'label'      => __('Custom Name'),
                'std'        => __("Chuyển khoản Ngân hàng tự động"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'textarea',
                'id'    => 'payment_note',
                'label' => __('Payment Note'),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
            // Khai báo các field SePay để hệ thống tự động lưu vào DB
            [
                'type'  => 'input',
                'id'    => 'bank_account_id',
                'label' => 'bank_account_id'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank_sub_account_id',
                'label' => 'bank_sub_account_id'
            ],
            [
                'type'  => 'input',
                'id'    => 'prefix',
                'label' => 'prefix'
            ],
            [
                'type'  => 'input',
                'id' => 'bank_display',
                'label' => 'bank_display'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank',
                'label' => 'bank'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank_short_name',
                'label' => 'bank_short_name'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank_brand_name',
                'label' => 'bank_brand_name'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank_account_number',
                'label' => 'bank_account_number'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank_account_holder',
                'label' => 'bank_account_holder'
            ],
            [
                'type'  => 'input',
                'id'    => 'bank_logo',
                'label' => 'bank_logo'
            ]
        ];
    }

    public function process(Request $request, $booking, $service)
    {
        if (in_array($booking->status, [
            $booking::PAID,
            $booking::COMPLETED,
            $booking::CANCELLED
        ])) {
            throw new Exception(__("Booking status does need to be paid"));
        }
        if (!$booking->pay_now) {
            throw new Exception(__("Booking total is zero. Can not process payment gateway!"));
        }

        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->amount = (float)$booking->pay_now;
        $payment->status = 'draft';
        $payment->save();

        $booking->status = $booking::UNPAID;
        $booking->payment_id = $payment->id;
        $booking->save();

        try {
            event(new BookingCreatedEvent($booking));
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
        }

        $service->afterPaymentProcess($booking, $this);

        response()->json([
            'url' => $booking->getDetailUrl()
        ])->send();
    }

    public function callbackPayment(Request $request)
    {
        // 1. Xác thực API Key từ Header Authorization
        $header = $request->header('Authorization', '');
        if (!str_contains($header, 'Apikey ')) {
            return response()->json(['message' => 'Unauthorized - Missing token'], 401);
        }

        $apiKey = trim(str_ireplace('Apikey ', '', $header));
        $storedApiKey = setting_item('sepay_api_key');

        if (empty($apiKey) || empty($storedApiKey) || !hash_equals($storedApiKey, $apiKey)) {
            return response()->json(['message' => 'Unauthorized - Invalid token'], 401);
        }

        // 2. Nhận dữ liệu giao dịch
        $content = $request->input('content', '');
        $transferAmount = (float)$request->input('transferAmount', 0);
        $transferType = $request->input('transferType', 'in');

        if (strtolower($transferType) === 'out') {
            // Nhận dạng nội dung chuyển tiền hoa hồng affiliate
            // Cú pháp: "Thanh toan hoa hong affiliate don hang [booking_id]"
            if (preg_match('/thanh toan hoa hong affiliate don hang (\d+)/i', $content, $matches)) {
                $bookingId = $matches[1];
                
                // Tìm dòng hoa hồng tương ứng ở trạng thái pending hoặc approved
                $commission = \Illuminate\Support\Facades\DB::table('affiliate_commissions')
                    ->where('booking_id', $bookingId)
                    ->whereIn('status', ['pending', 'approved'])
                    ->first();
                
                if ($commission) {
                    // Kiểm tra xem đơn đặt phòng tương ứng đã hoàn thành (completed) chưa để bảo mật
                    $booking = \Illuminate\Support\Facades\DB::table('bravo_bookings')->where('id', $bookingId)->first();
                    if (!$booking || $booking->status !== 'completed') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Booking not completed or not found for booking #' . $bookingId
                        ], 400);
                    }

                    // Cập nhật trạng thái sang paid
                    \Illuminate\Support\Facades\DB::table('affiliate_commissions')
                        ->where('id', $commission->id)
                        ->update([
                            'status' => 'paid',
                            'updated_at' => now()
                        ]);
                    
                    return response()->json([
                        'success' => true
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Commission not found or already paid for booking #' . $bookingId
                ], 400);
            }
            
            return response()->json(['success' => true]);
        }

        if (strtolower($transferType) !== 'in') {
            return response()->json(['success' => true, 'message' => 'Not incoming transfer. Ignore.']);
        }

        // 3. Tìm Booking khớp với nội dung chuyển khoản
        $booking = Booking::whereIn('status', [Booking::UNPAID, Booking::DRAFT])
            ->where(function($q) use($content) {
                $q->whereRaw('? LIKE CONCAT("%", code, "%")', [$content])
                  ->orWhere('id', $content);
            })->first();

        if (!$booking) {
            return $this->processAdvertisementPayment($request, $content, $transferAmount, $transferType);
        }

        // 4. Kiểm tra số tiền chuyển khoản (Cho phép sai số tolerance 1000đ)
        $expectedAmount = (float)$booking->pay_now;
        $tolerance = 1000;

        if ($transferAmount < ($expectedAmount - $tolerance)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient amount. Expected: ' . $expectedAmount . ', Transferred: ' . $transferAmount
            ], 400);
        }

        // 5. Cập nhật trạng thái thanh toán và đơn hàng
        $payment = $booking->payment;
        if ($payment) {
            $payment->status = 'completed';
            $payment->logs = json_encode($request->all());
            $payment->save();
        }

        $booking->paid += $transferAmount;
        $booking->markAsPaid();

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully for booking: ' . $booking->code
        ]);
    }

    protected function processAdvertisementPayment(Request $request, string $content, float $transferAmount, string $transferType)
    {
        $payload = $request->all();
        $code = (string) $request->input('code', '');
        $transactionId = $this->getSepayTransactionId($payload);
        $gateway = $request->input('gateway') ?: $request->input('gatewayName') ?: $request->input('bank_brand_name');
        $transactionDate = $request->input('transactionDate') ?: $request->input('transaction_date') ?: $request->input('time');

        if (AdvertisementPayment::where('sepay_transaction_id', $transactionId)->exists()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction already processed.',
            ]);
        }

        return DB::transaction(function () use ($payload, $code, $content, $transactionId, $transferType, $transferAmount, $gateway, $transactionDate) {
            if ($code === '' && $content === '') {
                Log::warning('SePay advertisement transaction missing transfer content/code.', [
                    'sepay_transaction_id' => $transactionId,
                    'transfer_type' => $transferType,
                    'amount' => $transferAmount,
                    'code' => $code,
                    'content' => $content,
                    'payload' => $payload,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Missing transfer content/code.',
                ], 400);
            }

            $payment = AdvertisementPayment::query()
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

            if (!$payment) {
                Log::warning('SePay advertisement transaction has no matching payment code.', [
                    'sepay_transaction_id' => $transactionId,
                    'transfer_type' => $transferType,
                    'amount' => $transferAmount,
                    'code' => $code,
                    'content' => $content,
                    'payload' => $payload,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Booking or advertisement payment not found matching content: ' . $content,
                ], 400);
            }

            $payment->update([
                'paid_amount' => $transferAmount,
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
                'success' => true,
                'message' => 'Advertisement payment is waiting for admin confirmation: ' . $payment->payment_code,
                'payment_status' => AdvertisementPayment::STATUS_WAITING_CONFIRM,
            ]);
        });
    }

    protected function getSepayTransactionId(array $payload)
    {
        $transactionId = data_get($payload, 'id')
            ?: data_get($payload, 'transactionId')
            ?: data_get($payload, 'referenceCode')
            ?: data_get($payload, 'reference_code');

        return $transactionId ?: 'payload_' . hash('sha256', json_encode($payload));
    }

    public function syncSepaySettings(array $values)
    {
        $bankAccountId = $values['g_sepay_bank_account_id'] ?? null;
        if (!$bankAccountId) {
            return $values;
        }

        $client = new SepayClient();
        if ($client->isConnected()) {
            // 1. Đồng bộ thông tin ngân hàng chi tiết từ SePay API
            $bankAccount = $client->bankAccount($bankAccountId);
            if ($bankAccount) {
                $bankDisplay = $values['g_sepay_bank_display'] ?? 'short_name';
                $bank = match ($bankDisplay) {
                    'full_name' => $bankAccount->bank['full_name'],
                    'short_name' => $bankAccount->bank['brand_name'],
                    'full_name_short_name' => "{$bankAccount->bank['full_name']} ({$bankAccount->bank['brand_name']})",
                    default => $bankAccount->bank['brand_name'],
                };

                $values['g_sepay_bank'] = $bank;
                $values['g_sepay_bank_short_name'] = $bankAccount->bank['short_name'];
                $values['g_sepay_bank_brand_name'] = $bankAccount->bank['brand_name'];
                $values['g_sepay_bank_account_number'] = $bankAccount->account_number;
                $values['g_sepay_bank_account_holder'] = $bankAccount->account_holder_name;
                $values['g_sepay_bank_logo'] = $bankAccount->bank['logo_url'];

                // Hỗ trợ Sub Account ảo nếu có cấu hình
                $bankSubAccountId = $values['g_sepay_bank_sub_account_id'] ?? null;
                if ($bankSubAccountId) {
                    $bankSubAccounts = $client->bankSubAccounts($bankAccount->id);
                    $bankSubAccount = collect($bankSubAccounts)
                        ->where('bank_account_id', $bankAccount->id)
                        ->where('id', $bankSubAccountId)
                        ->first();

                    if ($bankSubAccount) {
                        $values['g_sepay_bank_account_number'] = $bankSubAccount['account_number'];
                        if (!empty($bankSubAccount['account_holder_name'])) {
                            $values['g_sepay_bank_account_holder'] = $bankSubAccount['account_holder_name'];
                        }
                    }
                }
            }

            // 2. Đồng bộ Webhook tự động tạo/cập nhật trên SePay
            $webhookId = setting_item('sepay_webhook_id');
            $webhookData = [
                'bank_account_id' => $bankAccountId,
            ];

            try {
                $webhook = $this->handleWebhookUpdate($client, $webhookId, $webhookData);
                Settings::store('sepay_webhook_id', $webhook['id']);
            } catch (Exception $e) {
                if ($e->getCode() === 404) {
                    try {
                        $webhook = $client->createWebhook($webhookData);
                        Settings::store('sepay_webhook_id', $webhook['id']);
                    } catch (Exception $ex) {
                        Log::error('SePay Webhook creation failed: ' . $ex->getMessage());
                    }
                } else {
                    Log::error('SePay Webhook sync failed: ' . $e->getMessage());
                }
            }
        }

        return $values;
    }

    protected function handleWebhookUpdate(SepayClient $client, ?string $webhookId, array $data): array
    {
        if (!$webhookId) {
            return $client->createWebhook($data);
        }

        try {
            $webhook = $client->webhook((int)$webhookId);
        } catch (Exception $e) {
            $webhook = null;
        }

        if (!$webhook) {
            return $client->createWebhook($data);
        }

        if ($webhook['bank_account_id'] != $data['bank_account_id']) {
            $webhook = $client->updateWebhook((int)$webhookId, $data);
            $webhook['id'] = $webhookId;
        }

        return $webhook;
    }
}
