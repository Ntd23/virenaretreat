@php
    $bankShortName = $gateway->getOption('bank_short_name');
    $bankAccountNumber = $gateway->getOption('bank_account_number');
    $bankAccountHolder = $gateway->getOption('bank_account_holder');
    $amount = (float) $booking->pay_now;
    $chargeId = $booking->code;
    $qrCodeUrl = "https://qr.sepay.vn/img?bank=" . urlencode($bankShortName) . "&acc=" . urlencode($bankAccountNumber) . "&template=compact&amount=" . urlencode($amount) . "&des=" . urlencode($chargeId);
@endphp

<div class="sepay-container">
    <div class="sepay-card">
        <div class="sepay-heading">
            <i class="fa fa-credit-card"></i>
            <span>Thông tin chuyển khoản qua SePay</span>
        </div>

        <div class="row align-items-center">
            <div class="col-md-5 sepay-qr-container text-center mb-3 mb-md-0">
                <div class="sepay-qr-caption mb-2">Quét mã QR bằng ứng dụng ngân hàng để thanh toán nhanh</div>
                <div class="sepay-qr-code-wrapper">
                    <img src="{{ $qrCodeUrl }}" alt="VietQR SePay Code" class="img-fluid sepay-qr-code" id="qrCodeImage">
                </div>
            </div>

            <div class="col-md-7 sepay-details">
                @if($bankLogo = $gateway->getOption('bank_logo'))
                    <div class="mb-3">
                        <img src="{{ $bankLogo }}" alt="{{ $bankShortName }}" class="sepay-bank-logo" style="max-height: 40px; object-fit: contain;">
                    </div>
                @endif
                <div class="sepay-detail-row d-flex justify-content-between py-2 border-bottom">
                    <span class="sepay-detail-label text-muted">Ngân Hàng</span>
                    <span class="sepay-detail-value font-weight-bold">{{ $bankShortName }}</span>
                </div>
                <div class="sepay-detail-row d-flex justify-content-between py-2 border-bottom">
                    <span class="sepay-detail-label text-muted">Chủ Tài Khoản</span>
                    <span class="sepay-detail-value font-weight-bold text-uppercase">{{ $bankAccountHolder }}</span>
                </div>
                <div class="sepay-detail-row d-flex justify-content-between py-2 border-bottom align-items-center">
                    <span class="sepay-detail-label text-muted">Số Tài Khoản</span>
                    <span class="sepay-detail-value font-weight-bold d-flex align-items-center">
                        <span id="sepay-account-number">{{ $bankAccountNumber }}</span>
                        <button class="btn btn-sm btn-link p-0 ml-2 sepay-copy-btn" data-clipboard="{{ $bankAccountNumber }}" title="Copy số tài khoản">
                            <i class="fa fa-clone text-secondary"></i>
                        </button>
                    </span>
                </div>
                <div class="sepay-detail-row d-flex justify-content-between py-2 border-bottom align-items-center">
                    <span class="sepay-detail-label text-muted">Nội Dung Chuyển Khoản</span>
                    <span class="sepay-detail-value font-weight-bold text-danger d-flex align-items-center">
                        <span id="sepay-charge-id">{{ $chargeId }}</span>
                        <button class="btn btn-sm btn-link p-0 ml-2 sepay-copy-btn" data-clipboard="{{ $chargeId }}" title="Copy nội dung">
                            <i class="fa fa-clone text-danger"></i>
                        </button>
                    </span>
                </div>
                <div class="sepay-detail-row d-flex justify-content-between py-2 border-bottom align-items-center">
                    <span class="sepay-detail-label text-muted">Số Tiền Giao Dịch</span>
                    <span class="sepay-detail-value font-weight-bold text-primary d-flex align-items-center" style="font-size: 16px;">
                        <span>{{ format_money($amount) }}</span>
                        <button class="btn btn-sm btn-link p-0 ml-2 sepay-copy-btn" data-clipboard="{{ $amount }}" title="Copy số tiền">
                            <i class="fa fa-clone text-primary"></i>
                        </button>
                    </span>
                </div>
                
                <div class="sepay-warning mt-3 p-3 bg-light border-left-warning rounded">
                    <small class="text-secondary d-block">
                        <i class="fa fa-exclamation-triangle text-warning mr-1"></i>
                        <strong>Lưu ý quan trọng:</strong> Vui lòng nhập đúng chính xác nội dung chuyển khoản <strong>{{ $chargeId }}</strong> để hệ thống tự động duyệt đặt phòng ngay lập tức.
                    </small>
                </div>
            </div>
        </div>

        <div class="sepay-loading mt-4 p-3 bg-light text-center rounded border" id="sepay-pending-box">
            <div class="d-flex align-items-center justify-content-center">
                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="width: 1rem; height: 1rem;">
                    <span class="sr-only">Loading...</span>
                </div>
                <span class="font-weight-bold text-dark">Đang chờ bạn thanh toán...</span>
            </div>
            <div class="text-muted mt-1" style="font-size: 12px;">Hệ thống đang tự động kiểm tra giao dịch chuyển khoản của bạn</div>
        </div>

        <div class="sepay-success mt-4 p-4 text-center rounded border border-success bg-light" id="sepay-success-box" style="display: none;">
            <i class="fa fa-check-circle text-success" style="font-size: 40px;"></i>
            <h4 class="text-success font-weight-bold mt-2">Thanh toán thành công!</h4>
            <p class="text-muted mb-0" style="font-size: 13px;">Đặt phòng của bạn đã được xác nhận tự động. Trang web sẽ tải lại sau giây lát...</p>
        </div>
    </div>
</div>

<style>
    .sepay-container {
        margin-bottom: 30px;
    }
    .sepay-card {
        background-color: #ffffff;
        border: 1px solid #e7e7e7;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .sepay-heading {
        font-size: 18px;
        font-weight: 700;
        color: #0f294d;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 12px;
    }
    .sepay-heading i {
        color: #003b95;
    }
    .sepay-qr-code-wrapper {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        background: #ffffff;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .sepay-qr-code {
        max-width: 200px;
        height: auto;
    }
    .sepay-qr-caption {
        font-size: 12px;
        color: #718096;
        line-height: 1.4;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .sepay-copy-btn {
        transition: transform 0.1s ease;
    }
    .sepay-copy-btn:active {
        transform: scale(0.85);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nút Copy thông tin chuyển khoản
        const copyBtns = document.querySelectorAll('.sepay-copy-btn');
        copyBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const text = this.getAttribute('data-clipboard');
                
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(() => {
                        showSuccessCopy(this);
                    });
                } else {
                    // Fallback
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'absolute';
                    textArea.style.left = '-9999px';
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showSuccessCopy(this);
                    } catch (err) {
                        console.error('Không thể copy', err);
                    }
                    document.body.removeChild(textArea);
                }
            });
        });

        function showSuccessCopy(element) {
            const icon = element.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fa fa-check text-success';
            setTimeout(() => {
                icon.className = originalClass;
            }, 1500);
        }

        // Logic Ajax Polling kiểm tra trạng thái thanh toán
        const checkUrl = "{{ route('sepay.check-status', ['code' => $booking->code]) }}";
        const intervalId = setInterval(function() {
            fetch(checkUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.is_paid === true) {
                        // Dừng polling
                        clearInterval(intervalId);
                        
                        // Hiển thị box thành công, ẩn box chờ
                        document.getElementById('sepay-pending-box').style.display = 'none';
                        document.getElementById('sepay-success-box').style.display = 'block';
                        
                        // Reload lại trang sau 2.5 giây
                        setTimeout(function() {
                            window.location.reload();
                        }, 2500);
                    }
                })
                .catch(error => console.error('Lỗi kiểm tra trạng thái thanh toán:', error));
        }, 3000); // 3 giây kiểm tra một lần
    });
</script>
