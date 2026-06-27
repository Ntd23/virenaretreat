@php
    $client = new \Modules\Booking\Gateways\SepayClient();
    $isConnected = $client->isConnected();
    $profile = null;
    $bankAccounts = [];
    if ($isConnected) {
        try {
            $profile = $client->profile();
            $bankAccounts = $client->bankAccounts();
        } catch (\Exception $e) {
            $isConnected = false;
        }
    }
@endphp

@if(!$isConnected)
    <div class="p-4 border rounded bg-white text-center shadow-sm mb-4">
        <div class="mb-3">
            <i class="fa fa-link text-primary" style="font-size: 48px;"></i>
        </div>
        <h4 class="text-primary font-weight-bold mb-2">Kết nối với SePay</h4>
        <p class="text-muted mx-auto" style="max-width: 500px; font-size: 14px;">
            Kết nối tài khoản SePay của bạn qua OAuth2 để tự động đồng bộ tài khoản ngân hàng và kích hoạt tính năng tự động xác nhận giao dịch chuyển khoản.
        </p>
        <button type="button" class="btn btn-primary px-4 py-2 mt-2 font-weight-bold" onclick="openSepayOAuth()">
            <i class="fa fa-external-link"></i> Kết nối tài khoản SePay ngay
        </button>
    </div>
@else
    <div class="sepay-settings-wrapper">
        <div class="sepay-profile-info bg-light p-3 rounded mb-4 border d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if(!empty($profile->avatar))
                    <img src="{{ $profile->avatar }}" class="rounded-circle mr-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary mr-3 text-white d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; font-weight: bold; font-size: 20px;">
                        {{ substr($profile->first_name ?? 'U', 0, 1) }}
                    </div>
                @endif
                <div>
                    <h5 class="mb-1 font-weight-bold text-dark">
                        {{ ($profile->last_name ?? '') . ' ' . ($profile->first_name ?? '') }}
                        <span class="badge badge-success ml-2" style="font-size: 11px; padding: 4px 8px;">Đã kết nối</span>
                    </h5>
                    <small class="text-muted d-block"><i class="fa fa-envelope"></i> {{ $profile->email ?? '' }}</small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" onclick="disconnectSepay()">
                <i class="fa fa-unlink"></i> Ngắt kết nối
            </button>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">
                <input type="checkbox" name="g_sepay_enable" value="1" {{ setting_item('g_sepay_enable') == 1 ? 'checked' : '' }}> 
                {{ __('Bật cổng thanh toán SePay?') }}
            </label>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">{{ __('Tên Cổng Thanh Toán (Hiển thị ở frontend)') }}</label>
            <input type="text" name="g_sepay_name" class="form-control" value="{{ setting_item('g_sepay_name', 'Chuyển khoản Ngân hàng tự động') }}">
        </div>

        <div class="form-group">
            <label class="font-weight-bold">{{ __('Logo Cổng Thanh Toán') }}</label>
            <div class="form-controls">
                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('g_sepay_logo_id', setting_item('g_sepay_logo_id')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">{{ __('Tài khoản ngân hàng nhận tiền') }}</label>
            <select name="g_sepay_bank_account_id" class="form-control">
                <option value="">-- Chọn tài khoản ngân hàng --</option>
                @foreach($bankAccounts as $account)
                    <option value="{{ $account['id'] }}" {{ setting_item('g_sepay_bank_account_id') == $account['id'] ? 'selected' : '' }}>
                        {{ $account['bank']['short_name'] }} - {{ $account['account_number'] }} - {{ $account['account_holder_name'] }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Chọn tài khoản ngân hàng của bạn trên SePay để hệ thống tự động tạo webhook đồng bộ giao dịch chuyển khoản.</small>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">{{ __('Ghi chú thanh toán (Hiển thị ở trang cảm ơn)') }}</label>
            <textarea name="g_sepay_payment_note" class="form-control" rows="4">{{ setting_item('g_sepay_payment_note') }}</textarea>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">{{ __('Mô tả HTML tuỳ chỉnh (Hiển thị ở trang Checkout)') }}</label>
            <textarea name="g_sepay_html" class="has-ckeditor" cols="30" rows="10">{{ setting_item('g_sepay_html') }}</textarea>
        </div>
    </div>
@endif

<script>
    function openSepayOAuth() {
        const url = '{{ route('sepay.oauth.connect') }}';
        const width = 600;
        const height = 700;
        const top = (window.innerHeight - height) / 2;
        const left = (window.innerWidth - width) / 2;

        window.open(
            url,
            'sepayOAuthWindow',
            `width=${width},height=${height},top=${top},left=${left},toolbar=no,menubar=no,scrollbars=yes,resizable=yes,status=no`
        );
    }

    function disconnectSepay() {
        if (confirm('Bạn có chắc chắn muốn ngắt kết nối tài khoản SePay?')) {
            $.ajax({
                url: '{{ route('sepay.oauth.disconnect') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                }
            });
        }
    }
</script>
