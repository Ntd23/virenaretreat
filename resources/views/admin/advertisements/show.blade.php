@extends('admin.layouts.app')

@php
    $statusColors = [
        'pending_review' => 'warning',
        'approved_wait_payment' => 'info',
        'payment_waiting_confirm' => 'warning',
        'payment_received' => 'success',
        'waiting_queue' => 'secondary',
        'running' => 'primary',
        'completed' => 'success',
        'rejected' => 'danger',
        'cancelled' => 'secondary',
    ];
    $paymentColors = [
        'pending' => 'warning',
        'waiting_confirm' => 'warning',
        'paid' => 'success',
        'failed' => 'danger',
    ];
    $mediaUrls = is_array($row->media_urls) ? $row->media_urls : array_filter(preg_split('/\r\n|\r|\n|,/', (string) $row->media_urls));
@endphp

@push('css')
    <style>
        .advertisement-admin-detail .panel,
        .advertisement-admin-detail .panel-body,
        .advertisement-admin-detail .panel-body p {
            min-width: 0;
        }

        .advertisement-admin-detail .advertisement-target-link {
            display: inline;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        @media (max-width: 767px) {
            .advertisement-admin-detail .title-bar {
                font-size: 24px;
                line-height: 1.25;
            }

            .advertisement-admin-detail .panel-body {
                padding: 16px;
            }

            .advertisement-admin-detail .panel-body p {
                line-height: 1.55;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid advertisement-admin-detail">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Yêu cầu quảng cáo")}} #{{$row->id}}</h1>
            <a href="{{route('admin.advertisements.index')}}" class="btn btn-secondary">{{__("Quay lại")}}</a>
        </div>
        @include('admin.message')

        <div class="row">
            <div class="col-lg-8">
                <div class="panel">
                    <div class="panel-title"><strong>{{__("Thông tin yêu cầu")}}</strong></div>
                    <div class="panel-body">
                        <p><strong>{{__("Người dùng")}}:</strong>
                            @if($row->user)
                                {{$row->user->getDisplayName(true)}} - {{$row->user->email}}
                            @else
                                {{__("[Người dùng đã bị xoá]")}}
                            @endif
                        </p>
                        <p><strong>{{__("Tiêu đề")}}:</strong> {{$row->title}}</p>
                        <p><strong>{{__("Link đích")}}:</strong>
                            @if($row->link_url ?: $row->target_url)
                                <a href="{{$row->link_url ?: $row->target_url}}" target="_blank" class="advertisement-target-link">{{$row->link_url ?: $row->target_url}}</a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>{{__("Trạng thái")}}:</strong>
                            <span class="badge badge-{{$statusColors[$row->status] ?? 'secondary'}}">{{$statuses[$row->status] ?? $row->status}}</span>
                        </p>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-title"><strong>{{__("Nội dung / media")}}</strong></div>
                    <div class="panel-body">
                        <h5>{{__("Nội dung")}}</h5>
                        <div class="mb-3">{!! nl2br(e($row->content ?: $row->description ?: '-')) !!}</div>
                        <h5>{{__("Hình ảnh quảng cáo")}}</h5>
                        @if($mediaUrls)
                            <div class="row">
                                @foreach($mediaUrls as $url)
                                    <div class="col-md-4 mb-3">
                                        <a href="{{$url}}" target="_blank">
                                            <img src="{{$url}}" alt="media" style="width:100%; max-height:220px; object-fit:cover;">
                                        </a>
                                        <div class="mt-1"><a href="{{$url}}" target="_blank">{{__("Mở tệp")}}</a></div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>-</p>
                        @endif
                        <h5>{{__("Ghi chú")}}</h5>
                        <div>{!! nl2br(e($row->note ?: '-')) !!}</div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-title"><strong>{{__("Thông tin khách hàng & thời gian")}}</strong></div>
                    <div class="panel-body">
                        <p><strong>{{__("Tên khách hàng")}}:</strong> {{$row->customer_name ?: '-'}}</p>
                        <p><strong>{{__("Email")}}:</strong> {{$row->customer_email ?: '-'}}</p>
                        <p><strong>{{__("Số điện thoại")}}:</strong> {{$row->customer_phone ?: '-'}}</p>
                        <p><strong>{{__("Địa chỉ")}}:</strong> {{$row->customer_address ?: '-'}}</p>
                        <p><strong>{{__("Vị trí quảng cáo")}}:</strong> {{$row->position ? $row->position->name : '-'}}</p>
                        <p><strong>{{__("Thời gian chạy")}}:</strong> {{$row->duration_label}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($row->status === \App\Models\AdvertisementRequest::STATUS_PENDING_REVIEW)
                    <div class="panel">
                        <div class="panel-title"><strong>{{__("Báo giá admin")}}</strong></div>
                        <div class="panel-body">
                            <form method="post" action="{{route('admin.advertisements.approve', $row)}}" class="js-loading-form js-approve-form">
                                @csrf
                                <div class="form-group">
                                    <label>{{__("Vị trí quảng cáo")}} <span class="text-danger">*</span></label>
                                    <select name="advertisement_position_id" class="form-control js-advertisement-position" required>
                                        <option value="">{{__("-- Chọn vị trí quảng cáo --")}}</option>
                                        @foreach($advertisementPositions as $position)
                                            <option
                                                value="{{$position->id}}"
                                                data-price="{{(float) $position->base_price}}"
                                                @if($position->is_full && (string) old('advertisement_position_id', $row->advertisement_position_id) !== (string) $position->id) disabled @endif
                                                @if((string) old('advertisement_position_id', $row->advertisement_position_id) === (string) $position->id) selected @endif
                                            >
                                                {{$position->name}} - {{$position->code}}
                                                ({{__("Chờ")}}: {{$position->active_ads_count}}/{{$position->fixed_quantity}})
                                                @if($position->is_full) - {{__("Đã đủ")}} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($advertisementPositions->isEmpty())
                                        <small class="form-text text-danger">{{__("Chưa có cấu hình vị trí quảng cáo. Vui lòng tạo dữ liệu trong bảng advertisement_positions.")}}</small>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Ngày bắt đầu")}} <span class="text-danger">*</span></label>
                                            <input type="date"
                                                   name="start_date"
                                                   value="{{old('start_date', $row->start_date ? $row->start_date->format('Y-m-d') : '')}}"
                                                   class="form-control js-advertisement-start-date"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__("Ngày kết thúc")}} <span class="text-danger">*</span></label>
                                            <input type="date"
                                                   name="end_date"
                                                   value="{{old('end_date', $row->end_date ? $row->end_date->format('Y-m-d') : '')}}"
                                                   class="form-control js-advertisement-end-date"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__("Giá tiền / ngày")}}</label>
                                    <input type="number" class="form-control js-base-price" readonly>
                                    <small class="form-text text-muted">
                                        {{__("Giá tiền được tính theo đơn giá/ngày của vị trí quảng cáo đã chọn.")}}
                                        <a href="{{route('admin.advertisements.pricing')}}" target="_blank">{{__("Cấu hình giá")}}</a>
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label>{{__("Thành tiền")}}</label>
                                    <input type="number" name="final_price" value="{{old('final_price', $row->final_price)}}" class="form-control js-final-price" readonly>
                                    <small class="form-text text-muted">{{__("Thành tiền = số ngày chạy x giá tiền/ngày.")}}</small>
                                </div>
                                <div class="form-group">
                                    <label>{{__("Ghi chú admin")}}</label>
                                    <textarea name="admin_note" rows="4" class="form-control">{{old('admin_note', $row->admin_note)}}</textarea>
                                </div>
                                <button class="btn btn-primary btn-block" type="submit" data-loading-text="{{__('Đang xử lý...')}}">
                                    <i class="fa fa-check"></i> {{__("Duyệt & gửi báo giá")}}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-title"><strong>{{__("Từ chối")}}</strong></div>
                        <div class="panel-body">
                            <form method="post" action="{{route('admin.advertisements.reject', $row)}}" class="js-loading-form">
                                @csrf
                                <div class="form-group">
                                    <label>{{__("Lý do từ chối")}} <span class="text-danger">*</span></label>
                                    <textarea name="reject_reason" rows="4" class="form-control" required>{{old('reject_reason', $row->reject_reason)}}</textarea>
                                </div>
                                <button class="btn btn-danger btn-block" type="submit" data-loading-text="{{__('Đang xử lý...')}}">
                                    <i class="fa fa-times"></i> {{__("Từ chối")}}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($row->payment)
                    <div class="panel">
                        <div class="panel-title"><strong>{{__("Thông tin thanh toán")}}</strong></div>
                        <div class="panel-body">
                            @php
                                $paymentGateway = $row->payment->payment_method ? get_payment_gateway_obj($row->payment->payment_method) : null;
                                $paymentMethodName = $paymentGateway ? $paymentGateway->getDisplayName() : $row->payment->payment_method;
                            @endphp
                            <p><strong>{{__("Mã thanh toán")}}:</strong> {{$row->payment->payment_code}}</p>
                            <p><strong>{{__("Phương thức thanh toán")}}:</strong> {{$paymentMethodName ?: '-'}}</p>
                            <p><strong>{{__("Số tiền cần thanh toán")}}:</strong> {{number_format((float) $row->payment->amount)}}đ</p>
                            <p><strong>{{__("Số tiền đã nhận")}}:</strong> {{number_format((float) $row->payment->paid_amount)}}đ</p>
                            <p><strong>{{__("Trạng thái thanh toán")}}:</strong>
                                <span class="badge badge-{{$paymentColors[$row->payment->payment_status] ?? 'secondary'}}">
                                    {{$paymentStatuses[$row->payment->payment_status] ?? $row->payment->payment_status}}
                                </span>
                            </p>
                            <p><strong>{{__("Mã giao dịch SePay")}}:</strong> {{$row->payment->sepay_transaction_id ?: '-'}}</p>
                            <p><strong>{{__("Cổng SePay")}}:</strong> {{$row->payment->sepay_gateway ?: '-'}}</p>
                            <p><strong>{{__("Nội dung chuyển khoản SePay")}}:</strong> {{$row->payment->sepay_transfer_content ?: $row->payment->sepay_content ?: '-'}}</p>
                            <p><strong>{{__("Ngày giao dịch SePay")}}:</strong> {{$row->payment->sepay_transaction_date ? display_datetime($row->payment->sepay_transaction_date) : '-'}}</p>
                            <p><strong>{{__("Thời gian thanh toán")}}:</strong> {{$row->payment->paid_at ? display_datetime($row->payment->paid_at) : '-'}}</p>

                            @if($row->payment->qr_url)
                                <div class="text-center mb-3">
                                    <img src="{{$row->payment->qr_url}}" alt="{{$row->payment->payment_code}}" class="img-fluid" style="max-width: 220px;">
                                </div>
                            @endif

                            @if($row->status === \App\Models\AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT)
                                <hr>
                                <div class="alert alert-info mb-0">
                                    {{__("Đang chờ người dùng thanh toán.")}}
                                </div>
                            @elseif($row->status === \App\Models\AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM)
                                <hr>
                                <div class="alert alert-warning">
                                    {{__("Người dùng đã thanh toán. Vui lòng kiểm tra số tiền thực nhận trước khi xác nhận.")}}
                                </div>
                                <form method="post" action="{{route('admin.advertisements.confirm-payment', $row)}}" class="js-loading-form js-confirm-payment-form" data-confirm-message="{{__('Thanh toán thành công?')}}">
                                    @csrf
                                    <div class="form-group">
                                        <label>{{__("Số tiền thực nhận")}}</label>
                                        @php
                                            $confirmPaidAmount = old('paid_amount', $row->payment->paid_amount > 0 ? $row->payment->paid_amount : $row->payment->amount);
                                        @endphp
                                        <input type="hidden" name="paid_amount" value="{{(float) $confirmPaidAmount}}" class="js-paid-amount-value">
                                        <input type="text"
                                               value="{{number_format((float) $confirmPaidAmount, 0, ',', '.')}}"
                                               class="form-control js-money-input"
                                               inputmode="numeric"
                                               autocomplete="off">
                                        <small class="form-text text-muted">{{__("Chỉ xác nhận khi số tiền thực nhận đúng bằng số tiền cần thanh toán.")}}</small>
                                    </div>
                                    <button class="btn btn-success btn-block" type="submit" data-loading-text="{{__('Đang xử lý...')}}">
                                        <i class="fa fa-check-circle"></i> {{__("Thanh toán thành công")}}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="panel">
                    <div class="panel-title"><strong>{{__("Hành động")}}</strong></div>
                    <div class="panel-body">
                        @if($row->status === \App\Models\AdvertisementRequest::STATUS_PAYMENT_RECEIVED && optional($row->payment)->payment_status === \App\Models\AdvertisementPayment::STATUS_PAID)
                            <form method="post" action="{{route('admin.advertisements.add-to-waiting-queue', $row)}}" class="js-loading-form">
                                @csrf
                                <button class="btn btn-warning btn-block" type="submit" data-loading-text="{{__('Đang xử lý...')}}">
                                    <i class="fa fa-list"></i> {{__("Cho vào danh sách chờ")}}
                                </button>
                            </form>
                        @elseif($row->status === \App\Models\AdvertisementRequest::STATUS_WAITING_QUEUE)
                            @if(empty($isCurrentPositionRunningFull))
                                <form method="post" action="{{route('admin.advertisements.confirm-running', $row)}}" class="js-loading-form">
                                    @csrf
                                    <button class="btn btn-primary btn-block" type="submit" data-loading-text="{{__('Đang xử lý...')}}">
                                        <i class="fa fa-play"></i> {{__("Xác nhận chạy quảng cáo")}}
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning mb-0">
                                    {{__("Vị trí này đã đủ số lượng quảng cáo đang chạy.")}}
                                    @if(!empty($currentPositionRunningLimit))
                                        <br>{{__("Đang chạy")}}: {{$currentPositionRunningCount}}/{{$currentPositionRunningLimit}}
                                    @endif
                                </div>
                            @endif
                        @elseif($row->status === \App\Models\AdvertisementRequest::STATUS_RUNNING)
                            <form method="post" action="{{route('admin.advertisements.complete', $row)}}" class="js-loading-form">
                                @csrf
                                <button class="btn btn-success btn-block" type="submit" data-loading-text="{{__('Đang xử lý...')}}">
                                    <i class="fa fa-flag-checkered"></i> {{__("Đánh dấu hết hạn")}}
                                </button>
                            </form>
                        @else
                            <p class="mb-0 text-muted">{{__("Không có hành động khả dụng cho trạng thái hiện tại.")}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmPaymentSuccessModal" tabindex="-1" role="dialog" aria-labelledby="confirmPaymentSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmPaymentSuccessModalLabel">{{__("Xác nhận thanh toán")}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{__('Đóng')}}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{__("Bạn xác nhận thanh toán này đã thành công?")}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("Hủy")}}</button>
                        <button type="button" class="btn btn-success js-confirm-payment-submit">
                            <i class="fa fa-check-circle"></i> {{__("Xác nhận")}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function updateAdvertisementFinalPrice() {
            $('.js-approve-form').each(function () {
                var form = $(this);
                var selectedPosition = form.find('.js-advertisement-position option:selected');
                var basePrice = parseFloat(selectedPosition.data('price')) || 0;
                var finalPrice = basePrice;
                var startDate = form.find('.js-advertisement-start-date').val();
                var endDate = form.find('.js-advertisement-end-date').val();
                var days = 0;

                if (startDate && endDate) {
                    var start = new Date(startDate + 'T00:00:00');
                    var end = new Date(endDate + 'T00:00:00');
                    if (end >= start) {
                        days = Math.round((end - start) / 86400000) + 1;
                    }
                }

                if (days > 0) {
                    finalPrice = basePrice * days;
                }

                form.find('.js-base-price').val(Math.max(0, Math.round(basePrice)));
                form.find('.js-final-price').val(Math.max(0, Math.round(finalPrice)));
            });
        }

        $('.js-advertisement-position, .js-advertisement-start-date, .js-advertisement-end-date').on('change keyup', updateAdvertisementFinalPrice);
        updateAdvertisementFinalPrice();

        function normalizeMoneyValue(value) {
            return String(value || '').replace(/[^\d]/g, '');
        }

        function formatMoneyValue(value) {
            var numberValue = normalizeMoneyValue(value);
            if (!numberValue) {
                return '';
            }

            return new Intl.NumberFormat('vi-VN').format(parseInt(numberValue, 10));
        }

        function syncPaidAmount(form) {
            var input = form.find('.js-money-input');
            var hidden = form.find('.js-paid-amount-value');
            var value = normalizeMoneyValue(input.val());

            hidden.val(value || 0);
            input.val(formatMoneyValue(value));
        }

        $('.js-money-input').on('input blur', function () {
            var input = $(this);
            input.val(formatMoneyValue(input.val()));
            input.closest('form').find('.js-paid-amount-value').val(normalizeMoneyValue(input.val()) || 0);
        });

        var pendingConfirmPaymentForm = null;

        $('.js-confirm-payment-form').on('submit', function (event) {
            var form = $(this);
            syncPaidAmount(form);

            if (form.data('confirmed') !== true) {
                event.preventDefault();
                pendingConfirmPaymentForm = form;
                $('#confirmPaymentSuccessModal').modal('show');
                return false;
            }
        });

        $('.js-confirm-payment-submit').on('click', function () {
            if (!pendingConfirmPaymentForm) {
                return;
            }

            $('#confirmPaymentSuccessModal').modal('hide');
            pendingConfirmPaymentForm.data('confirmed', true);
            pendingConfirmPaymentForm.trigger('submit');
        });

        $('#confirmPaymentSuccessModal').on('hidden.bs.modal', function () {
            if (pendingConfirmPaymentForm && pendingConfirmPaymentForm.data('confirmed') !== true) {
                pendingConfirmPaymentForm = null;
            }
        });

        $('.js-loading-form').on('submit', function (event) {
            if (event.isDefaultPrevented()) {
                return;
            }

            updateAdvertisementFinalPrice();
            syncPaidAmount($(this));
            var btn = $(this).find('button[type=submit]');
            btn.prop('disabled', true).data('original-text', btn.html()).html(btn.data('loading-text') || 'Đang xử lý...');
        });
    </script>
@endpush
