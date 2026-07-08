<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdvertisementPayment;
use App\Models\AdvertisementPosition;
use App\Models\AdvertisementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\AdminController;

class AdminAdvertisementRequestController extends AdminController
{
    public function __construct()
    {
        if (Route::has('admin.advertisements.index')) {
            $this->setActiveMenu(route('admin.advertisements.index'));
        }
    }

    public function checkPermission($permission = false)
    {
        if ($permission) {
            $user = Auth::user() ?: Auth::guard('api')->user();
            if (!$user || !$user->hasPermission($permission)) {
                abort(403);
            }
        }
    }

    public function index(Request $request)
    {
        $this->checkPermission('dashboard_access');

        $request->merge([
            'exclude_main_statuses' => true,
        ]);

        $rows = $this->buildIndexQuery($request)->paginate((int) $request->input('per_page', 20));

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'data' => $rows,
            ]);
        }

        return view('admin.advertisements.index', [
            'rows' => $rows,
            'statuses' => self::requestStatusesForList('all'),
            'paymentStatuses' => self::paymentStatuses(),
            'listType' => 'all',
            'pageTitle' => __('Yêu cầu quảng cáo'),
            'breadcrumbs' => [
                ['name' => __('Yêu cầu quảng cáo'), 'class' => 'active'],
            ],
        ]);
    }

    public function waitingList(Request $request)
    {
        $this->checkPermission('dashboard_access');

        $request->merge([
            'status' => AdvertisementRequest::STATUS_WAITING_QUEUE,
        ]);

        $rows = $this->buildIndexQuery($request)->paginate((int) $request->input('per_page', 20));

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'data' => $rows,
            ]);
        }

        return view('admin.advertisements.index', [
            'rows' => $rows,
            'statuses' => self::requestStatusesForList('waiting'),
            'paymentStatuses' => self::paymentStatuses(),
            'listType' => 'waiting',
            'pageTitle' => __('Danh sách chờ quảng cáo'),
            'breadcrumbs' => [
                ['name' => __('Danh sách chờ quảng cáo'), 'class' => 'active'],
            ],
        ]);
    }

    public function runningList(Request $request)
    {
        $this->checkPermission('dashboard_access');

        $request->merge([
            'status' => AdvertisementRequest::STATUS_RUNNING,
        ]);

        $rows = $this->buildIndexQuery($request)->paginate((int) $request->input('per_page', 20));

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'data' => $rows,
            ]);
        }

        return view('admin.advertisements.index', [
            'rows' => $rows,
            'statuses' => self::requestStatusesForList('running'),
            'paymentStatuses' => self::paymentStatuses(),
            'listType' => 'running',
            'pageTitle' => __('Quảng cáo đang chạy'),
            'breadcrumbs' => [
                ['name' => __('Quảng cáo đang chạy'), 'class' => 'active'],
            ],
        ]);
    }

    public function pricing()
    {
        $this->checkPermission('dashboard_access');

        return view('admin.advertisements.pricing', [
            'positions' => AdvertisementPosition::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'breadcrumbs' => [
                ['name' => __('Yêu cầu quảng cáo'), 'url' => route('admin.advertisements.index')],
                ['name' => __('Giá tiền quảng cáo'), 'class' => 'active'],
            ],
        ]);
    }

    public function updatePricing(Request $request)
    {
        $this->checkPermission('dashboard_access');

        $data = $request->validate([
            'positions' => 'required|array',
            'positions.*.base_price' => 'nullable|numeric|min:0',
            'positions.*.is_active' => 'nullable|boolean',
        ]);

        $positions = AdvertisementPosition::query()->get()->keyBy('id');

        DB::transaction(function () use ($data, $positions) {
            foreach ($data['positions'] as $id => $values) {
                $position = $positions->get((int) $id);
                if (!$position) {
                    continue;
                }

                $position->update([
                    'base_price' => $values['base_price'] ?? 0,
                    'max_active_ads' => $this->getFixedPositionQuantity($position),
                    'is_active' => !empty($values['is_active']),
                ]);
            }
        });

        return redirect()
            ->route('admin.advertisements.pricing')
            ->with('success', __('Cập nhật giá tiền quảng cáo thành công.'));
    }

    protected function getFixedPositionQuantity(AdvertisementPosition $position)
    {
        return $position->code === 'large_banner' ? 3 : 1;
    }

    public function show(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        $advertisementRequest->load(['user', 'payment', 'approver', 'confirmer', 'position']);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'data' => $advertisementRequest,
            ]);
        }

        $advertisementPositions = AdvertisementPosition::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($position) {
                $position->active_ads_count = $this->getPositionWaitingQueueCount($position->id);
                $position->running_ads_count = $this->getPositionRunningCount($position->id);
                $position->fixed_quantity = $this->getFixedPositionQuantity($position);
                $position->is_full = $position->active_ads_count >= $position->fixed_quantity;

                return $position;
            });
        $selectedPositionIds = collect($advertisementRequest->advertisement_position_ids ?: [])
            ->map(function ($id) {
                return (int) $id;
            })
            ->filter()
            ->values()
            ->all();
        if (empty($selectedPositionIds) && $advertisementRequest->advertisement_position_id) {
            $selectedPositionIds = [(int) $advertisementRequest->advertisement_position_id];
        }

        return view('admin.advertisements.show', [
            'row' => $advertisementRequest,
            'advertisementPositions' => $advertisementPositions,
            'selectedPositionIds' => $selectedPositionIds,
            'currentPositionRunningCount' => $advertisementRequest->advertisement_position_id
                ? $this->getPositionRunningCount($advertisementRequest->advertisement_position_id)
                : 0,
            'currentPositionRunningLimit' => $advertisementRequest->position
                ? $this->getFixedPositionQuantity($advertisementRequest->position)
                : 0,
            'isCurrentPositionRunningFull' => $advertisementRequest->position
                ? $this->isPositionRunningFull($advertisementRequest->position)
                : false,
            'statuses' => self::requestStatuses(),
            'paymentStatuses' => self::paymentStatuses(),
            'breadcrumbs' => [
                ['name' => __('Yêu cầu quảng cáo'), 'url' => route('admin.advertisements.index')],
                ['name' => '#' . $advertisementRequest->id, 'class' => 'active'],
            ],
        ]);
    }

    public function approve(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        $data = $request->validate([
            'advertisement_position_ids' => 'required|array|min:1',
            'advertisement_position_ids.*' => 'exists:advertisement_positions,id',
            'selected_media_url' => 'nullable|string',
            'selected_media_urls' => 'nullable|array',
            'selected_media_urls.*' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'admin_note' => 'nullable|string',
        ]);

        if ($advertisementRequest->status !== AdvertisementRequest::STATUS_PENDING_REVIEW) {
            return $this->respondError($request, __('Chỉ yêu cầu đang chờ duyệt mới có thể được duyệt.'));
        }

        if ($advertisementRequest->isPriceLocked()) {
            return $this->respondError($request, __('Không thể sửa giá sau khi đã thanh toán, đang chạy hoặc đã hoàn thành.'));
        }

        $positions = AdvertisementPosition::query()
            ->where('is_active', true)
            ->whereIn('id', $data['advertisement_position_ids'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        if ($positions->count() !== count(array_unique($data['advertisement_position_ids']))) {
            return $this->respondError($request, __('Có vị trí quảng cáo không hợp lệ hoặc đang tắt.'));
        }

        foreach ($positions as $position) {
            if ($this->isPositionFull($position)) {
                return $this->respondError($request, __('Vị trí :name đã đủ số lượng trong danh sách chờ.', ['name' => $position->name]));
            }
        }

        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = Carbon::parse($data['end_date'])->startOfDay();
        $durationDays = $startDate->diffInDays($endDate) + 1;

        $quote = $this->calculatePositionsQuoteByDays($positions, $durationDays);
        $data['original_price'] = $quote['original_price'];
        $data['discount_amount'] = $quote['discount_amount'];
        $data['final_price'] = $quote['final_price'];
        $data['duration'] = $durationDays . ' ngày';
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        $data['advertisement_position_ids'] = $positions->pluck('id')->map(function ($id) {
            return (int) $id;
        })->values()->all();
        $data['advertisement_position_id'] = $data['advertisement_position_ids'][0];
        $data['placement'] = optional($positions->first())->placement ?: optional($positions->first())->code;
        $data['selected_media_urls'] = $this->normalizeSelectedMediaUrls(
            $data['selected_media_urls'] ?? [],
            $positions,
            $advertisementRequest
        );
        $data['selected_media_url'] = collect($data['selected_media_urls'])->filter()->first()
            ?: $this->normalizeSelectedMediaUrl($data['selected_media_url'] ?? null, $advertisementRequest);

        if ($data['final_price'] <= 0) {
            return $this->respondError($request, __('Thành tiền phải lớn hơn 0.'));
        }

        $row = DB::transaction(function () use ($advertisementRequest, $data) {
            $paymentCode = optional($advertisementRequest->payment)->payment_code ?: $this->generatePaymentCode($advertisementRequest);

            $advertisementRequest->update([
                'status' => AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT,
                'advertisement_position_id' => $data['advertisement_position_id'],
                'advertisement_position_ids' => $data['advertisement_position_ids'],
                'placement' => $data['placement'],
                'selected_media_url' => $data['selected_media_url'],
                'selected_media_urls' => $data['selected_media_urls'],
                'duration' => $data['duration'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'original_price' => $data['original_price'],
                'discount_amount' => $data['discount_amount'],
                'final_price' => $data['final_price'],
                'admin_note' => $data['admin_note'] ?? null,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            AdvertisementPayment::updateOrCreate(
                ['advertisement_request_id' => $advertisementRequest->id],
                [
                    'user_id' => $advertisementRequest->user_id,
                    'payment_code' => $paymentCode,
                    'amount' => $data['final_price'],
                    'paid_amount' => 0,
                    'payment_method' => 'sepay',
                    'payment_status' => AdvertisementPayment::STATUS_PENDING,
                    'sepay_gateway' => setting_item('g_sepay_bank_brand_name') ?: setting_item('g_sepay_bank_short_name') ?: setting_item('g_sepay_bank'),
                    'qr_url' => $this->buildSepayQrUrl($paymentCode, $data['final_price']),
                ]
            );

            return $advertisementRequest->fresh(['user', 'payment']);
        });

        return $this->respondSuccess($request, __('Advertisement request approved and quotation sent.'), $row);
    }

    public function reject(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        $data = $request->validate([
            'reject_reason' => 'required|string',
        ]);

        if ($advertisementRequest->status !== AdvertisementRequest::STATUS_PENDING_REVIEW) {
            return $this->respondError($request, __('Only pending requests can be rejected.'));
        }

        $advertisementRequest->update([
            'status' => AdvertisementRequest::STATUS_REJECTED,
            'reject_reason' => $data['reject_reason'],
            'rejection_reason' => $data['reject_reason'],
        ]);

        return $this->respondSuccess($request, __('Advertisement request rejected.'), $advertisementRequest->fresh(['user', 'payment']));
    }

    public function confirmPayment(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        $data = $request->validate([
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        if ($advertisementRequest->status !== AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM) {
            return $this->respondError($request, __('Chỉ yêu cầu đang chờ xác nhận thanh toán mới có thể xác nhận.'));
        }

        if (!$advertisementRequest->payment) {
            return $this->respondError($request, __('Không tìm thấy thông tin thanh toán.'));
        }

        $paidAmount = isset($data['paid_amount'])
            ? (float) $data['paid_amount']
            : (float) $advertisementRequest->payment->paid_amount;

        if ($paidAmount !== (float) $advertisementRequest->payment->amount) {
            return $this->respondError($request, __('Số tiền thực nhận chưa đúng với số tiền cần thanh toán.'));
        }

        $row = DB::transaction(function () use ($advertisementRequest, $paidAmount) {
            $payment = AdvertisementPayment::where('advertisement_request_id', $advertisementRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            $payment->update([
                'paid_amount' => $paidAmount,
                'payment_status' => AdvertisementPayment::STATUS_PAID,
                'paid_at' => now(),
            ]);

            $advertisementRequest->update([
                'status' => AdvertisementRequest::STATUS_PAYMENT_RECEIVED,
            ]);

            return $advertisementRequest->fresh(['user', 'payment']);
        });

        return $this->respondSuccess($request, __('Đã xác nhận thanh toán thành công.'), $row);
    }

    public function confirmRunning(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        if ($advertisementRequest->status !== AdvertisementRequest::STATUS_WAITING_QUEUE) {
            return $this->respondError($request, __('Chỉ quảng cáo trong danh sách chờ mới có thể chuyển sang đang chạy.'));
        }

        if (!$advertisementRequest->position) {
            return $this->respondError($request, __('Yêu cầu chưa có vị trí quảng cáo.'));
        }

        foreach (AdvertisementRequest::getAdvertisementPositionIds($advertisementRequest) as $positionId) {
            if ($this->getPositionRunningCount($positionId, $advertisementRequest->id) >= $this->getFixedPositionQuantityById($positionId)) {
                return $this->respondError($request, __('Một vị trí quảng cáo đã đủ số lượng quảng cáo đang chạy.'));
            }
        }

        $advertisementRequest->update([
            'status' => AdvertisementRequest::STATUS_RUNNING,
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
            'running_at' => now(),
        ]);

        return $this->respondSuccess($request, __('Advertisement is now running.'), $advertisementRequest->fresh(['user', 'payment']));
    }

    public function addToWaitingQueue(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        if (
            $advertisementRequest->status !== AdvertisementRequest::STATUS_PAYMENT_RECEIVED
            || optional($advertisementRequest->payment)->payment_status !== AdvertisementPayment::STATUS_PAID
        ) {
            return $this->respondError($request, __('Chỉ yêu cầu đã xác nhận thanh toán thành công mới có thể cho vào danh sách chờ.'));
        }

        if (!$advertisementRequest->position) {
            return $this->respondError($request, __('Yêu cầu chưa có vị trí quảng cáo.'));
        }

        foreach (AdvertisementRequest::getAdvertisementPositionIds($advertisementRequest) as $positionId) {
            if ($this->getPositionWaitingQueueCount($positionId, $advertisementRequest->id) >= $this->getFixedPositionQuantityById($positionId)) {
                return $this->respondError($request, __('Một vị trí quảng cáo đã đủ số lượng trong danh sách chờ.'));
            }
        }

        $advertisementRequest->update([
            'status' => AdvertisementRequest::STATUS_WAITING_QUEUE,
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
        ]);

        $promotedCount = AdvertisementRequest::promoteWaitingForEmptyPositions(Auth::id());
        $message = $promotedCount > 0
            ? __('Đã cho quảng cáo vào danh sách chờ và tự động bật quảng cáo đang chạy.')
            : __('Đã cho quảng cáo vào danh sách chờ.');

        return $this->respondSuccess($request, $message, $advertisementRequest->fresh(['user', 'payment']));
    }

    public function complete(Request $request, AdvertisementRequest $advertisementRequest)
    {
        $this->checkPermission('dashboard_access');

        if ($advertisementRequest->status !== AdvertisementRequest::STATUS_RUNNING) {
            return $this->respondError($request, __('Chỉ quảng cáo đang chạy mới có thể đánh dấu hết hạn.'));
        }

        $result = $advertisementRequest->completeAndPromoteNext(Auth::id());
        $message = $result['promoted']
            ? __('Đã đánh dấu quảng cáo hết hạn và bật quảng cáo tiếp theo trong danh sách chờ.')
            : __('Đã đánh dấu quảng cáo hết hạn.');

        return $this->respondSuccess($request, $message, $result['completed']);
    }

    protected function buildIndexQuery(Request $request)
    {
        $query = AdvertisementRequest::query()->with(['user', 'payment', 'position'])->latest();

        $excludedMainStatuses = [
            AdvertisementRequest::STATUS_WAITING_QUEUE,
            AdvertisementRequest::STATUS_RUNNING,
        ];

        if ($status = $request->query('status')) {
            if ($request->input('exclude_main_statuses') && in_array($status, $excludedMainStatuses, true)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('status', $status);
            }
        } elseif ($request->input('exclude_main_statuses')) {
            $query->whereNotIn('status', $excludedMainStatuses);
        }

        if ($search = trim((string) $request->query('s'))) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', $search)
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_email', 'like', '%' . $search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $search . '%')
                    ->orWhere('customer_address', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('email', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('business_name', 'like', '%' . $search . '%')
                            ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $search . '%');
                    });
            });
        }

        if ($createdFrom = $request->query('created_from')) {
            $query->whereDate('created_at', '>=', $createdFrom);
        }

        if ($createdTo = $request->query('created_to')) {
            $query->whereDate('created_at', '<=', $createdTo);
        }

        return $query;
    }

    public static function requestStatuses()
    {
        return [
            AdvertisementRequest::STATUS_PENDING_REVIEW => __('Chờ admin duyệt'),
            AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT => __('Đã duyệt, chờ thanh toán'),
            AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM => __('Đã thanh toán, chờ admin xác nhận'),
            AdvertisementRequest::STATUS_PAYMENT_RECEIVED => __('Admin xác nhận đã nhận tiền'),
            AdvertisementRequest::STATUS_WAITING_QUEUE => __('Đang trong danh sách chờ'),
            AdvertisementRequest::STATUS_RUNNING => __('Đang chạy'),
            AdvertisementRequest::STATUS_COMPLETED => __('Hết hạn'),
            AdvertisementRequest::STATUS_REJECTED => __('Từ chối'),
            AdvertisementRequest::STATUS_CANCELLED => __('Đã huỷ'),
        ];
    }

    public static function requestStatusesForList($listType)
    {
        $statuses = self::requestStatuses();

        if ($listType === 'waiting') {
            return [
                AdvertisementRequest::STATUS_WAITING_QUEUE => $statuses[AdvertisementRequest::STATUS_WAITING_QUEUE],
            ];
        }

        if ($listType === 'running') {
            return [
                AdvertisementRequest::STATUS_RUNNING => $statuses[AdvertisementRequest::STATUS_RUNNING],
            ];
        }

        if ($listType === 'all') {
            unset(
                $statuses[AdvertisementRequest::STATUS_WAITING_QUEUE],
                $statuses[AdvertisementRequest::STATUS_RUNNING]
            );
        }

        return $statuses;
    }

    public static function paymentStatuses()
    {
        return [
            AdvertisementPayment::STATUS_PENDING => __('Chờ thanh toán'),
            AdvertisementPayment::STATUS_WAITING_CONFIRM => __('Chờ admin xác nhận thanh toán'),
            AdvertisementPayment::STATUS_PAID => __('Đã thanh toán'),
            AdvertisementPayment::STATUS_FAILED => __('Thanh toán lỗi'),
        ];
    }

    protected function generatePaymentCode(AdvertisementRequest $advertisementRequest)
    {
        do {
            $code = 'ADS' . $advertisementRequest->id . strtoupper(Str::random(6));
        } while (AdvertisementPayment::where('payment_code', $code)->exists());

        return $code;
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

    protected function calculatePositionsQuoteByDays($positions, $days)
    {
        $days = max(1, (int) $days);
        $originalPrice = $positions->sum(function ($position) {
            return (float) $position->base_price;
        }) * $days;

        return [
            'original_price' => $originalPrice,
            'discount_amount' => 0,
            'final_price' => max(0, round($originalPrice, 2)),
        ];
    }

    protected function normalizeSelectedMediaUrl($selectedMediaUrl, AdvertisementRequest $advertisementRequest)
    {
        $selectedMediaUrl = trim((string) $selectedMediaUrl);
        $mediaUrls = collect($advertisementRequest->media_urls ?: [])
            ->map(function ($url) {
                return (string) $url;
            })
            ->filter()
            ->values();

        if ($selectedMediaUrl === '') {
            return $mediaUrls->first();
        }

        return $mediaUrls->contains($selectedMediaUrl) ? $selectedMediaUrl : $mediaUrls->first();
    }

    protected function normalizeSelectedMediaUrls($selectedMediaUrls, $positions, AdvertisementRequest $advertisementRequest)
    {
        $selectedMediaUrls = is_array($selectedMediaUrls) ? $selectedMediaUrls : [];
        $mediaUrls = collect($advertisementRequest->media_urls ?: [])
            ->map(function ($url) {
                return (string) $url;
            })
            ->filter()
            ->values();

        if ($mediaUrls->isEmpty()) {
            return [];
        }

        $fallbackMediaUrl = $mediaUrls->first();
        $normalized = [];

        foreach ($positions as $position) {
            $selectedMediaUrl = trim((string) ($selectedMediaUrls[$position->id] ?? ''));
            $normalized[(string) $position->id] = $mediaUrls->contains($selectedMediaUrl)
                ? $selectedMediaUrl
                : $fallbackMediaUrl;
        }

        return $normalized;
    }

    protected function isPositionFull(AdvertisementPosition $position, $excludeRequestId = null)
    {
        return $this->getPositionWaitingQueueCount($position->id, $excludeRequestId) >= $this->getFixedPositionQuantity($position);
    }

    protected function isPositionRunningFull(AdvertisementPosition $position, $excludeRequestId = null)
    {
        return $this->getPositionRunningCount($position->id, $excludeRequestId) >= $this->getFixedPositionQuantity($position);
    }

    protected function getFixedPositionQuantityById($positionId)
    {
        $position = AdvertisementPosition::query()->find($positionId);

        return $position ? $this->getFixedPositionQuantity($position) : 1;
    }

    protected function getPositionRunningCount($positionId, $excludeRequestId = null)
    {
        return AdvertisementRequest::queryForPosition($positionId)
            ->where('status', AdvertisementRequest::STATUS_RUNNING)
            ->when($excludeRequestId, function ($query) use ($excludeRequestId) {
                $query->where('id', '<>', $excludeRequestId);
            })
            ->count();
    }

    protected function getPositionWaitingQueueCount($positionId, $excludeRequestId = null)
    {
        return AdvertisementRequest::queryForPosition($positionId)
            ->where('status', AdvertisementRequest::STATUS_WAITING_QUEUE)
            ->when($excludeRequestId, function ($query) use ($excludeRequestId) {
                $query->where('id', '<>', $excludeRequestId);
            })
            ->count();
    }

    protected function respondSuccess(Request $request, $message, $data = null)
    {
        if ($request->expectsJson()) {
            return response()->json(['status' => true, 'message' => $message, 'data' => $data]);
        }

        return redirect()->back()->with('success', $message);
    }

    protected function respondError(Request $request, $message, $status = 422)
    {
        if ($request->expectsJson()) {
            return response()->json(['status' => false, 'message' => $message], $status);
        }

        return redirect()->back()->with('error', $message);
    }
}
