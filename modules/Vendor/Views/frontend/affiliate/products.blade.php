@extends('layouts.user')

@section('content')
    <h2 class="title-bar">
        {{__("Affiliate Products")}}
    </h2>
    @include('admin.message')

    <div class="booking-history-manager">
        <div class="filter-div d-flex justify-content-between mb-3">
            <div class="col-left w-100 col-md-4 pl-0">
                <form method="get" action="{{ route('vendor.affiliate.products') }}" class="d-flex w-100">
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by name')}}" class="form-control mr-2">
                    <button class="btn btn-info btn_search" type="submit">{{__('Search')}}</button>
                </form>
            </div>
            <div class="col-right text-right align-self-center">
                <p class="m-0"><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-booking-history">
                <thead>
                <tr>
                    <th>{{__("Product Name")}}</th>
                    <th width="100px">{{__("Category")}}</th>
                    <th width="120px">{{__("Price")}}</th>
                    <th width="150px">{{__("Commission")}}</th>
                    <th>{{__("Referral Link")}}</th>
                </tr>
                </thead>
                <tbody>
                @if($rows->total() > 0)
                    @foreach($rows as $row)
                        @php
                            $refLink = $row->detail_url . (parse_url($row->detail_url, PHP_URL_QUERY) ? '&' : '?') . 'ref=' . Auth::id();
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ $row->detail_url }}" target="_blank" class="font-weight-bold">
                                    {{ $row->title }}
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ strtoupper($row->object_model) }}</span>
                            </td>
                            <td>{{ format_money($row->price) }}</td>
                            <td>
                                <strong class="text-success">
                                    {{ $row->affiliate_commission_type === 'percent' ? number_format($row->affiliate_commission_value).'%' : format_money($row->affiliate_commission_value) }}
                                </strong>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm bg-white" readonly value="{{ $refLink }}" id="ref-link-{{ $row->object_model }}-{{ $row->id }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-info copy-btn" type="button" data-target="ref-link-{{ $row->object_model }}-{{ $row->id }}">
                                            <i class="fa fa-copy mr-1"></i> {{__("Copy")}}
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">{{__("No products found for affiliate")}}</td>
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

@push('js')
    <script>
        $(document).ready(function() {
            $('.copy-btn').click(function() {
                var targetId = $(this).data('target');
                var copyText = document.getElementById(targetId);
                
                copyText.select();
                copyText.setSelectionRange(0, 99999); // Mobile
                
                navigator.clipboard.writeText(copyText.value).then(function() {
                    var $btn = $(copyText).closest('.input-group').find('.copy-btn');
                    var originalText = $btn.html();
                    
                    $btn.addClass('btn-success').removeClass('btn-info').html('<i class="fa fa-check mr-1"></i> {{__("Copied!")}}');
                    setTimeout(function() {
                        $btn.removeClass('btn-success').addClass('btn-info').html(originalText);
                    }, 2000);
                }).catch(function(err) {
                    alert('Could not copy text: ', err);
                });
            });
        });
    </script>
@endpush
