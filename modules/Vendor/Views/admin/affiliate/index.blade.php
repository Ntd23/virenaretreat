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
                        <option value="approved" @if(Request()->status == 'approved') selected @endif>{{__('Approved (Unpaid)')}}</option>
                        <option value="paid" @if(Request()->status == 'paid') selected @endif>{{__('Paid')}}</option>
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
                                        @php
                                            $payout_account_json = \App\User::find($row->referrer_id)->getMeta('affiliate_payout_account');
                                            $payout_account = json_decode($payout_account_json, true) ?? [];
                                        @endphp
                                        @if(!empty($payout_account))
                                            <div class="mt-1 p-2 bg-light border rounded text-dark" style="font-size: 11px; line-height: 1.4; background-color: #f8f9fa; position: relative;">
                                                <a href="#" class="btn-vietqr" 
                                                   style="position: absolute; right: 8px; top: 8px;"
                                                   data-bank="{{ $payout_account['bank_name'] }}"
                                                   data-account="{{ $payout_account['account_number'] }}"
                                                   data-holder="{{ $payout_account['account_holder'] }}"
                                                   data-amount="{{ (int) $row->commission_amount }}"
                                                   data-info="Thanh toan hoa hong affiliate don hang {{ $row->booking_id }}"
                                                   title="Quét mã QR chuyển khoản nhanh">
                                                    <i class="fa fa-qrcode text-danger" style="font-size: 20px;"></i>
                                                </a>
                                                <i class="fa fa-university text-primary mr-1"></i><strong>{{ $payout_account['bank_name'] }}</strong><br>
                                                STK: <code class="text-danger font-weight-bold" style="font-size: 12px;">{{ $payout_account['account_number'] }}</code><br>
                                                Chủ TK: <strong>{{ strtoupper($payout_account['account_holder']) }}</strong>
                                                @if(!empty($payout_account['branch']))
                                                    <br><span class="text-muted" style="font-size: 10px;">CN: {{ $payout_account['branch'] }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-danger mt-1" style="font-size: 11px;"><i class="fa fa-exclamation-triangle"></i> {{ __('No bank account configured') }}</div>
                                        @endif
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
                                            <span class="badge badge-info text-white">{{ __('Approved (Unpaid)') }}</span>
                                        @elseif($row->status === 'paid')
                                            <span class="badge badge-success">{{ __('Paid') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ display_date($row->created_at) }}</td>
                                    <td>
                                        @if($row->status === 'pending')
                                            <div class="d-flex align-items-center">
                                                @if($row->booking_status === 'completed')
                                                    <form action="{{ route('vendor.admin.affiliate.commission.approve', ['id' => $row->id]) }}" method="post" class="mr-1" onsubmit="return confirm('{{ __('Are you sure you want to approve this commission?') }}')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">{{ __('Approve') }}</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-secondary mr-1" disabled title="{{ __('Only completed bookings can be approved') }}">{{ __('Approve') }}</button>
                                                @endif
                                                <form action="{{ route('vendor.admin.affiliate.commission.reject', ['id' => $row->id]) }}" method="post" onsubmit="return confirm('{{ __('Are you sure you want to reject this commission?') }}')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Reject') }}</button>
                                                </form>
                                            </div>
                                        @elseif($row->status === 'approved')
                                            <form action="{{ route('vendor.admin.affiliate.commission.pay', ['id' => $row->id]) }}" method="post" onsubmit="return confirm('{{ __('Are you sure you want to mark this commission as paid?') }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">{{ __('Mark as Paid') }}</button>
                                            </form>
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

    <!-- Modal VietQR -->
    <div class="modal fade" id="vietQrModal" tabindex="-1" role="dialog" aria-labelledby="vietQrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vietQrModalLabel"><i class="fa fa-qrcode mr-1"></i> Quét mã VietQR chuyển khoản</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="qr-loading" class="my-4"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i> Đang tạo mã QR...</div>
                    <img id="vietqr-image" src="" alt="VietQR" class="img-fluid d-none shadow-sm rounded border mb-3" style="max-height: 380px; max-width: 280px; margin: 0 auto;">
                    <div class="p-3 bg-light rounded text-left" style="font-size: 13px; line-height: 1.5; background-color: #f8f9fa;">
                        <div class="mb-1">Ngân hàng nhận: <strong id="qr-bank" class="text-dark"></strong></div>
                        <div class="mb-1">Số tài khoản: <strong id="qr-account" class="text-danger" style="font-size: 14px;"></strong></div>
                        <div class="mb-1">Chủ tài khoản: <strong id="qr-holder" class="text-dark"></strong></div>
                        <div class="mb-1">Số tiền: <strong id="qr-amount" class="text-success" style="font-size: 14px;"></strong></div>
                        <div class="mb-1">Nội dung CK: <strong id="qr-info" class="text-primary"></strong></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.btn-vietqr').on('click', function(e) {
                e.preventDefault();
                var bankName = $(this).data('bank');
                var accountNo = $(this).data('account');
                var accountHolder = $(this).data('holder');
                var amount = $(this).data('amount');
                var info = $(this).data('info');

                // Chuẩn hóa mã ngân hàng cho VietQR
                var bankMap = {
                    'vietcombank': 'VCB', 'vcb': 'VCB',
                    'techcombank': 'TCB', 'tcb': 'TCB',
                    'mbbank': 'MB', 'mb bank': 'MB', 'mb': 'MB',
                    'acb': 'ACB', 'vpb': 'VPB', 'vpbank': 'VPB',
                    'bidv': 'BIDV', 'vietinbank': 'CTG', 'ctg': 'CTG',
                    'tpbank': 'TPB', 'sacombank': 'STB', 'shb': 'SHB',
                    'hdbank': 'HDB', 'agribank': 'VBA', 'vib': 'VIB',
                    'ocb': 'OCB', 'msb': 'MSB'
                };
                var rawBank = bankName.toLowerCase();
                var bankCode = 'VCB'; // mặc định
                for (var key in bankMap) {
                    if (rawBank.indexOf(key) !== -1) {
                        bankCode = bankMap[key];
                        break;
                    }
                }

                // Tạo URL ảnh QR
                var qrUrl = "https://api.vietqr.io/image/" + bankCode + "-" + accountNo + "-compact2.jpg?amount=" + amount + "&addInfo=" + encodeURIComponent(info) + "&accountName=" + encodeURIComponent(accountHolder);

                $('#qr-bank').text(bankName);
                $('#qr-account').text(accountNo);
                $('#qr-holder').text(accountHolder.toUpperCase());
                $('#qr-amount').text(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount));
                $('#qr-info').text(info);

                $('#qr-loading').removeClass('d-none');
                $('#vietqr-image').addClass('d-none').attr('src', qrUrl);

                $('#vietQrModal').modal('show');
            });

            $('#vietqr-image').on('load', function() {
                $('#qr-loading').addClass('d-none');
                $(this).removeClass('d-none');
            });
        });
    </script>
@endpush
