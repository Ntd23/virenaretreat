@extends('layouts.user')

@section('content')
    <h2 class="title-bar">
        {{__("Affiliate Commissions Report")}}
    </h2>
    @include('admin.message')

    <!-- Thống kê Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-light-blue" style="border-radius: 10px; border-left: 4px solid #1ABC9C !important;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 bg-white p-2 rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-mouse-pointer text-primary" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1" style="font-size: 12px;">{{__("Total Clicks")}}</h6>
                            <h4 class="card-title font-weight-bold m-0 text-dark">{{ number_format($total_clicks) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-light-green" style="border-radius: 10px; border-left: 4px solid #2ECC71 !important;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 bg-white p-2 rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-shopping-cart text-success" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1" style="font-size: 12px;">{{__("Approved Referrals")}}</h6>
                            <h4 class="card-title font-weight-bold m-0 text-dark">{{ number_format($total_approved_count) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-light-orange" style="border-radius: 10px; border-left: 4px solid #E67E22 !important;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 bg-white p-2 rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-money text-warning" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1" style="font-size: 12px;">{{__("Approved Commission")}}</h6>
                            <h4 class="card-title font-weight-bold m-0 text-success">{{ format_money($total_approved_amount) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 bg-light-red" style="border-radius: 10px; border-left: 4px solid #E74C3C !important;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3 bg-white p-2 rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-clock-o text-danger" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1" style="font-size: 12px;">{{__("Pending Commission")}}</h6>
                            <h4 class="card-title font-weight-bold m-0 text-danger">{{ format_money($total_pending_amount) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng lịch sử hoa hồng -->
    <div class="booking-history-manager">
        <h4>{{__("Commission History")}}</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-booking-history">
                <thead>
                <tr>
                    <th width="80px">{{__("ID")}}</th>
                    <th width="120px">{{__("Booking ID")}}</th>
                    <th width="150px">{{__("Commission Rate")}}</th>
                    <th width="180px">{{__("Commission Amount")}}</th>
                    <th width="120px">{{__("Status")}}</th>
                    <th>{{__("Date Created")}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($rows))
                    @foreach($rows as $row)
                        <tr>
                            <td>#{{ $row->id }}</td>
                            <td>
                                <span class="font-weight-bold text-primary">#{{ $row->booking_id }}</span>
                            </td>
                            <td>
                                {{ $row->commission_type === 'percent' ? number_format($row->commission_rate).'%' : format_money($row->commission_rate) }}
                            </td>
                            <td>
                                <strong class="text-success">{{ format_money($row->commission_amount) }}</strong>
                            </td>
                            <td>
                                @if($row->status === 'pending')
                                    <span class="badge badge-warning text-white">{{__("Pending")}}</span>
                                @elseif($row->status === 'approved')
                                    <span class="badge badge-success">{{__("Approved")}}</span>
                                @else
                                    <span class="badge badge-danger">{{__("Cancelled")}}</span>
                                @endif
                            </td>
                            <td>{{ display_date($row->created_at) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">{{__("No commission history found")}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        
        <div class="bravo-pagination">
            {{$rows->appends(request()->query())->links()}}
        </div>
    </div>
@endsection
