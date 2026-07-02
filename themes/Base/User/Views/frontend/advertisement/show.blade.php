@extends('layouts.user')

@php
    $requestStatuses = [
        \App\Models\AdvertisementRequest::STATUS_PENDING_REVIEW => __('Chờ admin duyệt'),
        \App\Models\AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT => __('Đã duyệt, chờ thanh toán'),
        \App\Models\AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM => __('Đã thanh toán, chờ admin xác nhận'),
        \App\Models\AdvertisementRequest::STATUS_PAYMENT_RECEIVED => __('Thanh toán thành công, chờ admin chạy quảng cáo'),
        \App\Models\AdvertisementRequest::STATUS_WAITING_QUEUE => __('Đang trong danh sách chờ'),
        \App\Models\AdvertisementRequest::STATUS_RUNNING => __('Đang chạy quảng cáo'),
        \App\Models\AdvertisementRequest::STATUS_COMPLETED => __('Hết hạn'),
        \App\Models\AdvertisementRequest::STATUS_REJECTED => __('Từ chối'),
        \App\Models\AdvertisementRequest::STATUS_CANCELLED => __('Đã hủy'),
    ];
    $paymentStatuses = [
        \App\Models\AdvertisementPayment::STATUS_PENDING => __('Chờ thanh toán'),
        \App\Models\AdvertisementPayment::STATUS_WAITING_CONFIRM => __('Chờ admin xác nhận thanh toán'),
        \App\Models\AdvertisementPayment::STATUS_PAID => __('Đã thanh toán'),
        \App\Models\AdvertisementPayment::STATUS_FAILED => __('Thanh toán lỗi'),
    ];
@endphp

@push('css')
    <style>
        .advertisement-detail-page .title-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .advertisement-detail-page .title-bar .btn {
            border-radius: 6px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .advertisement-detail-card {
            margin-bottom: 22px;
            border: 1px solid #e6edf5;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(26, 43, 72, 0.06);
            overflow: hidden;
        }

        .advertisement-detail-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 20px 24px;
            border-bottom: 1px solid #edf1f6;
            background: #f8fafd;
        }

        .advertisement-detail-card-header h3,
        .advertisement-detail-card-header h4 {
            margin: 0;
            color: #1a2b48;
            font-size: 20px;
            font-weight: 700;
        }

        .advertisement-detail-card-body {
            padding: 24px;
        }

        .advertisement-status-badge,
        .advertisement-detail-page .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 26px;
            border-radius: 5px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.1;
            white-space: normal;
            text-align: center;
        }

        .advertisement-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 22px;
        }

        .advertisement-info-item {
            min-width: 0;
            padding: 14px 16px;
            border: 1px solid #edf1f6;
            border-radius: 7px;
            background: #fbfcfe;
        }

        .advertisement-info-label {
            margin-bottom: 5px;
            color: #6b778c;
            font-size: 13px;
            font-weight: 700;
        }

        .advertisement-info-value {
            color: #253858;
            font-size: 15px;
            word-break: break-word;
        }

        .advertisement-description {
            margin-top: 18px;
            padding: 16px;
            border-radius: 7px;
            background: #fbfcfe;
            color: #253858;
            line-height: 1.7;
        }

        .advertisement-media-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .advertisement-media-item {
            overflow: hidden;
            border: 1px solid #e6edf5;
            border-radius: 8px;
            background: #fff;
        }

        .advertisement-media-item img {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
        }

        .advertisement-media-link {
            display: block;
            padding: 10px 12px;
            color: #bc8b52;
            font-weight: 700;
        }

        .advertisement-payment-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .advertisement-gateway-card {
            border: 1px solid #e6edf5;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .advertisement-gateway-card .card-header {
            padding: 14px 16px;
            border-bottom: 1px solid #edf1f6;
            background: #fbfcfe;
            cursor: pointer;
        }

        .advertisement-gateway-card .card-body {
            padding: 18px;
        }

        .advertisement-qr-box {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 260px;
            padding: 16px;
            border: 1px dashed #d7e0ea;
            border-radius: 8px;
            background: #fbfcfe;
        }

        .advertisement-qr-box img {
            max-width: 260px;
            width: 100%;
        }

        .advertisement-pay-note {
            margin: 18px 0 0;
            color: #6b778c;
        }

        .advertisement-detail-page .btn-primary {
            border-radius: 6px;
            padding: 10px 18px;
            font-weight: 700;
        }

        @media (max-width: 991px) {
            .advertisement-media-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .advertisement-detail-page .title-bar,
            .advertisement-detail-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .advertisement-info-grid,
            .advertisement-payment-summary,
            .advertisement-media-grid {
                grid-template-columns: 1fr;
            }

            .advertisement-detail-card-body,
            .advertisement-detail-card-header {
                padding: 18px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="advertisement-detail-page">
        <h2 class="title-bar">
            <span>{{$row->title}}</span>
            <a href="{{route('user.advertisement.index')}}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> {{__("Quay lại")}}
            </a>
        </h2>
        @include('admin.message')

        <div class="advertisement-detail-card">
            <div class="advertisement-detail-card-header">
                <h3>{{__("Thông tin yêu cầu")}}</h3>
                <span class="badge badge-info advertisement-status-badge">{{$requestStatuses[$row->status] ?? $row->status}}</span>
            </div>
            <div class="advertisement-detail-card-body">
                <div class="advertisement-info-grid">
                    <div class="advertisement-info-item">
                        <div class="advertisement-info-label">{{__("Link đích")}}</div>
                        <div class="advertisement-info-value">{{$row->target_url ?: '-'}}</div>
                    </div>
                    <div class="advertisement-info-item">
                        <div class="advertisement-info-label">{{__("Thời gian chạy")}}</div>
                        <div class="advertisement-info-value">{{$row->duration_label}}</div>
                    </div>
                    <div class="advertisement-info-item">
                        <div class="advertisement-info-label">{{__("Tên khách hàng")}}</div>
                        <div class="advertisement-info-value">{{$row->customer_name ?: '-'}}</div>
                    </div>
                    <div class="advertisement-info-item">
                        <div class="advertisement-info-label">{{__("Email")}}</div>
                        <div class="advertisement-info-value">{{$row->customer_email ?: '-'}}</div>
                    </div>
                    <div class="advertisement-info-item">
                        <div class="advertisement-info-label">{{__("Số điện thoại")}}</div>
                        <div class="advertisement-info-value">{{$row->customer_phone ?: '-'}}</div>
                    </div>
                    <div class="advertisement-info-item">
                        <div class="advertisement-info-label">{{__("Địa chỉ")}}</div>
                        <div class="advertisement-info-value">{{$row->customer_address ?: '-'}}</div>
                    </div>
                </div>

                <div class="advertisement-description">
                    <div class="advertisement-info-label">{{__("Mô tả")}}</div>
                    <div>{!! nl2br(e($row->description ?: '-')) !!}</div>
                </div>
            </div>
        </div>

        <div class="advertisement-detail-card">
            <div class="advertisement-detail-card-header">
                <h3>{{__("Hình ảnh quảng cáo")}}</h3>
            </div>
            <div class="advertisement-detail-card-body">
            @if(!empty($row->media_urls))
                <div class="advertisement-media-grid">
                    @foreach($row->media_urls as $mediaUrl)
                        <div class="advertisement-media-item">
                            <a href="{{$mediaUrl}}" target="_blank">
                                <img src="{{$mediaUrl}}" alt="{{$row->title}}">
                            </a>
                            <a class="advertisement-media-link" href="{{$mediaUrl}}" target="_blank">{{__("Mở tệp")}}</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p>-</p>
            @endif
            </div>
        </div>

            @if($row->rejection_reason)
            <div class="advertisement-detail-card">
                <div class="advertisement-detail-card-body">
                    <p class="text-danger mb-0"><strong>{{__("Lý do từ chối")}}:</strong> {{$row->rejection_reason}}</p>
                </div>
            </div>
            @endif

            @if($row->payment)
                @php
                    $selectedPaymentMethod = $row->payment->payment_method;
                    $selectedPaymentName = $selectedPaymentMethod && isset($gateways[$selectedPaymentMethod])
                        ? $gateways[$selectedPaymentMethod]->getDisplayName()
                        : $selectedPaymentMethod;
                    $paymentStatusLabel = $paymentStatuses[$row->payment->payment_status] ?? $row->payment->payment_status;
                    $isPayable = $row->status === \App\Models\AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT
                        && in_array($row->payment->payment_status, [
                            \App\Models\AdvertisementPayment::STATUS_PENDING,
                        ], true);
                    $formSelectedPaymentMethod = $isPayable && isset($gateways['sepay']) ? 'sepay' : $selectedPaymentMethod;

                @endphp
            <div class="advertisement-detail-card" id="advertisement-payment">
                <div class="advertisement-detail-card-header">
                    <h3>{{__("Thông tin thanh toán")}}</h3>
                    <span class="badge badge-secondary">{{$paymentStatusLabel}}</span>
                </div>
                <div class="advertisement-detail-card-body">
                    <div class="advertisement-payment-summary">
                        <div class="advertisement-info-item">
                            <div class="advertisement-info-label">{{__("Mã thanh toán")}}</div>
                            <div class="advertisement-info-value">{{$row->payment->payment_code}}</div>
                        </div>
                        <div class="advertisement-info-item">
                            <div class="advertisement-info-label">{{__("Số tiền")}}</div>
                            <div class="advertisement-info-value">{{number_format((float) $row->payment->amount)}}đ</div>
                        </div>
                        <div class="advertisement-info-item">
                            <div class="advertisement-info-label">{{__("Phương thức thanh toán")}}</div>
                            <div class="advertisement-info-value">{{$selectedPaymentName ?: '-'}}</div>
                        </div>
                        <div class="advertisement-info-item">
                            <div class="advertisement-info-label">{{__("Đã nhận")}}</div>
                            <div class="advertisement-info-value">{{number_format((float) $row->payment->paid_amount)}}đ</div>
                        </div>
                    </div>

                @if($isPayable)
                    <h4>{{__("Chọn phương thức thanh toán")}}</h4>
                    @if(!empty($gateways))
                        <form action="{{route('user.advertisement.pay', $row)}}" method="post">
                            @csrf
                            <div class="gateways-table accordion" id="advertisementPaymentGateways">
                                @foreach($gateways as $key => $gateway)
                                    <div class="card mb-2 advertisement-gateway-card">
                                        <div class="card-header">
                                            <label class="mb-0 d-flex align-items-center" data-toggle="collapse" data-target="#gateway_{{$key}}">
                                                <input type="radio" name="payment_gateway" value="{{$key}}" class="mr-2" required @if($formSelectedPaymentMethod === $key) checked @endif>
                                                @if($logo = $gateway->getDisplayLogo())
                                                    <img src="{{$logo}}" alt="{{$gateway->getDisplayName()}}" style="max-height:28px; margin-right:8px;">
                                                @endif
                                                {{$gateway->getDisplayName()}}
                                            </label>
                                        </div>
                                        <div id="gateway_{{$key}}" class="collapse @if($formSelectedPaymentMethod === $key) show @endif" data-parent="#advertisementPaymentGateways">
                                            <div class="card-body">
                                                {!! $gateway->getDisplayHtml() !!}
                                                @if($key === 'sepay')
                                                    <div class="row mt-3">
                                                        <div class="col-md-7">
                                                            <p><strong>{{__("Ngân hàng")}}:</strong> {{setting_item('g_sepay_bank') ?: setting_item('g_sepay_bank_brand_name') ?: setting_item('g_sepay_bank_short_name') ?: '-'}}</p>
                                                            <p><strong>{{__("Số tài khoản")}}:</strong> {{setting_item('g_sepay_bank_account_number') ?: '-'}}</p>
                                                            <p><strong>{{__("Chủ tài khoản")}}:</strong> {{setting_item('g_sepay_bank_account_holder') ?: '-'}}</p>
                                                            <p><strong>{{__("Số tiền")}}:</strong> {{number_format((float) $row->payment->amount)}}đ</p>
                                                            <p><strong>{{__("Nội dung chuyển khoản")}}:</strong> <code>{{$row->payment->payment_code}}</code></p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="advertisement-qr-box">
                                                            @if(!empty($sepayQrUrl))
                                                                <img src="{{$sepayQrUrl}}" alt="{{$row->payment->payment_code}}">
                                                            @else
                                                                <div class="alert alert-warning mb-0">
                                                                    {{__("Chưa cấu hình đủ thông tin ngân hàng SePay để tạo QR.")}}
                                                                </div>
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="advertisement-pay-note">{{__("Sau khi đã chuyển khoản/thanh toán, bấm nút bên dưới để gửi admin xác nhận.")}}</p>
                            <button class="btn btn-primary mt-3" type="submit">
                                <i class="fa fa-check-circle"></i> {{__("Tôi đã thanh toán")}}
                            </button>
                        </form>
                    @else
                        <p>{{__("Hiện chưa có cổng thanh toán khả dụng.")}}</p>
                    @endif
                @endif

                @if($row->payment->payment_method === 'sepay')
                    <div class="advertisement-description">
                        <h4>{{__("Chuyển khoản Ngân hàng tự động qua SePay")}}</h4>
                        <p><strong>{{__("Ngân hàng")}}:</strong> {{setting_item('g_sepay_bank') ?: setting_item('g_sepay_bank_brand_name') ?: setting_item('g_sepay_bank_short_name') ?: '-'}}</p>
                        <p><strong>{{__("Số tài khoản")}}:</strong> {{setting_item('g_sepay_bank_account_number') ?: '-'}}</p>
                        <p><strong>{{__("Chủ tài khoản")}}:</strong> {{setting_item('g_sepay_bank_account_holder') ?: '-'}}</p>
                        <p class="mb-0"><strong>{{__("Nội dung chuyển khoản")}}:</strong> <code>{{$row->payment->payment_code}}</code></p>
                    </div>
                @endif

                @if($row->payment->payment_method === 'sepay' && $row->payment->qr_url)
                    <div class="advertisement-qr-box mt-3">
                        <img src="{{$row->payment->qr_url}}" alt="{{$row->payment->payment_code}}">
                    </div>
                @endif
                </div>
            </div>
            @endif
    </div>
@endsection
