@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Affiliate Commission Management")}}</h1>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                <!-- Bộ lọc theo trạng thái -->
                <form method="get" action="{{route('vendor.admin.affiliate.index')}}" class="filter-form filter-form-left d-flex justify-content-start flex-column flex-sm-row">
                    <select name="status" class="form-control custom-select mr-2">
                        <option value="">{{__('-- Status --')}}</option>
                        <option value="pending" @if(Request()->status == 'pending') selected @endif>{{__('Pending')}}</option>
                        <option value="approved" @if(Request()->status == 'approved') selected @endif>{{__('Approved')}}</option>
                        <option value="cancelled" @if(Request()->status == 'cancelled') selected @endif>{{__('Cancelled')}}</option>
                    </select>
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Filter')}}</button>
                </form>
            </div>
            <div class="col-right">
                <form method="get" action="{{route('vendor.admin.affiliate.index')}}" class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by email, name or booking ID')}}" class="form-control">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search')}}</button>
                </form>
            </div>
        </div>
        <div class="text-right mb-2">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th width="80px">{{ __('ID') }}</th>
                            <th>{{ __('Referrer (Vendor)') }}</th>
                            <th>{{ __('Booking') }}</th>
                            <th>{{ __('Booking Total') }}</th>
                            <th>{{ __('Commission Type') }}</th>
                            <th>{{ __('Rate') }}</th>
                            <th>{{ __('Commission Amount') }}</th>
                            <th>{{ __('Commission Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th width="200px">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($rows->total() > 0)
                            @foreach($rows as $row)
                                <tr>
                                    <td>#{{$row->id}}</td>
                                    <td>
                                        <div><strong>{{ $row->first_name }} {{ $row->last_name }}</strong></div>
                                        <div class="text-muted" style="font-size: 11px">{{ $row->email }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('report.admin.booking') }}?s={{ $row->booking_id }}" target="_blank">
                                            #{{ $row->booking_id }}
                                        </a>
                                        <span class="badge badge-secondary ml-1">{{ $row->booking_status }}</span>
                                    </td>
                                    <td>{{ format_money($row->booking_total) }}</td>
                                    <td>{{ $row->commission_type === 'percent' ? __('Percentage') : __('Fixed') }}</td>
                                    <td>
                                        {{ $row->commission_type === 'percent' ? number_format($row->commission_rate).'%' : format_money($row->commission_rate) }}
                                    </td>
                                    <td><strong class="text-success">{{ format_money($row->commission_amount) }}</strong></td>
                                    <td>
                                        @if($row->status === 'pending')
                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                        @elseif($row->status === 'approved')
                                            <span class="badge badge-success">{{ __('Approved') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ display_date($row->created_at) }}</td>
                                    <td>
                                        @if($row->status === 'pending')
                                            <div class="d-flex">
                                                <form action="{{ route('vendor.admin.affiliate.commission.approve', ['id' => $row->id]) }}" method="post" class="mr-1" onsubmit="return confirm('{{ __('Are you sure you want to approve this commission?') }}')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">{{ __('Approve') }}</button>
                                                </form>
                                                <form action="{{ route('vendor.admin.affiliate.commission.reject', ['id' => $row->id]) }}" method="post" onsubmit="return confirm('{{ __('Are you sure you want to reject this commission?') }}')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Reject') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">{{__("No data")}}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{$rows->appends(request()->query())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
