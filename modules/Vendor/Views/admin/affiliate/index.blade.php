@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Affiliate Commission Management")}}</h1>
        </div>
        @include('admin.message')
        <div class="filter-div">
            <form method="get" action="{{route('vendor.admin.affiliate.index')}}" class="filter-form d-flex align-items-end flex-column flex-lg-row" role="search" id="affiliate-filter-form">
                <div class="form-group mb-2 mr-lg-2">
                    <label>{{__("Status")}}</label>
                    <select name="status" class="form-control custom-select">
                        <option value="">{{__('-- Status --')}}</option>
                        <option value="unpaid" @if(Request()->status == 'unpaid') selected @endif>{{__('Unpaid')}}</option>
                        <option value="pending" @if(Request()->status == 'pending') selected @endif>{{__('Pending')}}</option>
                        <option value="approved" @if(Request()->status == 'approved') selected @endif>{{__('Approved (Unpaid)')}}</option>
                        <option value="paid" @if(Request()->status == 'paid') selected @endif>{{__('Paid')}}</option>
                        <option value="cancelled" @if(Request()->status == 'cancelled') selected @endif>{{__('Cancelled')}}</option>
                    </select>
                </div>
                <div class="form-group mb-2 mr-lg-2">
                    <label>{{__("Filter by")}}</label>
                    <select name="filter_type" class="form-control custom-select" id="affiliate-filter-type">
                        <option value="" @if(empty($date_filter['type'])) selected @endif>{{__("All dates")}}</option>
                        <option value="day" @if(($date_filter['type'] ?? '') == 'day') selected @endif>{{__("Day")}}</option>
                        <option value="month" @if(($date_filter['type'] ?? '') == 'month') selected @endif>{{__("Month")}}</option>
                        <option value="year" @if(($date_filter['type'] ?? '') == 'year') selected @endif>{{__("Year")}}</option>
                    </select>
                </div>
                <div class="form-group mb-2 mr-lg-2 affiliate-filter-control" data-filter-control="day">
                    <label>{{__("Day")}}</label>
                    <input type="date" name="filter_day" value="{{$date_filter['day'] ?? date('Y-m-d')}}" class="form-control">
                </div>
                <div class="form-group mb-2 mr-lg-2 affiliate-filter-control" data-filter-control="month">
                    <label>{{__("Month")}}</label>
                    <input type="month" name="filter_month" value="{{$date_filter['month'] ?? date('Y-m')}}" class="form-control">
                </div>
                <div class="form-group mb-2 mr-lg-2 affiliate-filter-control" data-filter-control="year">
                    <label>{{__("Year")}}</label>
                    <input type="number" name="filter_year" value="{{$date_filter['year'] ?? date('Y')}}" min="2000" max="2100" class="form-control">
                </div>
                <div class="form-group mb-2 mr-lg-2 flex-grow-1">
                    <label>{{__("Search")}}</label>
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by email, name or booking ID')}}" class="form-control">
                </div>
                <div class="form-group mb-2 mr-lg-2">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Filter')}}</button>
                    <a href="{{route('vendor.admin.affiliate.index')}}" class="btn btn-secondary">{{__('Reset')}}</a>
                </div>
                <div class="form-group mb-2 ml-lg-auto">
                    <label>{{__("Current period")}}</label>
                    <div class="form-control-plaintext font-weight-bold">{{$date_filter['label'] ?? __('All dates')}}</div>
                </div>
            </form>
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
                            <th width="280px">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($rows->total() > 0)
                            @foreach($rows as $row)
                                <tr>
                                    @php
                                        $payout_account_json = \App\User::find($row->referrer_id)->getMeta('affiliate_payout_account');
                                        $payout_account = json_decode($payout_account_json, true) ?? [];
                                        $affiliatePaymentCode = $row->affiliate_payment_code ?: ('AFPAY' . str_pad($row->id, 8, '0', STR_PAD_LEFT));
                                    @endphp
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
                                            <span class="badge badge-info text-white">{{ __('Approved (Unpaid)') }}</span>
                                        @elseif($row->status === 'paid')
                                            <span class="badge badge-success">{{ __('Paid') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ display_date($row->created_at) }}</td>
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                @if(!empty($payout_account))
                                                    <div class="p-2 bg-light border rounded text-dark" style="font-size: 11px; line-height: 1.4; background-color: #f8f9fa; position: relative;">
                                                        <a href="#" class="btn-vietqr"
                                                           style="position: absolute; right: 8px; top: 8px;"
                                                           data-bank="{{ $payout_account['bank_name'] }}"
                                                           data-account="{{ $payout_account['account_number'] }}"
                                                           data-holder="{{ $payout_account['account_holder'] }}"
                                                           data-amount="{{ (int) $row->commission_amount }}"
                                                           data-info="{{ $affiliatePaymentCode }}"
                                                           title="Quét mã QR chuyển khoản nhanh">
                                                            <i class="fa fa-qrcode text-danger" style="font-size: 20px;"></i>
                                                        </a>
                                                        <i class="fa fa-university text-primary mr-1"></i><strong>{{ $payout_account['bank_name'] }}</strong><br>
                                                        STK: <code class="text-danger font-weight-bold" style="font-size: 12px;">{{ $payout_account['account_number'] }}</code><br>
                                                        Mã thanh toán: <code class="text-primary font-weight-bold" style="font-size: 12px;">{{ $affiliatePaymentCode }}</code><br>
                                                        Chủ TK: <strong>{{ strtoupper($payout_account['account_holder']) }}</strong>
                                                        @if(!empty($payout_account['branch']))
                                                            <br><span class="text-muted" style="font-size: 10px;">CN: {{ $payout_account['branch'] }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-danger" style="font-size: 11px;"><i class="fa fa-exclamation-triangle"></i> {{ __('No bank account configured') }}</div>
                                                @endif
                                            </div>
                                            <div class="dropdown ml-2">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ __('Actions') }}">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if($row->status === 'pending')
                                                        @if($row->booking_status === 'completed')
                                                            <form action="{{ route('vendor.admin.affiliate.commission.approve', ['id' => $row->id]) }}" method="post" onsubmit="return confirm('{{ __('Are you sure you want to approve this commission?') }}')">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">{{ __('Approve') }}</button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="dropdown-item disabled" disabled>{{ __('Approve') }}</button>
                                                        @endif
                                                        <form action="{{ route('vendor.admin.affiliate.commission.reject', ['id' => $row->id]) }}" method="post" onsubmit="return confirm('{{ __('Are you sure you want to reject this commission?') }}')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger">{{ __('Reject') }}</button>
                                                        </form>
                                                    @elseif($row->status === 'approved')
                                                        <form action="{{ route('vendor.admin.affiliate.commission.pay', ['id' => $row->id]) }}" method="post" onsubmit="return confirm('{{ __('Are you sure you want to mark this commission as paid?') }}')">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-primary">{{ __('Mark as Paid') }}</button>
                                                        </form>
                                                    @else
                                                        <span class="dropdown-item text-muted">{{ __('No actions') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
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
                        <div class="mb-1">Mã thanh toán: <strong id="qr-info" class="text-primary"></strong></div>
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
            var filterType = $('#affiliate-filter-type');
            function toggleAffiliateFilterControls() {
                var selected = filterType.val();
                $('.affiliate-filter-control').hide();
                if (selected) {
                    $('[data-filter-control="' + selected + '"]').show();
                }
            }
            filterType.on('change', toggleAffiliateFilterControls);
            toggleAffiliateFilterControls();

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
