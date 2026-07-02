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
@endphp

@push('css')
    <style>
        .advertisement-filter-fields {
            display: grid;
            grid-template-columns: minmax(190px, 1fr) minmax(230px, 1.2fr) minmax(190px, 1fr) minmax(190px, 1fr);
            gap: 12px;
            max-width: 1040px;
        }

        .advertisement-filter-fields .form-control {
            margin: 0 !important;
            width: 100%;
        }

        .advertisement-filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .advertisement-filter-actions .btn {
            margin: 0 !important;
        }

        .advertisement-request-table {
            font-size: 13px;
        }

        .advertisement-request-table th,
        .advertisement-request-table td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
            line-height: 1.35;
        }

        .advertisement-request-table th {
            white-space: nowrap;
        }

        .advertisement-request-table small {
            font-size: 12px;
            line-height: 1.35;
        }

        .advertisement-request-table .badge {
            display: inline-block;
            max-width: 190px;
            padding: 4px 7px;
            font-size: 12px;
            line-height: 1.2;
            white-space: normal;
        }

        .advertisement-request-table .btn-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 62px;
            padding: 6px 10px;
            line-height: 1.2;
            white-space: nowrap;
        }

        .advertisement-request-table .col-id {
            width: 58px;
        }

        .advertisement-request-table .col-price,
        .advertisement-request-table .col-date,
        .advertisement-request-table .col-action {
            white-space: nowrap;
        }

        @media (max-width: 1199px) {
            .advertisement-filter-fields {
                grid-template-columns: repeat(2, minmax(220px, 1fr));
            }
        }

        @media (max-width: 575px) {
            .advertisement-filter-fields {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $listType = $listType ?? 'all';
        $filterAction = $listType === 'waiting'
            ? route('admin.advertisements.waiting-list')
            : ($listType === 'running' ? route('admin.advertisements.running-list') : route('admin.advertisements.index'));
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{$pageTitle ?? __("Yêu cầu quảng cáo")}}</h1>
        </div>
        @include('admin.message')

        <div class="filter-div d-flex justify-content-between flex-column flex-xl-row">
            <div class="col-left mb-2">
                <form method="get" action="{{$filterAction}}" class="filter-form filter-form-right" role="search">
                    <div class="advertisement-filter-fields">
                        <select name="status" class="form-control">
                            <option value="">{{__("-- Trạng thái --")}}</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{$key}}" @if(request('status') === $key) selected @endif>{{$label}}</option>
                            @endforeach
                        </select>
                        <input type="text" name="s" value="{{request('s')}}" placeholder="{{__('Tìm theo tiêu đề/khách hàng/user')}}" class="form-control">
                        <input type="date" name="created_from" value="{{request('created_from')}}" class="form-control">
                        <input type="date" name="created_to" value="{{request('created_to')}}" class="form-control">
                    </div>
                    <div class="advertisement-filter-actions">
                        <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Bộ lọc')}}</button>
                        <a href="{{route('admin.advertisements.pricing')}}" class="btn btn-secondary">
                            <i class="fa fa-money"></i> {{__('Giá tiền')}}
                        </a>
                        <a href="{{route('admin.advertisements.index')}}" class="btn {{$listType === 'all' ? 'btn-primary' : 'btn-outline-primary'}}">
                            <i class="fa fa-list-alt"></i> {{__('Tất cả')}}
                        </a>
                        <a href="{{route('admin.advertisements.waiting-list')}}" class="btn {{$listType === 'waiting' ? 'btn-warning' : 'btn-outline-warning'}}">
                            <i class="fa fa-list"></i> {{__('Danh sách chờ')}}
                        </a>
                        <a href="{{route('admin.advertisements.running-list')}}" class="btn {{$listType === 'running' ? 'btn-success' : 'btn-outline-success'}}">
                            <i class="fa fa-play"></i> {{__('Quảng cáo đang chạy')}}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="panel-title"><strong>{{__("Danh sách yêu cầu")}}</strong></div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover advertisement-request-table">
                        <thead>
                        <tr>
                            <th class="col-id">{{__("Mã")}}</th>
                            <th>{{__("Người gửi")}}</th>
                            <th>{{__("Tiêu đề quảng cáo")}}</th>
                            <th>{{__("Thông tin khách hàng")}}</th>
                            <th>{{__("Thời gian / Vị trí")}}</th>
                            <th class="col-price">{{__("Thành tiền")}}</th>
                            <th>{{__("Trạng thái")}}</th>
                            <th>{{__("Thanh toán")}}</th>
                            <th class="col-date">{{__("Ngày tạo")}}</th>
                            <th class="col-action">{{__("Hành động")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>#{{$row->id}}</td>
                                <td>
                                    @if($row->user)
                                        <strong>{{$row->user->getDisplayName(true)}}</strong><br>
                                        <small>{{$row->user->email}}</small>
                                    @else
                                        {{__("[Người dùng đã bị xoá]")}}
                                    @endif
                                </td>
                                <td><a href="{{route('admin.advertisements.show', $row)}}">{{$row->title}}</a></td>
                                <td>
                                    <strong>{{$row->customer_name ?: '-'}}</strong><br>
                                    <small>{{$row->customer_email ?: '-'}}</small><br>
                                    <small>{{$row->customer_phone ?: '-'}}</small>
                                </td>
                                <td>
                                    <strong>{{$row->position ? $row->position->name : '-'}}</strong><br>
                                    <small>{{$row->duration_label ?: '-'}}</small>
                                </td>
                                @php
                                    $displayFinalPrice = $row->final_price ?? optional($row->payment)->amount;
                                @endphp
                                <td class="col-price">{{$displayFinalPrice !== null ? number_format((float) $displayFinalPrice).'đ' : '-'}}</td>
                                <td><span class="badge badge-{{$statusColors[$row->status] ?? 'secondary'}}">{{$statuses[$row->status] ?? $row->status}}</span></td>
                                <td>
                                    @if($row->payment)
                                        <span class="badge badge-{{$paymentColors[$row->payment->payment_status] ?? 'secondary'}}">
                                            {{$paymentStatuses[$row->payment->payment_status] ?? $row->payment->payment_status}}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="col-date">{{display_datetime($row->created_at)}}</td>
                                <td class="col-action">
                                    <a href="{{route('admin.advertisements.show', $row)}}" class="btn btn-sm btn-primary btn-view">
                                        <i class="fa fa-eye"></i> {{__("Xem")}}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">{{__("Không có dữ liệu")}}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{$rows->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
@endsection
