<?php
namespace Modules\Booking\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Booking\Gateways\SepayClient;
use Modules\Booking\Gateways\VerifySepaySignature;
use Modules\Core\Models\Settings;
use Exception;

class SepayOAuthController extends Controller
{
    public function connect()
    {
        $state = bin2hex(random_bytes(16));
        session()->put('sepay_oauth_state', $state);

        $queryParams = http_build_query([
            'callback_url' => route('sepay.oauth.callback'),
            'state' => $state,
        ]);

        return redirect()->away("https://friendsofbotble.com/oauth/sepay/init?$queryParams");
    }

    public function callback(Request $request, VerifySepaySignature $verifySignature)
    {
        $validated = $request->validate([
            'access_token' => 'required|string',
            'refresh_token' => 'required|string',
            'expires_in' => 'required|integer',
            'state' => 'required|string',
            'signature' => 'required|string',
        ]);

        if (!$verifySignature($validated)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Settings::store('sepay_access_token', $validated['access_token']);
        Settings::store('sepay_refresh_token', $validated['refresh_token']);
        Settings::store('sepay_expired_at', date('Y-m-d H:i:s', time() + (int)$validated['expires_in']));
        Settings::store('sepay_connected_at', date('Y-m-d H:i:s'));

        return response()->json(['success' => true]);
    }

    public function getCallback()
    {
        return <<<HTML
            <script>
                if (window.opener) {
                    window.opener.location.reload();
                }
                window.close();
            </script>
HTML;
    }

    public function disconnect()
    {
        Settings::store('sepay_access_token', null);
        Settings::store('sepay_refresh_token', null);
        Settings::store('sepay_expired_at', null);
        Settings::store('sepay_connected_at', null);
        Settings::store('sepay_webhook_id', null);

        Cache::forget('sepay.profile');
        Cache::forget('sepay.bank-accounts');

        return response()->json([
            'success' => true,
            'message' => 'Ngắt kết nối với SePay thành công'
        ]);
    }
}
