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
                            <i class="fa fa-check-circle text-success" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1" style="font-size: 12px;">{{__("Paid Commission")}}</h6>
                            <h4 class="card-title font-weight-bold m-0 text-success">{{ format_money($total_paid_amount) }}</h4>
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

    <div class="row">
        <div class="col-md-8">
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
                                            <span class="badge badge-info text-white">{{__("Approved (Unpaid)")}}</span>
                                        @elseif($row->status === 'paid')
                                            <span class="badge badge-success">{{__("Paid")}}</span>
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
        </div>
        <div class="col-md-4">
            <!-- Cấu hình tài khoản thanh toán Affiliate -->
            <div class="panel" style="margin-top: 70px; border-radius: 10px; border: 1px solid #EAEAEA; background: #fff;">
                <div class="panel-title" style="padding: 15px; border-bottom: 1px solid #EAEAEA; background: #F9F9F9; border-top-left-radius: 9px; border-top-right-radius: 9px;">
                    <strong>{{__("Affiliate Bank Account")}}</strong>
                </div>
                <div class="panel-body" style="padding: 15px;">
                    @php
                        $affiliate_payout_accounts = $affiliate_payout_accounts ?? [];
                    @endphp
                    <button type="button" class="btn btn-primary btn-block" style="width: 100%" data-toggle="modal" data-target="#affiliatePayoutAccountsModal">
                        <i class="fa fa-credit-card mr-1"></i> {{__("Tài khoản đã lưu")}}
                    </button>
                    @if(!count($affiliate_payout_accounts))
                        <div class="text-muted mt-2" style="font-size: 12px;">
                            {{__("Bạn chưa thêm tài khoản nhận hoa hồng.")}}
                        </div>
                    @else
                        <div class="mt-3">
                            <div class="text-muted mb-1" style="font-size: 12px;">{{__("Tài khoản mặc định")}}</div>
                            <div class="p-2 border rounded bg-light" style="font-size: 12px;">
                                <strong>{{ $affiliate_payout_accounts[0]['bank_name'] }}</strong><br>
                                STK: <code class="text-danger">{{ $affiliate_payout_accounts[0]['account_number'] }}</code><br>
                                {{__("Chủ TK")}}: <strong>{{ strtoupper($affiliate_payout_accounts[0]['account_holder']) }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="affiliatePayoutAccountsModal" tabindex="-1" role="dialog" aria-labelledby="affiliatePayoutAccountsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="affiliatePayoutAccountsModalLabel">
                        <i class="fa fa-credit-card mr-1"></i> {{__("Tài khoản đã lưu")}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{__("Close")}}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(count($affiliate_payout_accounts))
                        <div class="row">
                            @foreach($affiliate_payout_accounts as $account)
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3 h-100 bg-light">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong class="text-dark">{{ $account['bank_name'] }}</strong>
                                                @if($loop->first)
                                                    <span class="badge badge-primary ml-1">{{__("Mặc định")}}</span>
                                                @endif
                                            </div>
                                            <i class="fa fa-university text-primary"></i>
                                        </div>
                                        <div class="mt-2" style="font-size: 13px; line-height: 1.6;">
                                            <div>{{__("Số tài khoản")}}: <code class="text-danger font-weight-bold">{{ $account['account_number'] }}</code></div>
                                            <div>{{__("Chủ tài khoản")}}: <strong>{{ strtoupper($account['account_holder']) }}</strong></div>
                                            @if(!empty($account['branch']))
                                                <div class="text-muted">{{__("Chi nhánh")}}: {{ $account['branch'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-3">
                            {{__("Bạn chưa thêm tài khoản nhận hoa hồng.")}}
                        </div>
                    @endif

                    <hr>
                    <button type="button" class="btn btn-outline-primary mb-3" data-toggle="collapse" data-target="#affiliateAddPayoutAccountForm" aria-expanded="{{ count($affiliate_payout_accounts) ? 'false' : 'true' }}">
                        <i class="fa fa-plus mr-1"></i> {{__("Thêm tài khoản")}}
                    </button>

                    <div class="collapse @if(!count($affiliate_payout_accounts)) show @endif" id="affiliateAddPayoutAccountForm">
                        <form action="{{ route('vendor.affiliate.save_payout_account') }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="mb-1"><strong>{{__("Bank Name")}}</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="affiliate_payout_account[bank_name]" class="form-control" required placeholder="{{__('e.g. Vietcombank, Techcombank')}}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-1"><strong>{{__("Account Number")}}</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="affiliate_payout_account[account_number]" class="form-control" required placeholder="{{__('e.g. 19033...')}}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-1"><strong>{{__("Account Holder Name")}}</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="affiliate_payout_account[account_holder]" class="form-control" required placeholder="{{__('e.g. NGUYEN VAN A')}}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-1"><strong>{{__("Branch (Optional)")}}</strong></label>
                                <input type="text" name="affiliate_payout_account[branch]" class="form-control" placeholder="{{__('e.g. Ha Noi Branch')}}">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-1"></i> {{__("Lưu tài khoản")}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
