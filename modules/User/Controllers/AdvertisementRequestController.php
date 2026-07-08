<?php

namespace Modules\User\Controllers;

use App\Models\AdvertisementPayment;
use App\Models\AdvertisementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\FrontendController;

class AdvertisementRequestController extends FrontendController
{
    public function index(Request $request)
    {
        $query = AdvertisementRequest::query()
            ->with('payment')
            ->where('user_id', Auth::id())
            ->latest();

        $data = [
            'rows' => $query->paginate(10),
            'breadcrumbs' => [
                [
                    'name' => __('Đăng ký quảng cáo'),
                    'class' => 'active',
                ],
            ],
            'page_title' => __('Đăng ký quảng cáo'),
        ];

        return view('User::frontend.advertisement.index', $data);
    }

    public function create(Request $request)
    {
        $data = [
            'breadcrumbs' => [
                [
                    'name' => __('Đăng ký quảng cáo'),
                    'url' => route('user.advertisement.index'),
                ],
                [
                    'name' => __('Tạo yêu cầu'),
                    'class' => 'active',
                ],
            ],
            'page_title' => __('Tạo yêu cầu quảng cáo'),
        ];

        return view('User::frontend.advertisement.create', $data);
    }

    public function store(Request $request)
    {
        if (is_demo_mode()) {
            return back()->with('error', 'Demo mode: disabled');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_url' => 'nullable|url|max:255',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:255',
            'media_files' => 'nullable|array|max:10',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,webp|max:51200',
        ], [
            'media_files.*.mimes' => __('Hình ảnh quảng cáo chỉ được dùng định dạng jpg, jpeg, png, gif hoặc webp.'),
        ], [
            'customer_name' => __('Tên khách hàng'),
            'customer_email' => __('Email'),
            'customer_phone' => __('Số điện thoại'),
            'customer_address' => __('Địa chỉ'),
        ]);

        $data['media_urls'] = $this->storeMediaFiles($request);
        unset($data['media_files']);

        $advertisementRequest = DB::transaction(function () use ($data) {
            return AdvertisementRequest::create(array_merge($data, [
                'user_id' => Auth::id(),
                'status' => AdvertisementRequest::STATUS_PENDING_REVIEW,
            ]));
        });

        return redirect()
            ->route('user.advertisement.show', $advertisementRequest)
            ->with('success', __('Yêu cầu quảng cáo đã được gửi.'));
    }

    public function show(Request $request, AdvertisementRequest $advertisementRequest)
    {
        if ((int) $advertisementRequest->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $advertisementRequest->load('payment');

        $data = [
            'row' => $advertisementRequest,
            'gateways' => $this->getAvailablePaymentGateways(),
            'sepayQrUrl' => $advertisementRequest->payment
                ? ($advertisementRequest->payment->qr_url ?: $this->buildSepayQrUrl($advertisementRequest->payment->payment_code, $advertisementRequest->payment->amount))
                : null,
            'breadcrumbs' => [
                [
                    'name' => __('Đăng ký quảng cáo'),
                    'url' => route('user.advertisement.index'),
                ],
                [
                    'name' => $advertisementRequest->title,
                    'class' => 'active',
                ],
            ],
            'page_title' => $advertisementRequest->title,
        ];

        return view('User::frontend.advertisement.show', $data);
    }

    public function pay(Request $request, AdvertisementRequest $advertisementRequest)
    {
        if ((int) $advertisementRequest->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $gateways = $this->getAvailablePaymentGateways();
        $request->validate([
            'payment_gateway' => 'required|in:' . implode(',', array_keys($gateways)),
            'payment_receipt' => 'required_if:payment_gateway,sepay|nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ], [
            'payment_receipt.required_if' => __('Vui lòng gửi ảnh bill chuyển khoản.'),
            'payment_receipt.image' => __('Bill chuyển khoản phải là hình ảnh.'),
            'payment_receipt.mimes' => __('Bill chuyển khoản chỉ được dùng định dạng jpg, jpeg, png, gif hoặc webp.'),
        ]);

        if ($advertisementRequest->status !== AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT) {
            return redirect()->back()->with('error', __('Yêu cầu này chưa sẵn sàng thanh toán.'));
        }

        $payment = $advertisementRequest->payment;
        if (!$payment) {
            return redirect()->back()->with('error', __('Không tìm thấy thông tin thanh toán.'));
        }

        if (in_array($payment->payment_status, [
            AdvertisementPayment::STATUS_PAID,
        ], true)) {
            return redirect()->back()->with('error', __('Yêu cầu này đã được thanh toán.'));
        }

        $gatewayId = $request->input('payment_gateway');
        $receiptImageUrl = $this->storePaymentReceipt($request);

        DB::transaction(function () use ($advertisementRequest, $payment, $gatewayId, $receiptImageUrl) {
            $payment->update([
                'payment_method' => $gatewayId,
                'payment_status' => AdvertisementPayment::STATUS_WAITING_CONFIRM,
                'receipt_image_url' => $receiptImageUrl ?: $payment->receipt_image_url,
                'receipt_uploaded_at' => $receiptImageUrl ? now() : $payment->receipt_uploaded_at,
                'sepay_gateway' => $gatewayId === 'sepay'
                    ? (setting_item('g_sepay_bank_brand_name') ?: setting_item('g_sepay_bank_short_name') ?: setting_item('g_sepay_bank'))
                    : null,
                'qr_url' => $gatewayId === 'sepay'
                    ? $this->buildSepayQrUrl($payment->payment_code, $payment->amount)
                    : null,
            ]);

            $advertisementRequest->update([
                'status' => AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM,
            ]);
        });

        return redirect()->back()->with('success', __('Đã gửi xác nhận thanh toán. Vui lòng chờ admin kiểm tra và xác nhận.'));
    }

    public function uploadReceipt(Request $request, AdvertisementRequest $advertisementRequest)
    {
        if ((int) $advertisementRequest->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if (!in_array($advertisementRequest->status, [
            AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT,
            AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM,
        ], true)) {
            return redirect()->back()->with('error', __('Yêu cầu này không ở trạng thái có thể gửi bill thanh toán.'));
        }

        $request->validate([
            'payment_receipt' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ], [
            'payment_receipt.required' => __('Vui lòng gửi ảnh bill chuyển khoản.'),
            'payment_receipt.image' => __('Bill chuyển khoản phải là hình ảnh.'),
            'payment_receipt.mimes' => __('Bill chuyển khoản chỉ được dùng định dạng jpg, jpeg, png, gif hoặc webp.'),
        ]);

        $payment = $advertisementRequest->payment;
        if (!$payment) {
            return redirect()->back()->with('error', __('Không tìm thấy thông tin thanh toán.'));
        }

        $receiptImageUrl = $this->storePaymentReceipt($request);

        DB::transaction(function () use ($advertisementRequest, $payment, $receiptImageUrl) {
            $payment->update([
                'payment_status' => AdvertisementPayment::STATUS_WAITING_CONFIRM,
                'receipt_image_url' => $receiptImageUrl,
                'receipt_uploaded_at' => now(),
            ]);

            $advertisementRequest->update([
                'status' => AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM,
            ]);
        });

        return redirect()->back()->with('success', __('Đã gửi ảnh bill chuyển khoản cho admin kiểm tra.'));
    }

    protected function storeMediaFiles(Request $request)
    {
        if (!$request->hasFile('media_files')) {
            return [];
        }

        $urls = [];

        foreach ($request->file('media_files', []) as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $folder = 'advertisements/' . date('Y/m');
            $filename = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
            $path = $file->storeAs($folder, $filename, 'uploads');
            $urls[] = 'uploads/' . ltrim($path, '/');
        }

        return $urls;
    }

    protected function storePaymentReceipt(Request $request)
    {
        if (!$request->hasFile('payment_receipt') || !$request->file('payment_receipt')->isValid()) {
            return null;
        }

        $file = $request->file('payment_receipt');
        $folder = 'advertisement-bills/' . date('Y/m');
        $filename = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs($folder, $filename, 'uploads');

        return 'uploads/' . ltrim($path, '/');
    }

    protected function getAvailablePaymentGateways()
    {
        $gateways = [];
        foreach (get_payment_gateways() as $key => $gatewayClass) {
            if (!class_exists($gatewayClass)) {
                continue;
            }
            $gateway = new $gatewayClass($key);
            if ($gateway->isAvailable()) {
                $gateways[$key] = $gateway;
            }
        }

        return $gateways;
    }

    protected function buildSepayQrUrl($paymentCode, $amount)
    {
        $template = setting_item('g_sepay_qr_url_template') ?: env('SEPAY_QR_URL_TEMPLATE');
        if ($template) {
            return str_replace(['{payment_code}', '{amount}'], [rawurlencode($paymentCode), $amount], $template);
        }

        $bank = setting_item('g_sepay_bank_brand_name') ?: setting_item('g_sepay_bank_short_name') ?: env('SEPAY_BANK_BIN');
        $account = setting_item('g_sepay_bank_account_number') ?: env('SEPAY_ACCOUNT_NUMBER');

        if ($bank && $account) {
            return 'https://qr.sepay.vn/img?acc=' . rawurlencode($account)
                . '&bank=' . rawurlencode($bank)
                . '&amount=' . rawurlencode($amount)
                . '&des=' . rawurlencode($paymentCode);
        }

        return null;
    }

}
