@extends('layouts.user')

@php
    $requestStatuses = [
        \App\Models\AdvertisementRequest::STATUS_PENDING_REVIEW => __('Chờ admin duyệt'),
        \App\Models\AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT => __('Đã duyệt, chờ thanh toán'),
        \App\Models\AdvertisementRequest::STATUS_PAYMENT_WAITING_CONFIRM => __('Đã thanh toán, chờ admin xác nhận'),
        \App\Models\AdvertisementRequest::STATUS_PAYMENT_RECEIVED => __('Thanh toán thành công, chờ chạy quảng cáo'),
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
        .advertisement-list-page .title-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .advertisement-list-page .title-bar .btn {
            border-radius: 6px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .advertisement-table-card {
            overflow: hidden;
            border: 1px solid #e6edf5;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(26, 43, 72, 0.06);
        }

        .advertisement-table-card .table {
            margin-bottom: 0;
        }

        .advertisement-table-card thead th {
            border-bottom: 1px solid #e6edf5;
            background: #f7f9fc;
            color: #1a2b48;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        .advertisement-table-card tbody td {
            vertical-align: middle;
            color: #42526e;
            font-size: 14px;
        }

        .advertisement-table-card tbody tr:nth-child(even) {
            background: #fbfcfe;
        }

        .advertisement-table-card .badge {
            border-radius: 5px;
            padding: 7px 10px;
            font-size: 12px;
            line-height: 1;
            white-space: normal;
            text-align: center;
        }

        .advertisement-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            min-width: 142px;
        }

        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 58px;
            min-height: 34px;
            border: 0 !important;
            border-radius: 6px;
            color: #fff !important;
            -webkit-text-fill-color: #fff !important;
            font-size: 13px !important;
            font-weight: 700;
            line-height: 1;
            text-indent: 0 !important;
            text-decoration: none !important;
            text-shadow: none !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn:link,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn:visited,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn:hover,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn:focus,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn:active,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn span {
            color: #fff !important;
            -webkit-text-fill-color: #fff !important;
        }

        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn-view {
            background: #2563eb !important;
        }

        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn-view:hover,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn-view:focus {
            background: #1d4ed8 !important;
        }

        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn-pay {
            min-width: 96px;
            background: #16a34a !important;
        }

        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn-pay:hover,
        .advertisement-table-card td.advertisement-actions a.advertisement-action-btn-pay:focus {
            background: #15803d !important;
        }

        .advertisement-empty {
            padding: 28px;
            border: 1px dashed #d7e0ea;
            border-radius: 8px;
            color: #6b778c;
            background: #fbfcfe;
        }

        @media (max-width: 767px) {
            .advertisement-list-page .title-bar {
                align-items: flex-start;
                flex-direction: column;
            }

            .advertisement-table-card thead th,
            .advertisement-table-card tbody td {
                white-space: nowrap;
            }
        }
    </style>
@endpush

@section('content')
    <div class="advertisement-list-page">
        <h2 class="title-bar">
            <span>{{__("Đăng ký quảng cáo")}}</span>
            <a href="{{route('user.advertisement.create')}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{__("Tạo yêu cầu")}}
            </a>
        </h2>
        @include('admin.message')

        @if($rows->total() > 0)
        <div class="table-responsive advertisement-table-card">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>{{__("STT")}}</th>
                    <th>{{__("Tiêu đề")}}</th>
                    <th>{{__("Thời gian chạy")}}</th>
                    <th>{{__("Trạng thái")}}</th>
                    <th>{{__("Thanh toán")}}</th>
                    <th>{{__("Số tiền")}}</th>
                    <th>{{__("Ngày gửi")}}</th>
                    <th>{{__("Thao tác")}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $row)
                    @php
                        $paymentStatusLabel = $row->payment
                            ? ($paymentStatuses[$row->payment->payment_status] ?? $row->payment->payment_status)
                            : '-';

                    @endphp
                    <tr>
                        <td>{{$rows->firstItem() + $loop->index}}</td>
                        <td>{{$row->title}}</td>
                        <td>{{$row->duration_label}}</td>
                        <td><span class="badge badge-info">{{$requestStatuses[$row->status] ?? $row->status}}</span></td>
                        <td>
                            @if($row->payment)
                                <span class="badge badge-secondary">{{$paymentStatusLabel}}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($row->payment)
                                {{number_format((float) $row->payment->amount)}}đ
                            @else
                                -
                            @endif
                        </td>
                        <td>{{display_datetime($row->created_at)}}</td>
                        <td class="advertisement-actions">
                            <a href="{{route('user.advertisement.show', $row)}}" class="advertisement-action-btn advertisement-action-btn-view"><span>{{__("Xem")}}</span></a>
                            @if($row->status === \App\Models\AdvertisementRequest::STATUS_APPROVED_WAIT_PAYMENT && $row->payment && $row->payment->payment_status === \App\Models\AdvertisementPayment::STATUS_PENDING)
                                <a href="{{route('user.advertisement.show', $row)}}#advertisement-payment" class="advertisement-action-btn advertisement-action-btn-pay"><span>{{__("Thanh toán")}}</span></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="bravo-pagination">
            {{$rows->appends(request()->query())->links()}}
        </div>
        @else
            <div class="advertisement-empty">{{__("Bạn chưa có yêu cầu quảng cáo nào.")}}</div>
        @endif
    </div>
@endsection
