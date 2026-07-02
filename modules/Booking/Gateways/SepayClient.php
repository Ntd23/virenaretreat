<?php
namespace Modules\Booking\Gateways;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Core\Models\Settings;

class SepayClient
{
    protected $fobUrl = 'https://friendsofbotble.com';

    public function isConnected(): bool
    {
        return setting_item('sepay_connected_at') !== null;
    }

    public function profile(): ?object
    {
        return Cache::remember('sepay.profile', 60 * 60, function () {
            return (object) $this->request('get', 'me');
        });
    }

    public function company(): ?array
    {
        return $this->request('get', 'company');
    }

    public function bankAccounts(): array
    {
        return $this->request('get', 'bank-accounts');
    }

    public function bankAccount($id): ?object
    {
        return Cache::remember("sepay.bank-account.$id", 60 * 60, function () use ($id) {
            return (object) $this->request('get', "bank-accounts/$id");
        });
    }

    public function bankSubAccounts(int $bankAccountId)
    {
        return $this->request('get', "bank-accounts/$bankAccountId/sub-accounts");
    }

    public function webhook(int $id): ?array
    {
        return $this->request('get', "webhooks/$id");
    }

    public function createWebhook(array $data): array
    {
        $apiKey = bin2hex(random_bytes(16));

        Settings::store('sepay_api_key', $apiKey);

        return $this->request('post', 'webhooks', array_merge([
            'name' => sprintf('SePay - %s', config('app.name')),
            'event_type' => 'In_only',
            'authen_type' => 'Api_Key',
            'api_key' => $apiKey,
            'webhook_url' => route('gateway.webhook', ['gateway' => 'sepay']),
            'is_verify_payment' => 1,
            'skip_if_no_code' => 1,
            'request_content_type' => 'Json',
            'only_va' => 0,
        ], $data));
    }

    public function updateWebhook(int $id, array $data): array
    {
        return $this->request('patch', "webhooks/$id", $data);
    }

    public function request(string $method, string $url, array $data = []): array
    {
        try {
            $accessToken = setting_item('sepay_access_token');
            $response = Http::baseUrl('https://my.sepay.vn/api/v1')
                ->withToken($accessToken)
                ->$method($url, $data);

            if ($response->unauthorized()) {
                try {
                    $this->refreshToken();

                    $accessToken = setting_item('sepay_access_token');
                    $response = Http::baseUrl('https://my.sepay.vn/api/v1')
                        ->withToken($accessToken)
                        ->$method($url, $data);
                } catch (Exception $e) {
                    Settings::store('sepay_access_token', null);
                    Settings::store('sepay_refresh_token', null);
                    Settings::store('sepay_expired_at', null);
                    Settings::store('sepay_connected_at', null);

                    Cache::forget('sepay.profile');
                    Cache::forget('sepay.bank-accounts');

                    throw new Exception('Token đã hết hạn. Vui lòng kết nối lại tài khoản SePay.');
                }
            }

            $data = $response->json();

            if (isset($data['status']) && $data['status'] !== 'success') {
                throw new Exception($data['message'] ?? $data['messages']['error'], $response->status());
            }

            return $data['data'] ?? [];
        } catch (Exception $e) {
            Log::error('SePay API error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function refreshToken(): void
    {
        $refreshToken = setting_item('sepay_refresh_token');

        if (! $refreshToken) {
            throw new Exception('Refresh token not found. Please reconnect your SePay account.');
        }

        $response = Http::post($this->fobUrl . '/oauth/sepay/token', [
            'refresh_token' => $refreshToken,
        ]);

        $data = $response->json();

        if (! isset($data['access_token']) || ! isset($data['refresh_token'])) {
            throw new Exception('Failed to refresh SePay token. Please reconnect your account.');
        }

        Settings::store('sepay_access_token', $data['access_token']);
        Settings::store('sepay_refresh_token', $data['refresh_token']);
        Settings::store('sepay_expired_at', date('Y-m-d H:i:s', time() + (int)$data['expires_in']));
    }
}
